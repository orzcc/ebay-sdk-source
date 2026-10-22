<?php

// 使用应用的锁定依赖执行离线回归，不安装或升级 SDK 的旧版测试工具。
// php test/compatibility/promises-php84.php /path/to/vendor/autoload.php 2 /path/to/result.json

use DTS\eBaySDK\Browse\Services\BrowseService;
use DTS\eBaySDK\Browse\Types\GetItemRestRequest;
use DTS\eBaySDK\Browse\Types\SearchForItemsRestRequest;
use DTS\eBaySDK\Finding\Services\FindingService;
use DTS\eBaySDK\Finding\Types\FindItemsByKeywordsRequest;
use DTS\eBaySDK\HttpHandler;
use DTS\eBaySDK\OAuth\Services\OAuthService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Pool;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

if (PHP_VERSION_ID < 80400 || PHP_VERSION_ID >= 80500 || $argc !== 4) {
    fwrite(STDERR, "Requires PHP 8.4 and arguments: autoload.php expected-major result.json\n");
    exit(2);
}

$diagnostics = [];
error_reporting(E_ALL);
set_error_handler(function ($level, $message, $file, $line) use (&$diagnostics) {
    if ($level === E_DEPRECATED || $level === E_USER_DEPRECATED) {
        $diagnostics[] = compact('level', 'message', 'file', 'line');
        return true;
    }
    if (error_reporting() & $level) {
        throw new ErrorException($message, 0, $level, $file, $line);
    }
    return false;
});

require $argv[1];
$version = Composer\InstalledVersions::getPrettyVersion('guzzlehttp/promises');
$checks = [];
$assertions = 0;
$assert = function ($condition, $message) use (&$assertions) {
    $assertions++;
    if (!$condition) {
        throw new RuntimeException($message);
    }
};
$run = function ($name, $test) use (&$checks) {
    try {
        $test();
        $checks[] = ['check' => $name, 'passed' => true];
    } catch (Throwable $e) {
        $checks[] = ['check' => $name, 'passed' => false, 'error' => get_class($e).': '.$e->getMessage()];
    }
};
$transport = function (array $queue, array &$history) {
    $stack = HandlerStack::create(new MockHandler($queue));
    $stack->push(Middleware::history($history));
    return new HttpHandler(new Client(['handler' => $stack]));
};
$browse = function ($handler) {
    return new BrowseService([
        'authorization' => 'offline-token',
        'marketplaceId' => 'EBAY_DE',
        'httpHandler' => $handler,
    ]);
};

$run('expected-promise-major', function () use ($assert, $version, $argv) {
    $assert(explode('.', ltrim($version, 'v'))[0] === $argv[2], 'Unexpected promises version: '.$version);
});

$run('http-success-and-option-filtering', function () use ($assert, $transport) {
    $history = [];
    $handler = $transport([new Response(200, [], 'OK')], $history);
    $promise = $handler(new Request('GET', 'https://offline.invalid/'), ['timeout' => 3, 'unknown_option' => true]);
    $assert($promise instanceof PromiseInterface, 'Handler must return a promise');
    $assert((string) $promise->wait()->getBody() === 'OK', 'Response body changed');
    $assert($history[0]['options']['timeout'] === 3, 'Timeout not forwarded');
    $assert(!isset($history[0]['options']['unknown_option']), 'Unsupported option was forwarded');
});

$run('http-transport-rejection', function () use ($assert, $transport) {
    $history = [];
    $request = new Request('GET', 'https://offline.invalid/');
    $expected = new ConnectException('offline failure', $request);
    $handler = $transport([$expected], $history);
    try {
        $handler($request, [])->wait();
        throw new RuntimeException('Expected rejection');
    } catch (ConnectException $actual) {
        $assert($actual === $expected, 'Rejection reason changed');
    }
});

