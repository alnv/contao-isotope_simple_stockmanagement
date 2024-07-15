<?php

/**
 * This file is part of richardhj/contao-isotope_simple_stockmanagement.
 *
 * Copyright (c) 2016-2018 Richard Henkenjohann
 *
 * @package   richardhj/contao-isotope_simple_stockmanagement
 * @author    Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 * @copyright 2016-2018 Richard Henkenjohann
 * @license   https://github.com/richardhj/contao-isotope_simple_stockmanagement/blob/master/LICENSE LGPL-3.0
 */

use Richardhj\IsotopeSimpleStockManagement\BackendIntegration\LabelCallbackListener;

$table = Isotope\Model\Product::getTable();

// $GLOBALS['TL_DCA'][$table]['list']['label']['fields'][] = 'stock';

$GLOBALS['TL_DCA'][$table]['list']['label']['label_callback.default'] = $GLOBALS['TL_DCA'][$table]['list']['label']['label_callback'];
$GLOBALS['TL_DCA'][$table]['list']['label']['label_callback'] = [LabelCallbackListener::class, 'generate'];

/**
 * Fields
 */
$GLOBALS['TL_DCA'][$table]['fields']['stock'] = [
    'label' => &$GLOBALS['TL_LANG'][$table]['stock'],
    'inputType' => 'dcaWizard',
    'foreignTable' => Richardhj\IsotopeSimpleStockManagement\Model\Stock::getTable(),
    'params' => [
        'mode' => 2,
        'pid' => Contao\Input::get('id'),
        'act' => 'create',
    ],
    'eval' => [
        'fields' => ['quantity', 'source', 'product_collection_id', 'comment', 'tstamp'],
        'editButtonLabel' => ($GLOBALS['TL_LANG'][$table]['stock_create_button'] ?? ''),
        'orderField' => 'tstamp ASC',
        'showOperations' => true,
        'operations' => ['show'],
        'listCallback' => [
            Richardhj\IsotopeSimpleStockManagement\BackendIntegration\Dca::class,
            'generateWizardList',
        ],
        'tl_class' => 'clr',
    ],
    'attributes' => [
        'legend' => 'inventory_legend',
    ],
];
