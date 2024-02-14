<?php

/***** SPICE-SUGAR-HEADER-SPACEHOLDER *****/

use SpiceCRM\includes\SpiceDictionary\SpiceDictionaryHandler;

SpiceDictionaryHandler::getInstance()->dictionary['opportunities_products'] = [
    'table' => 'opportunities_products',
    'contenttype' => 'relationdata',
    'fields' => [
        'id' => ['name' => 'id', 'type' => 'char', 'len' => 36],
        'opportunity_id' => ['name' => 'opportunity_id', 'type' => 'char', 'len' => 36],
        'product_id' => ['name' => 'product_id', 'type' => 'char', 'len' => 36],
        'date_modified' => ['name' => 'date_modified', 'type' => 'datetime'],
        'deleted' => ['name' => 'deleted', 'type' => 'bool', 'len' => '1', 'required' => false, 'default' => 0],
    ],
    'indices' => [
        ['name' => 'opportunities_productspk', 'type' => 'primary', 'fields' => ['id']],
        ['name' => 'opportunities_productsalt', 'type' => 'alternate_key', 'fields' => ['opportunity_id', 'product_id']],
        ['name' => 'opportunities_products_oppproddel', 'type' => 'index', 'fields' => ['opportunity_id', 'product_id', 'deleted']],

    ],
    'relationships' => [
        'opportunities_products' => [
            'lhs_module' => 'Opportunities',
            'lhs_table' => 'opportunities',
            'lhs_key' => 'id',
            'rhs_module' => 'Products',
            'rhs_table' => 'products',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'opportunities_products',
            'join_key_lhs' => 'opportunity_id',
            'join_key_rhs' => 'product_id',
        ],
    ],
];