$run('rest-sync-item-and-request-headers', function () use ($assert, $transport, $browse) {
    $history = [];
    $service = $browse($transport([new Response(200, [], json_encode([
        'itemId' => 'v1|123|0', 'title' => 'Offline item', 'price' => ['value' => '19.95', 'currency' => 'EUR'],
    ]))], $history));
    $response = $service->getItem(new GetItemRestRequest(['item_id' => 'v1|123|0', 'fieldgroups' => 'PRODUCT']));
    $assert($response->getStatusCode() === 200, 'REST status changed');
    $assert($response->itemId === 'v1|123|0' && $response->price->currency === 'EUR', 'Typed item parsing failed');
    $assert($response->toArray()['price']['value'] === '19.95', 'Nested serialization changed');
    $request = $history[0]['request'];
    $assert($request->getHeaderLine('Authorization') === 'Bearer offline-token', 'Authorization header changed');
    $assert($request->getHeaderLine('X-EBAY-C-MARKETPLACE-ID') === 'EBAY_DE', 'Marketplace header changed');
    $assert($request->getUri()->getQuery() === 'fieldgroups=PRODUCT', 'Query serialization changed');
});

$run('rest-async-search-and-callback', function () use ($assert, $transport, $browse) {
    $history = [];
    $service = $browse($transport([new Response(200, [], json_encode([
        'total' => 1, 'itemSummaries' => [['itemId' => 'v1|123|0', 'title' => 'Offline item']],
    ]))], $history));
    $promise = $service->searchForItemsAsync(new SearchForItemsRestRequest(['q' => 'offline', 'limit' => '1']));
    $assert($promise instanceof PromiseInterface, 'Async service must return a promise');
    $title = $promise->then(function ($response) use ($assert) {
        $assert($response->total === 1 && count($response->itemSummaries) === 1, 'Collection parsing failed');
        return $response->itemSummaries[0]->title;
    })->wait();
    $assert($title === 'Offline item', 'Chained result changed');
});

$run('rest-error-response-remains-typed', function () use ($assert, $transport, $browse) {
    $history = [];
    $service = $browse($transport([new Response(400, [], json_encode([
        'errors' => [['errorId' => 11001, 'message' => 'Offline bad request']],
    ]))], $history));
    $response = $service->getItem(new GetItemRestRequest(['item_id' => 'invalid']));
    $assert($response->getStatusCode() === 400, 'Error response should not become an HTTP exception');
    $assert($response->errors[0]->errorId === 11001, 'Error payload parsing failed');
});

$run('rest-rejection-callback', function () use ($assert, $transport, $browse) {
    $history = [];
    $expected = new ConnectException('offline failure', new Request('GET', 'https://offline.invalid/'));
    $service = $browse($transport([$expected], $history));
    $result = $service->getItemAsync(new GetItemRestRequest(['item_id' => '123']))
        ->otherwise(function ($reason) use ($assert, $expected) {
            $assert($reason === $expected, 'Async service swallowed transport failure');
            return 'recovered';
        })->wait();
    $assert($result === 'recovered', 'Recovery callback changed');
});

$run('rest-cancellation-propagates', function () use ($assert, $browse) {
    $cancelled = false;
    $pending = new Promise(null, function () use (&$cancelled) { $cancelled = true; });
    $client = new Client(['handler' => function () use ($pending) { return $pending; }]);
    $service = $browse(new HttpHandler($client));
    $promise = $service->getItemAsync(new GetItemRestRequest(['item_id' => '123']));
    $promise->cancel();
    $assert($cancelled && $pending->getState() === 'rejected', 'Cancellation did not reach transport');
    $assert($promise->getState() === 'rejected', 'Cancelled service promise was not rejected');
});

$run('xml-async-response-and-request', function () use ($assert, $transport) {
    $history = [];
    $xml = '<findItemsByKeywordsResponse xmlns="http://www.ebay.com/marketplace/search/v1/services">'
        .'<ack>Success</ack><searchResult count="1"><item><itemId>123</itemId><title>Offline item</title>'
        .'</item></searchResult></findItemsByKeywordsResponse>';
    $service = new FindingService([
        'credentials' => ['appId' => 'offline-app', 'devId' => 'offline-dev', 'certId' => 'offline-cert'],
        'httpHandler' => $transport([new Response(200, [], $xml)], $history),
    ]);
    $response = $service->findItemsByKeywordsAsync(new FindItemsByKeywordsRequest(['keywords' => 'offline']))->wait();
    $assert($response->ack === 'Success', 'XML ack parsing failed');
    $assert($response->searchResult->item[0]->itemId === '123', 'XML item parsing failed');
    $assert(strpos((string) $history[0]['request']->getBody(), '<keywords>offline</keywords>') !== false, 'XML request serialization failed');
});

