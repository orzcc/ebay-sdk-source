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
 * @property string $title
 * @property \DTS\eBaySDK\Browse\Types\Image $image
 * @property string[] $gtins
 * @property string $brand
 * @property string[] $mpns
 * @property \DTS\eBaySDK\Browse\Types\AspectGroup[] $aspectGroups
 * @property \DTS\eBaySDK\Browse\Types\AdditionalProductIdentity[] $additionalProductIdentities
 * @property \DTS\eBaySDK\Browse\Types\ErrorDetailV3[] $warnings
 */
class Product extends \DTS\eBaySDK\Types\BaseType
{
    /**
     * @var array Properties belonging to objects of this class.
     */
    private static $propertyTypes = [
        'title' => [
            'type' => 'string',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'title'
        ],
        'image' => [
            'type' => 'DTS\eBaySDK\Browse\Types\Image',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'image'
        ],
        'gtins' => [
            'type' => 'string',
            'repeatable' => true,
            'attribute' => false,
            'elementName' => 'gtins'
        ],
        'brand' => [
            'type' => 'string',
            'repeatable' => false,
            'attribute' => false,
            'elementName' => 'brand'
        ],
        'mpns' => [
            'type' => 'string',
            'repeatable' => true,
            'attribute' => false,
            'elementName' => 'mpns'
        ],
        'aspectGroups' => [
            'type' => 'DTS\eBaySDK\Browse\Types\AspectGroup',
            'repeatable' => true,
            'attribute' => false,
            'elementName' => 'aspectGroups'
        ],
        'additionalProductIdentities' => [
            'type' => 'DTS\eBaySDK\Browse\Types\AdditionalProductIdentity',
            'repeatable' => true,
            'attribute' => false,
            'elementName' => 'additionalProductIdentities'
        ],
        'warnings' => [
            'type' => 'DTS\eBaySDK\Browse\Types\ErrorDetailV3',
            'repeatable' => true,
            'attribute' => false,
            'elementName' => 'warnings'
        ]
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
