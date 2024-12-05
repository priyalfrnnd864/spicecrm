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
SpiceDictionaryHandler::getInstance()->dictionary['workflowtasktypes'] = [
    'table' => 'workflowtasktypes',
    'fields' => [
        'id' => [
            'name' => 'id',
            'type' => 'id',
            'required' => true
        ],
        'name' => [
            'name' => 'name',
            'type' => 'varchar',
            'len' => 50
        ],
        'handler_class' => [
            'name' => 'handler_class',
            'type' => 'varchar'
        ],
        'admin_component' => [
            'name' => 'admin_component',
            'type' => 'varchar',
            'len' => 100
        ],
        'frontend_component' => [
            'name' => 'frontend_component',
            'type' => 'varchar',
            'len' => 100
        ],
        'type' => [
            'name' => 'type',
            'type' => 'enum',
            'options' => 'workflowtasktypes_type_enum',
            'default' => 'regular'
        ],
        'icon' => [
            'name' => 'icon',
            'type' => 'enum',
            'dbtype' => 'varchar',
            'len' => 255,
            'options' => 'workflowtasktypes_icon_enum'
        ],
        'assignable' => [
            'name' => 'assignable',
            'type' => 'bool',
            'default' => true,
            'comment' => 'if tasks of this type can be assigned to users'
        ],
        'has_timing' => [
            'name' => 'has_timing',
            'type' => 'bool',
            'default' => true,
            'comment' => 'if tasks of this type can set timing'
        ],
        'typedefaults' => [
            'name' => 'typedefaults',
            'type' => 'json',
            'comment' => 'any defaults for this tasktype'
        ],
        'deleted' => [
            'name' => 'deleted',
            'type' => 'bool',
        ],
        'version' => [
            'name' => 'version',
            'type' => 'varchar',
            'len' => 16
        ],
        'package' => [
            'name' => 'package',
            'type' => 'varchar',
            'len' => 32
        ]
    ],
    'indices' => [
        [
            'name' => 'workflowtasktypespk',
            'type' => 'primary',
            'fields' => ['id']
        ],
        [
            'name' => 'idx_workflowtasktypes_deleted',
            'type' => 'index',
            'fields' => ['deleted']
        ]
    ]
];
*/