<?php
/***** SPICE-HEADER-SPACEHOLDER *****/

use SpiceCRM\includes\SpiceDictionary\SpiceDictionaryHandler;
/* migrated to system
SpiceDictionaryHandler::getInstance()->dictionary['sysduplicatesbeans'] = [
    'table' => 'sysduplicatesbeans',
    'comment' => 'table containing duplicate Bean IDs and their status',
    'fields' => [
        'id' => [
            'name' => 'id',
            'vname' => 'LBL_ID',
            'type' => 'id',
            'required' => true,
            'reportable' => true,
            'comment' => 'Unique identifier'
        ],
        'bean_type' => [
            'name' => 'bean_type',
            'type' => 'varchar',
            'len' => '50'
        ],
        'bean_id_right' => [
            'name' => 'bean_id_right',
            'type' => 'id',
            'comment' => 'parent Bean'
        ],
        'bean_id_left' => [
            'name' => 'bean_id_left',
            'type' => 'id',
            'comment' => 'duplicate Bean'
        ],
        'date_created' => [
            'name' => 'date_created',
            'type' => 'datetime'
        ],
        'date_modified' => [
            'name' => 'date_modified',
            'type' => 'datetime'
        ],
        'created_by' => [
            'name' => 'created_by',
            'type' => 'id',
        ],
        'modified_by' => [
            'name' => 'modified_by',
            'type' => 'id',
        ],
        'duplicate_status' => [
            'name' => 'duplicate_status',
            'type' => 'enum',
            'vname' => 'LBL_DUPLICATE_STATUS',
            'options' => 'duplicate_status_dom',
            'len' => '20',
            'comment' => 'status of the duplicate acceptance/check',
        ],
        'deleted' => [
            'name' => 'deleted',
            'vname' => 'LBL_DELETED',
            'type' => 'bool',
            'default' => 0
        ],
    ],
    'indices' => [
        ['name' => 'sysduplicatesbeanspk', 'type' => 'primary', 'fields' => ['id']],
    ],
];
*/