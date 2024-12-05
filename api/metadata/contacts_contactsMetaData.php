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

SpiceDictionaryHandler::getInstance()->dictionary['contacts_contacts'] = [
    'table' => 'contacts_contacts',
    'contenttype'   => 'relationdata',
    'fields' => [
        ['name' => 'id', 'type' => 'varchar', 'len' => '36'],
        ['name' => 'parent_id', 'type' => 'varchar', 'len' => '36'],
        ['name' => 'child_id', 'type' => 'varchar', 'len' => '36'],
        ['name' => 'relationship_type', 'type' => 'enum', 'len' => '50', 'options' => 'relationship_type_dom'],
        ['name' => 'date_modified', 'type' => 'datetime'],
        ['name' => 'deleted', 'type' => 'bool', 'len' => '1', 'required' => false, 'default' => '0']
    ],
    'indices' => [
        ['name' => 'contacts_contacts_key', 'type' => 'primary', 'fields' => ['id']],
        ['name' => 'idx_contacts_contacts_parent', 'type' => 'index', 'fields' => ['parent_id']],
        ['name' => 'idx_contacts_contacts_child', 'type' => 'index', 'fields' => ['child_id']],
        ['name' => 'idx_contacts_contacts_parent_child', 'type' => 'alternate_key', 'fields' => ['parent_id', 'child_id']],
        ['name' => 'idx_contacts_contacts_deleted', 'type' => 'index', 'fields' => ['deleted']]
    ],
    'relationships' => [
        'contacts_contacts' => [
            'lhs_module' => 'Contacts', 'lhs_table' => 'contacts', 'lhs_key' => 'id',
            'rhs_module' => 'Contacts', 'rhs_table' => 'contacts', 'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'contacts_contacts',
            'join_key_lhs' => 'parent_id',
            'join_key_rhs' => 'child_id',
            'reverse' => 1
        ]
    ]
];
