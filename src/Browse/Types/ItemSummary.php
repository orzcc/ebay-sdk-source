<?php
/**
 * DO NOT EDIT THIS FILE!
 *
 * This file was automatically generated from external sources.
 *
 * Any manual change here will be lost the next time the SDK
 * is updated. You've been warned!
 */

namespace DTS\eBaySDK\Browse\Types;

/**
 *
 * @property \DTS\eBaySDK\Browse\Types\Image[] $additionalImages
 * @property integer $bidCount
 * @property string[] $buyingOptions
 * @property \DTS\eBaySDK\Browse\Types\Category[] $categories
 * @property string $condition
 * @property string $conditionId
 * @property \DTS\eBaySDK\Browse\Types\ConvertedAmount $currentBidPrice
 * @property \DTS\eBaySDK\Browse\Types\TargetLocation $distanceFromPickupLocation
 * @property string $energyEfficiencyClass
 * @property string $epid
 * @property \DTS\eBaySDK\Browse\Types\Image $image
 * @property string $itemAffiliateWebUrl
 * @property string $itemGroupHref
 * @property string $itemGroupType
 * @property string $itemHref
 * @property string $itemId
 * @property \DTS\eBaySDK\Browse\Types\ItemLocationImpl $itemLocation
 * @property string $itemWebUrl
 * @property \DTS\eBaySDK\Browse\Types\MarketingPrice $marketingPrice
 * @property \DTS\eBaySDK\Browse\Types\PickupOptionSummary[] $pickupOptions
 * @property \DTS\eBaySDK\Browse\Types\ConvertedAmount $price
 * @property \DTS\eBaySDK\Browse\Types\Seller $seller
 * @property \DTS\eBaySDK\Browse\Types\ShippingOptionSummary[] $shippingOptions
 * @property \DTS\eBaySDK\Browse\Types\Image[] $thumbnailImages
 * @property string $title
 * @property boolean $priorityListing
 * @property string $shortDescription
 * @property string $legacyItemId
 * @property boolean $availableCoupons
 * @property string $itemCreationDate
 * @property string $itemEndDate
 * @property boolean $topRatedBuyingExperience
 * @property string $listingMarketplaceId
 * @property boolean $adultOnly
 * @property \DTS\eBaySDK\Browse\Enums\CompatibilityMatchEnum $compatibilityMatch
 * @property string $compatibilityMatch
 * @property \DTS\eBaySDK\Browse\Types\CompatibilityProperty[] $compatibilityProperties
 * @property string $qualifiedPrograms
 * @property \DTS\eBaySDK\Browse\Types\ConvertedAmount $unitPrice
 * @property integer $watchCount
 */
