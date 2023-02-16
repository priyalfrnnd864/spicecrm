<?php
/***** SPICE-SUGAR-HEADER-SPACEHOLDER *****/
use SpiceCRM\includes\SpiceDictionary\SpiceDictionaryHandler;

SpiceDictionaryHandler::getInstance()->dictionary["users_hcmskilltypes"] = [
    'true_relationship_type' => 'many-to-many',
    'relationships' =>
        [
            'users_hcmskilltypes' =>
                [
                    'lhs_module' => 'Users',
                    'lhs_table' => 'users',
                    'lhs_key' => 'id',
                    'rhs_module' => 'HCMSkilltypes',
                    'rhs_table' => 'hcmskilltypes',
                    'rhs_key' => 'id',
                    'relationship_type' => 'many-to-many',
                    'join_table' => 'users_hcmskilltypes',
                    'join_key_lhs' => 'user_id',
                    'join_key_rhs' => 'hcmskilltypes_id',
                ],
        ],
    'table' => 'users_hcmskilltypes',
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
                    'name' => 'user_id',
                    'type' => 'varchar',
                    'len' => 36,
                ],
            4 =>
                [
                    'name' => 'hcmskilltypes_id',
                    'type' => 'varchar',
                    'len' => 36,
                ],
            5 =>
                [
                    'name' => 'acceptance_status',
                    'type' => 'bool',
                ],
            6 =>
                [
                    'name' => 'date_update_accepted',
                    'type' => 'datetime',
                ],
        ],
    'indices' =>
        [
            0 =>
                [
                    'name' => 'users_hcmskilltypesspk',
                    'type' => 'primary',
                    'fields' =>
                        [
                            0 => 'id',
                        ],
                ],
            1 =>
                [
                    'name' => 'idx_user_hcmskill_user_id',
                    'type' => 'alternate_key',
                    'fields' =>
                        [
                            0 => 'user_id',
                            1 => 'hcmskilltypes_id',
                        ],
                ],
            2 =>
                [
                    'name' => 'idx_user_hcmskill_hcmskill_id',
                    'type' => 'alternate_key',
                    'fields' =>
                        [
                            0 => 'hcmskilltypes_id',
                            1 => 'user_id',
                        ],
                ],
        ],
];