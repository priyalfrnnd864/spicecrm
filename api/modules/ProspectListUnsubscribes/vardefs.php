<?php
/**
 */

use SpiceCRM\includes\SpiceDictionary\SpiceDictionaryHandler;
use SpiceCRM\includes\SugarObjects\VardefManager;

SpiceDictionaryHandler::getInstance()->dictionary['ProspectListUnsubscribe'] = [
    'table' => 'prospectlistunsubscribes',
    'fields' => [
        'external_id' => [
            'name' => 'external_id',
            'vname' => 'LBL_EXTERNALID',
            'type' => 'varchar',
            'len' => 50,
        ],
        'contacts' => [
            'name' => 'contacts',
            'vname' => 'LBL_CONTACTS',
            'type' => 'link',
            'relationship' => 'prospectlistunsubscribes_contacts',
            'source' => 'non-db',
            'rel_fields' => [
                'email_addr_bean_rel_id' => [
                    'map' => 'prospectlists_person_email_addr_bean_rel_id'
                ]
            ]
        ],
        'is_default_sendgrid' => [
            'name' => 'is_default_sendgrid',
            'vname' => 'LBL_ISDEFAULT_SENDGRID',
            'type' => 'bool',
            'default' => 0,
        ],
        'prospectlists_person_email_addr_bean_rel_id' => [
            'name' => 'prospectlists_person_email_addr_bean_rel_id',
            'vname' => 'LBL_EMAIL_ADDRESS',
            'type' => 'varchar',
            'len' => '36',
            'source' => 'non-db'
        ],

    ]
];

VardefManager::createVardef('ProspectListUnsubscribes', 'ProspectListUnsubscribe', ['default', 'assignable']);
SpiceDictionaryHandler::getInstance()->dictionary['ProspectListUnsubscribe']['fields']['description']['required'] = true;