$run('xml-http-error-still-rejects', function () use ($assert, $transport) {
    $history = [];
    $service = new FindingService([
        'credentials' => ['appId' => 'offline-app', 'devId' => 'offline-dev', 'certId' => 'offline-cert'],
        'httpHandler' => $transport([new Response(503, [], 'offline unavailable')], $history),
    ]);
    try {
        $service->findItemsByKeywords(new FindItemsByKeywordsRequest(['keywords' => 'offline']));
        throw new RuntimeException('Expected HTTP failure');
    } catch (ServerException $e) {
        $assert($e->getResponse()->getStatusCode() === 503, 'HTTP failure changed');
    }
});

$run('oauth-default-request-and-response', function () use ($assert, $transport) {
    $history = [];
    $service = new OAuthService([
        'credentials' => ['appId' => 'offline-app', 'devId' => 'offline-dev', 'certId' => 'offline-cert'],
        'ruName' => 'offline-redirect',
        'httpHandler' => $transport([new Response(200, [], '{"access_token":"offline-result","expires_in":7200,"token_type":"Application Access Token"}')], $history),
    ]);
    $response = $service->getAppToken();
    $assert($response->access_token === 'offline-result' && $response->expires_in === 7200, 'OAuth parsing failed');
    parse_str((string) $history[0]['request']->getBody(), $form);
    $assert($form['grant_type'] === 'client_credentials', 'OAuth default grant changed');
});

$run('guzzle-pool-fulfillment-and-rejection', function () use ($assert) {
    $client = new Client(['handler' => HandlerStack::create(new MockHandler([
        new Response(200, [], 'one'), new Response(503), new Response(200, [], 'three'),
    ]))]);
    $fulfilled = [];
    $rejected = [];
    $requests = array_map(function ($id) { return new Request('GET', 'https://offline.invalid/'.$id); }, [0, 1, 2]);
    $pool = new Pool($client, $requests, [
        'concurrency' => 2,
        'fulfilled' => function ($response, $index) use (&$fulfilled) { $fulfilled[$index] = (string) $response->getBody(); },
        'rejected' => function ($reason, $index) use (&$rejected) { $rejected[$index] = $reason; },
    ]);
    $pool->promise()->wait();
    $assert($fulfilled === [0 => 'one', 2 => 'three'], 'Pool fulfilled results changed');
    $assert(count($rejected) === 1 && $rejected[1] instanceof ServerException, 'Pool rejection changed');
});

$promiseDiagnostics = array_values(array_filter($diagnostics, function ($diagnostic) {
    return strpos($diagnostic['file'], '/guzzlehttp/promises/') !== false;
}));
$run('promise-deprecations', function () use ($assert, $argv, $promiseDiagnostics) {
    // 旧版作为对照必须能复现 warning；候选版本必须完全消除目标 warning。
    $assert($argv[2] === '1' ? count($promiseDiagnostics) > 0 : count($promiseDiagnostics) === 0,
        'Unexpected promise deprecation count: '.count($promiseDiagnostics));
});

$failures = array_values(array_filter($checks, function ($check) { return !$check['passed']; }));
$report = [
    'php' => PHP_VERSION,
    'promises' => $version,
    'sdk' => Composer\InstalledVersions::getPrettyVersion('orzcc/ebay-sdk-php'),
    'checks' => $checks,
    'assertions' => $assertions,
    'promise_deprecations' => count($promiseDiagnostics),
    'other_deprecations' => count($diagnostics) - count($promiseDiagnostics),
    'diagnostics' => $diagnostics,
];
file_put_contents($argv[3], json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n");
echo json_encode(['promises' => $version, 'checks' => count($checks), 'assertions' => $assertions,
    'failures' => $failures, 'promise_deprecations' => count($promiseDiagnostics),
    'other_deprecations' => $report['other_deprecations']], JSON_PRETTY_PRINT)."\n";
exit($failures ? 1 : 0);
