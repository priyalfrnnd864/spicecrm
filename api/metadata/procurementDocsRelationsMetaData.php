<?php
/***** SPICE-HEADER-SPACEHOLDER *****/
use SpiceCRM\includes\SpiceDictionary\SpiceDictionaryHandler;

SpiceDictionaryHandler::getInstance()->dictionary['procurementdocsflow'] = [
    'table' => 'procurementdocsflow',
    'fields' => [
        ['name' => 'id', 'type' => 'id'],
        ['name' => 'procurementdoc_from_id', 'type' => 'id'],
        ['name' => 'procurementdoc_to_id', 'type' => 'id'],
        ['name' => 'date_modified', 'type' => 'datetime'],
        ['name' => 'deleted', 'type' => 'bool', 'required' => true, 'default' => false]
    ],
    'indices' => [
        ['name' => 'procurementdocsflowpk', 'type' => 'primary', 'fields' => ['id']]
    ],
    'relationships' => [
        'procurementdocsflow' => [
            'rhs_module' => 'ProcurementDocs',
            'rhs_table' => 'procurementdocs',
            'rhs_key' => 'id',
            'lhs_module' => 'ProcurementDocs',
            'lhs_table' => 'procurementdocs',
            'lhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'procurementdocsflow',
            'join_key_lhs' => 'procurementdoc_from_id',
            'join_key_rhs' => 'procurementdoc_to_id',
            'reverse' => 0
        ]
    ]
];

/**
 * handles two things:
 */
SpiceDictionaryHandler::getInstance()->dictionary['procurementdocsitemsflow'] = [
    'table' => 'procurementdocsitemsflow',
    'fields' => [
        ['name' => 'id', 'type' => 'id'],
        ['name' => 'procurementdocitem_from_id', 'type' => 'id'],
        ['name' => 'procurementdocitem_to_id', 'type' => 'id'],
        ['name' => 'date_modified', 'type' => 'datetime'],
        ['name' => 'deleted', 'type' => 'bool', 'required' => true, 'default' => false]
    ],
    'indices' => [
        ['name' => 'procurementdocsitemsflowpk', 'type' => 'primary', 'fields' => ['id']]
    ],
    'relationships' => [
        'procurementdocsitemsflow' => [
            'rhs_module' => 'ProcurementDocItems',
            'rhs_table' => 'procurementdocitems',
            'rhs_key' => 'id',
            'lhs_module' => 'ProcurementDocItems',
            'lhs_table' => 'procurementdocitems',
            'lhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'procurementdocsitemsflow',
            'join_key_lhs' => 'procurementdocitem_from_id',
            'join_key_rhs' => 'procurementdocitem_to_id',
            'reverse' => 0
        ]
    ]
];
