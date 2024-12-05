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
// migrated
//use SpiceCRM\includes\SpiceDictionary\SpiceDictionaryHandler;
//
//SpiceDictionaryHandler::getInstance()->dictionary['orgunits_employees'] = [
//    'table' => 'orgunits_employees',
//    'contenttype'   => 'relationdata',
//    'fields' => [
//        [
//            'name' => 'id',
//            'type' => 'id',
//        ],
//        [
//            'name' => 'orgunit_id',
//            'type' => 'id',
//        ],
//        [
//            'name' => 'employee_id',
//            'type' => 'id',
//        ],
//        [
//            'name' => 'is_primary_orgunit',
//            'type' => 'bool',
//            'default' => 0,
//        ],
//        [
//            'name' => 'employee_role',
//            'type' => 'varchar',
//            'len' => '50'
//        ],
//        [
//            'name' => 'date_modified',
//            'type' => 'datetime'
//        ],
//        [
//            'name' => 'deleted',
//            'type' => 'bool',
//            'default' => 0,
//        ]
//    ],
//    'indices' => [
//        [
//            'name' => 'orgunits_employeespk',
//            'type' => 'primary',
//            'fields' => ['id']
//        ],
//        [
//            'name' => 'idx_orgunits_employees_orgid',
//            'type' => 'index',
//            'fields' => ['orgunit_id', 'is_primary_orgunit']
//        ],
//        [
//            'name' => 'idx_orgunits_employees_employid',
//            'type' => 'index',
//            'fields' => ['employee_id', 'is_primary_orgunit']
//        ],
//        [
//            'name' => 'idx_orgunits_employees_alt',
//            'type' => 'alternate_key',
//            'fields' => ['orgunit_id', 'employee_id']
//        ]
//    ],
//    'relationships' => [
//        'orgunits_employees' => [
//            'lhs_module' => 'OrgUnits',
//            'lhs_table' => 'orgunits',
//            'lhs_key' => 'id',
//            'rhs_module' => 'Employees',
//            'rhs_table' => 'employees',
//            'rhs_key' => 'id',
//            'relationship_type' => 'many-to-many',
//            'join_table' => 'orgunits_employees',
//            'join_key_lhs' => 'orgunit_id',
//            'join_key_rhs' => 'employee_id'
//        ],
//        'orgunits_employees_primary' => [
//            'lhs_module' => 'OrgUnits',
//            'lhs_table' => 'orgunits',
//            'lhs_key' => 'id',
//            'rhs_module' => 'Employees',
//            'rhs_table' => 'employees',
//            'rhs_key' => 'id',
//            'relationship_type' => 'many-to-many',
//            'join_table' => 'orgunits_employees',
//            'join_key_lhs' => 'orgunit_id',
//            'join_key_rhs' => 'employee_id',
//            'relationship_role_column' => 'is_primary_orgunit',
//            'relationship_role_column_value' => 1
//        ],
//    ]
//];
