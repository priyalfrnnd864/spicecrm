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
/**
 * Relationship table linking emails with 1 or more SpiceBeans
 */
SpiceDictionaryHandler::getInstance()->dictionary['emails_beans'] = [
    'table' => 'emails_beans',
    'contenttype'   => 'relationdata',
    'fields' => [
        'id' => [
            'name' => 'id',
            'type' => 'varchar',
            'dbType' => 'id',
            'len' => '36'
        ],
        'email_id' => [
            'name' => 'email_id',
            'type' => 'varchar',
            'dbType' => 'id',
            'len' => '36',
            'comment' => 'FK to emails table',
        ],
        'bean_id' => [
            'name' => 'bean_id',
            'dbType' => 'id',
            'type' => 'varchar',
            'len' => '36',
            'comment' => 'FK to various beans\'s tables',
        ],
        'bean_module' => [
            'name' => 'bean_module',
            'type' => 'varchar',
            'len' => '100',
            'comment' => 'bean\'s Module',
        ],
        'campaign_data' => ['name' => 'campaign_data',
            'type' => 'text',
        ],
        'date_modified' => [
            'name' => 'date_modified',
            'type' => 'datetime'
        ],
        'deleted' => [
            'name' => 'deleted',
            'type' => 'bool',
            'len' => '1',
            'default' => '0',
            'required' => false
        ]
    ],
    'indices' => [
        'emails_beanspk' => [
            'name' => 'emails_beanspk',
            'type' => 'primary',
            'fields' => ['id']
        ],
        'idx_emails_beans_bean_id' => [
            'name' => 'idx_emails_beans_bean_id',
            'type' => 'index',
            'fields' => ['bean_id']
        ],
        'idx_emails_beans_email_bean' => [
            'name' => 'idx_emails_beans_email_bean',
            'type' => 'alternate_key',
            'fields' => ['email_id', 'bean_id', 'deleted']
        ],
        'idx_emails_beans_email_beanmodule' => [
            'name' => 'idx_emails_beans_email_beanmodule',
            'type' => 'index',
            'fields' => ['bean_module', 'deleted']
        ],
    ],
    'relationships' => [
        'emails_accounts_rel' => [
            'lhs_module' => 'Emails',
            'lhs_table' => 'emails',
            'lhs_key' => 'id',
            'rhs_module' => 'Accounts',
            'rhs_table' => 'accounts',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'emails_beans',
            'join_key_lhs' => 'email_id',
            'join_key_rhs' => 'bean_id',
            'relationship_role_column' => 'bean_module',
            'relationship_role_column_value' => 'Accounts',
        ],
// CR1000426 cleanup backend, module Bugs removed
//		'emails_bugs_rel' => array(
//			'lhs_module'		=> 'Emails',
//			'lhs_table'			=> 'emails',
//			'lhs_key'			=> 'id',
//			'rhs_module'		=> 'Bugs',
//			'rhs_table'			=> 'bugs',
//			'rhs_key'			=> 'id',
//			'relationship_type'	=> 'many-to-many',
//			'join_table'		=> 'emails_beans',
//			'join_key_lhs'		=> 'email_id',
//			'join_key_rhs'		=> 'bean_id',
//            'relationship_role_column' => 'bean_module',
//            'relationship_role_column_value' => 'Bugs',
//		),
// CR1000426 cleanup backend, module Cases removed
//		'emails_cases_rel' => array(
//			'lhs_module'		=> 'Emails',
//			'lhs_table'			=> 'emails',
//			'lhs_key'			=> 'id',
//			'rhs_module'		=> 'Cases',
//			'rhs_table'			=> 'cases',
//			'rhs_key'			=> 'id',
//			'relationship_type'	=> 'many-to-many',
//			'join_table'		=> 'emails_beans',
//			'join_key_lhs'		=> 'email_id',
//			'join_key_rhs'		=> 'bean_id',
//            'relationship_role_column' => 'bean_module',
//            'relationship_role_column_value' => 'Cases',
//		),
        'emails_contacts_rel' => [
            'lhs_module' => 'Emails',
            'lhs_table' => 'emails',
            'lhs_key' => 'id',
            'rhs_module' => 'Contacts',
            'rhs_table' => 'contacts',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'relationship_role_column' => 'bean_module',
            'relationship_role_column_value' => 'Contacts',
            'join_table' => 'emails_beans',
            'join_key_lhs' => 'email_id',
            'join_key_rhs' => 'bean_id',
        ],
        'emails_leads_rel' => [
            'lhs_module' => 'Emails',
            'lhs_table' => 'emails',
            'lhs_key' => 'id',
            'rhs_module' => 'Leads',
            'rhs_table' => 'leads',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'emails_beans',
            'join_key_lhs' => 'email_id',
            'join_key_rhs' => 'bean_id',
            'relationship_role_column' => 'bean_module',
            'relationship_role_column_value' => 'Leads',
        ],
        'emails_opportunities_rel' => [
            'lhs_module' => 'Emails',
            'lhs_table' => 'emails',
            'lhs_key' => 'id',
            'rhs_module' => 'Opportunities',
            'rhs_table' => 'opportunities',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'emails_beans',
            'join_key_lhs' => 'email_id',
            'join_key_rhs' => 'bean_id',
            'relationship_role_column' => 'bean_module',
            'relationship_role_column_value' => 'Opportunities',
        ],
        'emails_tasks_rel' => [
            'lhs_module' => 'Emails',
            'lhs_table' => 'emails',
            'lhs_key' => 'id',
            'rhs_module' => 'Tasks',
            'rhs_table' => 'tasks',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'emails_beans',
            'join_key_lhs' => 'email_id',
            'join_key_rhs' => 'bean_id',
            'relationship_role_column' => 'bean_module',
            'relationship_role_column_value' => 'Tasks',
        ],
        'emails_users_rel' => [
            'lhs_module' => 'Emails',
            'lhs_table' => 'emails',
            'lhs_key' => 'id',
            'rhs_module' => 'Users',
            'rhs_table' => 'users',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'emails_beans',
            'join_key_lhs' => 'email_id',
            'join_key_rhs' => 'bean_id',
            'relationship_role_column' => 'bean_module',
            'relationship_role_column_value' => 'Users',
        ],
        'emails_projects_rel' => [
            'lhs_module' => 'Emails',
            'lhs_table' => 'emails',
            'lhs_key' => 'id',
            'rhs_module' => 'Projects',
            'rhs_table' => 'projects',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'emails_beans',
            'join_key_lhs' => 'email_id',
            'join_key_rhs' => 'bean_id',
            'relationship_role_column' => 'bean_module',
            'relationship_role_column_value' => 'Projects',
        ],
        'emails_projectwbss_rel' => [
            'lhs_module' => 'Emails',
            'lhs_table' => 'emails',
            'lhs_key' => 'id',
            'rhs_module' => 'ProjectWBSs',
            'rhs_table' => 'projectwbss',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'emails_beans',
            'join_key_lhs' => 'email_id',
            'join_key_rhs' => 'bean_id',
            'relationship_role_column' => 'bean_module',
            'relationship_role_column_value' => 'ProjectWBSs',
        ],
        'emails_prospects_rel' => [
            'lhs_module' => 'Emails',
            'lhs_table' => 'emails',
            'lhs_key' => 'id',
            'rhs_module' => 'Prospects',
            'rhs_table' => 'prospects',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'emails_beans',
            'join_key_lhs' => 'email_id',
            'join_key_rhs' => 'bean_id',
            'relationship_role_column' => 'bean_module',
            'relationship_role_column_value' => 'Prospects',
        ],
        'emails_quotes' => [
            'lhs_module' => 'Emails',
            'lhs_table' => 'emails',
            'lhs_key' => 'id',
            'rhs_module' => 'Quotes',
            'rhs_table' => 'quotes',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'emails_beans',
            'join_key_lhs' => 'email_id',
            'join_key_rhs' => 'bean_id',
            'relationship_role_column' => 'bean_module',
            'relationship_role_column_value' => 'Quotes',
        ],
        'emails_emailtrackinglinks' => [
            'lhs_module' => 'Emails',
            'lhs_table' => 'emails',
            'lhs_key' => 'id',
            'rhs_module' => 'EmailTrackingLinks',
            'rhs_table' => 'emailtrackinglinks',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'emails_beans',
            'join_key_lhs' => 'email_id',
            'join_key_rhs' => 'bean_id',
            'relationship_role_column' => 'bean_module',
            'relationship_role_column_value' => 'EmailTrackingLinks',
        ]
    ]
];

