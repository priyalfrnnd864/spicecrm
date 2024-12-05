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
use SpiceCRM\includes\SugarObjects\VardefManager;

SpiceDictionaryHandler::getInstance()->dictionary['EventBooking'] = [
    'table' => 'eventbookings',
    'comment' => 'EventBookings Module',
    'audited' => false,
    'duplicate_merge' => false,
    'unified_search' => false,
    'fields' => [
        'date_start' => [
            'name' => 'date_start',
            'vname' => 'LBL_DATE_START',
            'type' => 'datetime',
            'required' => true,
            'comment' => 'common calendar date start'
        ],
        'event_capacity_id' => [
            'name' => 'event_capacity_id',
            'vname' => 'LBL_EVENT_CAPACITY_ID',
            'type' => 'varchar',
            'len' => 36,
            'reportable' => false,
            'required' => true,
        ],
        'event_capacity_name' => [
            'name' => 'event_capacity_name',
            'rname' => 'name',
            'id_name' => 'event_capacity_id',
            'vname' => 'LBL_CAPACITY',
            'type' => 'relate',
            'table' => 'eventcapacities',
            'isnull' => 'true',
            'module' => 'EventCapacities',
            'dbType' => 'varchar',
            'link' => 'event_capacity',
            'len' => '255',
            'source' => 'non-db',
            'required' => false
        ],
        'event_capacity' => [
            'name' => 'event_capacity',
            'vname' => 'LBL_EVENT_CAPACITY',
            'type' => 'link',
            'relationship' => 'eventcapacities_eventbookings',
            'source' => 'non-db',
        ],
        'parent_id' => [
            'name' => 'parent_id',
            'vname' => 'LBL_PARTICIPANT_ID',
            'type' => 'id',
            'reportable' => false,
            'comment' => 'ID of parent record'
        ],
        'parent_type' => [
            'name'     => 'parent_type',
            'vname'    => 'LBL_PARTICIPANT_TYPE',
            'type'     => 'parent_type',
            'dbtype'   => 'varchar',
            'len'      => 50,
            'comment'  => 'The module name of participant record',
        ],
        'parent_name' => [
            'name'        => 'parent_name',
            'vname'       => 'LBL_RELATED_TO',
            'type'        => 'parent',
            'type_name'   => 'parent_type',
            'id_name'     => 'parent_id',
            'source'      => 'non-db',
            'required' => true,
            'comment'  => 'The summary of the participant record',
        ],
        'participated' => [
            'name' => 'participated',
            'vname' => 'LBL_PARTICIPATED',
            'type' => 'bool'
        ],
        'contact' => [
            'name' => 'contact',
            'vname' => 'LBL_CONTACT',
            'type' => 'link',
            'relationship' => 'contact_eventbookings',
            'source' => 'non-db',
        ],
        'consumer' => [
            'name' => 'consumer',
            'vname' => 'LBL_CONSUMER',
            'type' => 'link',
            'relationship' => 'consumer_eventbookings',
            'source' => 'non-db',
        ],
        'channel' => [
            'name' => 'channel',
            'vname' => 'LBL_CHANNEL',
            'type' => 'enum',
            'len' => 3,
            'options' => 'eventbooking_channel_dom'
        ],
    ],
    'relationships' => [
        'eventcapacities_eventbookings' => [
            'lhs_module' => 'EventCapacities',
            'lhs_table' => 'eventcapacities',
            'lhs_key' => 'id',
            'rhs_module' => 'EventBookings',
            'rhs_table' => 'eventbookings',
            'rhs_key' => 'event_capacity_id',
            'relationship_type' => 'one-to-many'
        ],
        'contact_eventbookings' => [
            'lhs_module' => 'Contacts',
            'lhs_table' => 'contacts',
            'lhs_key' => 'id',
            'rhs_module' => 'EventBookings',
            'rhs_table' => 'eventbookings',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column'=>'parent_type',
            'relationship_role_column_value' => 'Contacts'
        ],
        'consumer_eventbookings' => [
            'lhs_module' => 'Consumers',
            'lhs_table' => 'consumers',
            'lhs_key' => 'id',
            'rhs_module' => 'EventBookings',
            'rhs_table' => 'eventbookings',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column'=>'parent_type',
            'relationship_role_column_value' => 'Consumers'
        ]
    ],
    'indices' => [
        ['name' => 'idx__eventbookings__regparticipant', 'type' => 'index', 'fields' => ['parent_id', 'parent_type', 'deleted','participated']],
        ['name' => 'idx__eventbookings__event_capacity_id', 'type' => 'index', 'fields' => ['event_capacity_id','deleted']],
        ['name' => 'idx__eventbookings__date_start', 'type' => 'index', 'fields' => ['date_start','deleted'] ],
        ['name' => 'idx__eventbookings__participated', 'type' => 'index', 'fields' => ['participated','deleted'] ]
    ]
];

VardefManager::createVardef('EventBookings', 'EventBooking', ['default', 'assignable']);


SpiceDictionaryHandler::getInstance()->dictionary['EventBooking']['fields']['name'] = [
    'name' => 'name',
    'type' => 'varchar',
    'len' => 50,
    'required' => false,
    'vname' => "LBL_BOOKINGNUMBER"
];