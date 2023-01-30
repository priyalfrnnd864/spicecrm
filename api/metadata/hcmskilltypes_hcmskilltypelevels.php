<?php
/***** SPICE-SUGAR-HEADER-SPACEHOLDER *****/
use SpiceCRM\includes\SpiceDictionary\SpiceDictionaryHandler;

SpiceDictionaryHandler::getInstance()->dictionary["hcmskilltypes_hcmskilltypelevels"] = [
    'true_relationship_type' => 'many-to-many',
    'relationships' =>
        [
            'hcmskilltypes_hcmskilltypelevels' =>
                [
                    'lhs_module' => 'HCMSkillTypes',
                    'lhs_table' => 'hcmskilltypes',
                    'lhs_key' => 'id',
                    'rhs_module' => 'HCMSkillTypeLevels',
                    'rhs_table' => 'hcmskilltypelevels',
                    'rhs_key' => 'id',
                    'relationship_type' => 'many-to-many',
                    'join_table' => 'hcmskilltypes_hcmskilltypelevels',
                    'join_key_lhs' => 'skilltype_id',
                    'join_key_rhs' => 'skilllevel_id',
                ],
        ],
    'table' => 'users_documentrevisions',
    'contenttype'   => 'relationdata',
    'fields' =>
        [
            0 =>
                [
                    'name' => 'id',
                    'type' => 'varchar',
                    'len' => 36,
                ],
            1 =>
                [
                    'name' => 'date_entered',
                    'type' => 'datetime',
                ],
            2 =>
                [
                    'name' => 'deleted',
                    'type' => 'bool',
                    'len' => '1',
                    'default' => '0',
                    'required' => true,
                ],
            3 =>
                [
                    'name' => 'skilltype_id',
                    'type' => 'varchar',
                    'len' => 36,
                ],
            4 =>
                [
                    'name' => 'skilllevel_id',
                    'type' => 'varchar',
                    'len' => 36,
                ],
            5 =>
                [
                    'name' => 'description',
                    'type' => 'text',
                ],
        ],
    'indices' => [],
];