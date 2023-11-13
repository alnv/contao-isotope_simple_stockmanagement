<?php

/**
 * This file is part of richardhj/contao-isotope_simple_stockmanagement.
 *
 * Copyright (c) 2016-2018 Richard Henkenjohann
 *
 * @package   richardhj/contao-isotope_simple_stockmanagement
 * @author    Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 * @copyright 2016-2018 Richard Henkenjohann
 * @license   https://github.com/richardhj/richardhj/contao-isotope_simple_stockmanagement/blob/master/LICENSE LGPL-3.0
 */


namespace Richardhj\IsotopeSimpleStockManagement\Model;

use Contao\Model;


/**
 * Class Stock
 *
 * @property int $tstamp
 * @property int $pid
 * @property int $product_collection_id
 * @property int $quantity
 * @property int $source
 * @property string $comment
 * @package Isotope\Model
 */
class Stock extends Model
{

    /**
     * Stock management sources
     */
    const STOCKMANAGEMENT_SOURCE_ORDER = 'order';
    const STOCKMANAGEMENT_SOURCE_BACKEND = 'backend';
    const STOCKMANAGEMENT_SOURCE_IMPORT = 'import';


    /**
     * Name of the current table
     *
     * @var string
     */
    protected static $strTable = 'tl_iso_stock';


    /**
     * Find all stock entries for one product
     *
     * @param int $product The product's id
     *
     * @return \Model\Collection|static
     */
    public static function findForProduct($product)
    {
        return static::findBy('pid', $product);
    }


    /**
     * Get the stock of the product
     *
     * @param int $product
     *
     * @return int|false if none entries in database
     */
    public static function getStockForProduct($product)
    {
        $entries = static::findForProduct($product);
        if (null === $entries) {
            return false;
        }

        $stockedUp = 0 < count(
                array_filter(
                    iterator_to_array($entries),
                    function ($entry) {
                        /** @var Stock $entry */
                        return ($entry->quantity > 0);
                    }
                )
            );

        // Product never got stocked up (no positive stock booking)
        if (false === $stockedUp) {
            // Returning false prevents the product from getting disabled.
            return false;
        }

        $stock = 0;

        while ($entries->next()) {
            $stock += $entries->quantity;
        }

        return $stock;
    }
}
