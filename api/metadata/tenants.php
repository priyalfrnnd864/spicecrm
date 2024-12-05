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

SpiceDictionaryHandler::getInstance()->dictionary['tenants_deploymentpackages'] = [
    'table'         => 'tenants_deploymentpackages',
    'fields'        => [
        'id' => [
            'name' => 'id',
            'type' => 'id',
            'len'  => '36'
        ],
        'tenant_id' => [
            'name' => 'tenant_id',
            'type' => 'varchar',
            'len'  => '36',
        ],
        'deploymentpackage_id' => [
            'name' => 'deploymentpackage_id',
            'type' => 'varchar',
            'len'  => '36',
        ],
        'status' => [
            'name'    => 'status',
            'type'    => 'bool',
            'len'     => '25',
            'default' => '0',
        ],
        'date_modified' => [
            'name' => 'date_modified',
            'type' => 'datetime',
        ],
        'deleted' => [
            'name'     => 'deleted',
            'type'     => 'bool',
            'len'      => '1',
            'default'  => '0',
            'required' => false,
        ],
    ],
    'indices'       => [
        [
            'name'   => 'tenants_deploymentpackagespk',
            'type'   => 'primary',
            'fields' => ['id'],
        ],
        [
            'name'   => 'idx_ten_dpk_ten',
            'type'   => 'index',
            'fields' => ['tenant_id'],
        ],
        [
            'name'   => 'idx_ten_dpk_dpk',
            'type'   => 'index',
            'fields' => ['deploymentpackage_id'],
        ],
        [
            'name'   => 'idx_tenant_deploymentpackage',
            'type'   => 'alternate_key',
            'fields' => ['tenant_id','deploymentpackage_id'],
        ],
    ],
    'relationships' => [
        'tenants_deploymentpackages' => [
            'lhs_module'		=> 'Tenants',
            'lhs_table'			=> 'tenants',
            'lhs_key'			=> 'id',
            'rhs_module'		=> 'SystemDeploymentPackages',
            'rhs_table'			=> 'systemdeploymentpackages',
            'rhs_key'			=> 'id',
            'relationship_type'	=> 'many-to-many',
            'join_table'		=> 'tenants_deploymentpackages',
            'join_key_lhs'		=> 'tenant_id',
            'join_key_rhs'		=> 'deploymentpackage_id',
        ],
    ],
];

SpiceDictionaryHandler::getInstance()->dictionary['tenant_auth_users'] = [
    'table'         => 'tenant_auth_users',
    'fields'        => [
        'id' => [
            'name' => 'id',
            'type' => 'id'
        ],
        'tenant_id' => [
            'name' => 'tenant_id',
            'type' => 'varchar',
            'len'  => '36'
        ],
        'username' => [
            'name' => 'username',
            'type' => 'varchar',
            'len'  => 150
        ],
        'tenant_domain' => [
            'name' => 'tenant_domain',
            'type' => 'varchar',
        ]
    ],
    'indices'       => [
        [
            'name'   => 'tenants_users_pk',
            'type'   => 'primary',
            'fields' => ['id'],
        ],
        [
            'name'   => 'idx_tenant_username',
            'type'   => 'index',
            'fields' => ['username', 'tenant_domain'],
        ],
    ]
];
