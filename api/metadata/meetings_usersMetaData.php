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

SpiceDictionaryHandler::getInstance()->dictionary['meetings_users'] = [
	'table' => 'meetings_users',
    'contenttype'   => 'relationdata',
	'fields' => [
	    'id' => [
            'name' => 'id',
            'type' => 'varchar',
            'len'  => '36',
        ],
        'meeting_id' => [
            'name' => 'meeting_id',
            'type' => 'varchar',
            'len'  => '36',
        ],
        'user_id' => [
            'name' => 'user_id',
            'type' => 'varchar',
            'len'  => '36',
        ],
        'required' => [
            'name'	  => 'required',
            'type'	  => 'varchar',
            'len'	  => '1',
            'default' => '1',
        ],
        'accept_status' => [
            'name'    => 'accept_status',
            'type'    => 'varchar',
            'len'     => '25',
            'default' => 'none',
        ],
        'external_data' => [
            'name' => 'external_data',
            'type' => 'text',
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
	'indices' => [
		[
		    'name'			=> 'meetings_userspk',
			'type'			=> 'primary',
			'fields'		=> ['id'],
		],
		[
		    'name'			=> 'idx_usr_mtg_mtg',
			'type'			=> 'index',
			'fields'		=> ['meeting_id'],
		],
		[
		    'name'			=> 'idx_usr_mtg_usr',
			'type'			=> 'index',
			'fields'		=> ['user_id'],
		],
		[
		    'name'			=> 'idx_meeting_users',
			'type'			=> 'alternate_key',
			'fields'		=> ['meeting_id', 'user_id'],
		],
	],
	'relationships' => [
		'meetings_users' => [
			'lhs_module'		=> 'Meetings',
			'lhs_table'			=> 'meetings', 
			'lhs_key'			=> 'id',
			'rhs_module'		=> 'Users', 
			'rhs_table'			=> 'users', 
			'rhs_key'			=> 'id',
			'relationship_type'	=> 'many-to-many',
			'join_table'		=> 'meetings_users', 
			'join_key_lhs'		=> 'meeting_id', 
			'join_key_rhs'		=> 'user_id',
		],
        // CR1000356
        'meetings_users_status_accept' => [
            'lhs_module'		=> 'Meetings',
            'lhs_table'			=> 'meetings',
            'lhs_key'			=> 'id',
            'rhs_module'		=> 'Users',
            'rhs_table'			=> 'users',
            'rhs_key'			=> 'id',
            'relationship_type'	=> 'many-to-many',
            'relationship_role_column'	=> 'accept_status',
            'relationship_role_column_value'	=> 'accept',
            'join_table'		=> 'meetings_users',
            'join_key_lhs'		=> 'meeting_id',
            'join_key_rhs'		=> 'user_id'
        ],
        'meetings_users_status_decline' => [
            'lhs_module'		=> 'Meetings',
            'lhs_table'			=> 'meetings',
            'lhs_key'			=> 'id',
            'rhs_module'		=> 'Users',
            'rhs_table'			=> 'users',
            'rhs_key'			=> 'id',
            'relationship_type'	=> 'many-to-many',
            'relationship_role_column'	=> 'accept_status',
            'relationship_role_column_value'	=> 'decline',
            'join_table'		=> 'meetings_users',
            'join_key_lhs'		=> 'meeting_id',
            'join_key_rhs'		=> 'user_id'
        ],
        'meetings_users_status_tentative' => [
            'lhs_module'		=> 'Meetings',
            'lhs_table'			=> 'meetings',
            'lhs_key'			=> 'id',
            'rhs_module'		=> 'Users',
            'rhs_table'			=> 'users',
            'rhs_key'			=> 'id',
            'relationship_type'	=> 'many-to-many',
            'relationship_role_column'	=> 'accept_status',
            'relationship_role_column_value'	=> 'tentative',
            'join_table'		=> 'meetings_users',
            'join_key_lhs'		=> 'meeting_id',
            'join_key_rhs'		=> 'user_id'
        ],
	],
];