if (file_exists('modules/ServiceOrders/ServiceOrder.php')) {
    SpiceDictionaryHandler::getInstance()->dictionary['emails_beans']['relationships']['emails_serviceorders_rel'] = [
        'lhs_module' => 'Emails',
        'lhs_table' => 'emails',
        'lhs_key' => 'id',
        'rhs_module' => 'ServiceOrders',
        'rhs_table' => 'serviceorders',
        'rhs_key' => 'id',
        'relationship_type' => 'many-to-many',
        'join_table' => 'emails_beans',
        'join_key_lhs' => 'email_id',
        'join_key_rhs' => 'bean_id',
        'relationship_role_column' => 'bean_module',
        'relationship_role_column_value' => 'ServiceOrders',
    ];
}

if (file_exists('modules/ServiceTickets/ServiceTicket.php')) {
    SpiceDictionaryHandler::getInstance()->dictionary['emails_beans']['relationships']['emails_servicetickets_rel'] = [
        'lhs_module' => 'Emails',
        'lhs_table' => 'emails',
        'lhs_key' => 'id',
        'rhs_module' => 'ServiceTickets',
        'rhs_table' => 'servicetickets',
        'rhs_key' => 'id',
        'relationship_type' => 'many-to-many',
        'join_table' => 'emails_beans',
        'join_key_lhs' => 'email_id',
        'join_key_rhs' => 'bean_id',
        'relationship_role_column' => 'bean_module',
        'relationship_role_column_value' => 'ServiceTickets',
    ];
}

