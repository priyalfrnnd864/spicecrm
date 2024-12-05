<?php
/*********************************************************************************
 * This file is part of SpiceCRM. SpiceCRM is an enhancement of SugarCRM Community Edition
 * and is developed by aac services k.s.. All rights are (c) 2016 by aac services k.s.
 * You can contact us at info@spicecrm.io
 * 
 * SpiceCRM is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 * 
 * SpiceCRM is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 ********************************************************************************/
use SpiceCRM\includes\SpiceDictionary\SpiceDictionaryHandler;

/* migrated
SpiceDictionaryHandler::getInstance()->dictionary['sysgdprretentions'] = [
    'table' => 'sysgdprretentions',
    'fields' => [
        'id' => [
            'name' => 'id',
            'type' => 'varchar',
            'len' => '36'
        ],
        'sysmodulefilter_id' => [
            'name' => 'sysmodulefilter_id',
            'type' => 'varchar',
            'len' => '36'
        ],
        'retention_type' => [
            'name' => 'retention_type',
            'type' => 'varchar',
            'len' => '5',
            'comment' => 'the type of action, options are I for inactive, D for delete, P for Purge'
        ],
        'delete_related' => [
            'name' => 'delete_related',
            'type' => 'varchar',
            'len' => '255',
            'comment' => 'a comma separated list of modules where records should also be deleted'
        ],
        'name' => [
            'name' => 'name',
            'type' => 'varchar',
            'len' => 255
        ],
        'description' => [
            'name' => 'description',
            'type' => 'text'
        ],
        'active' => [
            'name' => 'active',
            'type' => 'bool',
            'default' => 0
        ],
        'deleted' => [
            'name' => 'deleted',
            'type' => 'bool',
            'default' => 0
        ],
        'include_deleted' => [
            'name' => 'include_deleted',
            'type' => 'varchar',
            'len' => 1,
            'popupHelp' => 'LBL_INCLUDE_DELETED',
            'default' => '0',
            'comment' => ''
        ]
    ],
    'indices' => [
        [
            'name' => 'accounts_contactspk',
            'type' => 'primary',
            'fields' => ['id']
        ]
    ]
];
*/