class ItemSummary extends \DTS\eBaySDK\Types\BaseType
{
    /**
     * @var array Properties belonging to objects of this class.
     */
    private static $propertyTypes = [
        'additionalImages' => [
            'type' => 'DTS\eBaySDK\Browse\Types\Image',
            'repeatable' => true,
            'attribute' => false,
            'elementName' => 'additionalImages'
        ],
        'bidCount' => [
            'type' => 'integer',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'bidCount'
        ],
        'buyingOptions' => [
            'type' => 'string',
            'repeatable' => true,
            'attribute' => false,
            'elementName' => 'buyingOptions'
        ],
        'categories' => [
            'type' => 'DTS\eBaySDK\Browse\Types\Category',
            'repeatable' => true,
            'attribute' => false,
            'elementName' => 'categories'
        ],
        'condition' => [
            'type' => 'string',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'condition'
        ],
        'conditionId' => [
            'type' => 'string',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'conditionId'
        ],
        'currentBidPrice' => [
            'type' => 'DTS\eBaySDK\Browse\Types\ConvertedAmount',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'currentBidPrice'
        ],
        'distanceFromPickupLocation' => [
            'type' => 'DTS\eBaySDK\Browse\Types\TargetLocation',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'distanceFromPickupLocation'
        ],
        'energyEfficiencyClass' => [
            'type' => 'string',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'energyEfficiencyClass'
        ],
        'epid' => [
            'type' => 'string',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'epid'
        ],
        'image' => [
            'type' => 'DTS\eBaySDK\Browse\Types\Image',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'image'
        ],
        'itemAffiliateWebUrl' => [
            'type' => 'string',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'itemAffiliateWebUrl'
        ],
        'itemGroupHref' => [
            'type' => 'string',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'itemGroupHref'
        ],
        'itemGroupType' => [
            'type' => 'string',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'itemGroupType'
        ],
        'itemHref' => [
            'type' => 'string',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'itemHref'
        ],
        'itemId' => [
            'type' => 'string',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'itemId'
        ],
        'itemLocation' => [
            'type' => 'DTS\eBaySDK\Browse\Types\ItemLocationImpl',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'itemLocation'
        ],
        'itemWebUrl' => [
            'type' => 'string',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'itemWebUrl'
        ],
        'marketingPrice' => [
            'type' => 'DTS\eBaySDK\Browse\Types\MarketingPrice',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'marketingPrice'
        ],
        'pickupOptions' => [
            'type' => 'DTS\eBaySDK\Browse\Types\PickupOptionSummary',
            'repeatable' => true,
            'attribute' => false,
            'elementName' => 'pickupOptions'
        ],
        'price' => [
            'type' => 'DTS\eBaySDK\Browse\Types\ConvertedAmount',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'price'
        ],
        'seller' => [
            'type' => 'DTS\eBaySDK\Browse\Types\Seller',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'seller'
        ],
        'shippingOptions' => [
            'type' => 'DTS\eBaySDK\Browse\Types\ShippingOptionSummary',
            'repeatable' => true,
            'attribute' => false,
            'elementName' => 'shippingOptions'
        ],
        'thumbnailImages' => [
            'type' => 'DTS\eBaySDK\Browse\Types\Image',
            'repeatable' => true,
            'attribute' => false,
            'elementName' => 'thumbnailImages'
        ],
        'title' => [
            'type' => 'string',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'title'
        ],
        'priorityListing' => [
            'type' => 'boolean',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'priorityListing'
        ],
        'shortDescription' => [
            'type' => 'string',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'shortDescription'
        ],
        'legacyItemId' => [
            'type' => 'string',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'legacyItemId'
        ],
        'availableCoupons' => [
            'type' => 'boolean',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'availableCoupons'
        ],
        'itemCreationDate' => [
            'type' => 'string',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'itemCreationDate'
        ],
        'itemEndDate' => [
            'type' => 'string',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'itemEndDate'
        ],
        'topRatedBuyingExperience' => [
            'type' => 'boolean',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'topRatedBuyingExperience'
        ],
        'listingMarketplaceId' => [
            'type' => 'string',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'listingMarketplaceId'
        ],
        'adultOnly' => [
            'type' => 'boolean',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'adultOnly'
        ],
        'compatibilityMatch' => [
            'type' => 'string',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'compatibilityMatch'
        ],
        'compatibilityProperties' => [
            'type' => 'DTS\eBaySDK\Browse\Types\CompatibilityProperty',
            'repeatable' => true,
            'attribute' => false,
            'elementName' => 'compatibilityProperties'
        ],
        'qualifiedPrograms' => [
            'type' => 'string',
            'repeatable' => true,
            'attribute' => false,
            'elementName' => 'qualifiedPrograms'
        ],
        'unitPrice' => [
            'type' => 'DTS\eBaySDK\Browse\Types\ConvertedAmount',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'unitPrice'
        ],
        'watchCount' => [
            'type' => 'integer',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'watchCount'
        ],
    ];

    /**
     * @param array $values Optional properties and values to assign to the object.
     */
    public function __construct(array $values = [])
    {
        list($parentValues, $childValues) = self::getParentValues(self::$propertyTypes, $values);

        parent::__construct($parentValues);

        if (!array_key_exists(__CLASS__, self::$properties)) {
            self::$properties[__CLASS__] = array_merge(self::$properties[get_parent_class()], self::$propertyTypes);
        }

        $this->setValues(__CLASS__, $childValues);
    }
}