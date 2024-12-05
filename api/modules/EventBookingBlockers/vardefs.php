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

SpiceDictionaryHandler::getInstance()->dictionary['EventBookingBlocker'] = [
    'table' => 'eventbookingblockers',
    'comment' => 'EventBookingBlockers Module',
    'audited' => false,
    'duplicate_merge' => false,
    'unified_search' => false,
    'fields' => [
        'booking_blocked_until' => [
            'name' => 'booking_blocked_until',
            'vname' => 'LBL_BOOKING_BLOCKED_UNTIL',
            'type' => 'datetime',
            'required' => true,
            'comment' => 'common calendar booking blocked until'
        ],
        'booking_blocked_from' => [
            'name' => 'booking_blocked_from',
            'vname' => 'LBL_BOOKING_BLOCKED_FROM',
            'type' => 'datetime',
            'required' => false,
            'comment' => 'common calendar booking blocked from'
        ],
        'participant_id' => [
            'name' => 'participant_id',
            'vname' => 'LBL_PARTICIPANT_ID',
            'type' => 'id',
            'reportable' => false,
            'comment' => 'ID of parent record'
        ],
        'participant_type' => [
            'name'     => 'participant_type',
            'vname'    => 'LBL_PARTICIPANT_TYPE',
            'type'     => 'parent_type',
            'dbtype'   => 'varchar',
            'len'      => 50,
            'comment'  => 'The module name of participant record',
        ],
        'participant_name' => [
            'name'        => 'participant_name',
            'vname'       => 'LBL_RELATED_TO',
            'type'        => 'parent',
            'type_name'   => 'participant_type',
            'id_name'     => 'participant_id',
            'source'      => 'non-db',
            'required' => true,
            'comment'  => 'The summary of the participant record',
        ],
        'consumer' => [
            'name' => 'consumer',
            'vname' => 'LBL_CONSUMER',
            'type' => 'link',
            'relationship' => 'consumer_eventbookingblockers',
            'source' => 'non-db',
        ],
        /*
        'eventcapacitytype_id' => [
            'name' => 'eventcapacitytype_id',
            'vname' => 'LBL_EVENTCAPACITYTYPE_ID',
            'type' => 'varchar',
            'len' => 36,
            'reportable' => false,
        ],
        'eventcapacitytype_name' => [
            'name' => 'eventcapacitytype_name',
            'rname' => 'name',
            'id_name' => 'eventcapacitytype_id',
            'vname' => 'LBL_EVENTCAPACITYTYPE',
            'type' => 'relate',
            'table' => 'eventcapacitytypes',
            'isnull' => 'true',
            'module' => 'EventCapacityTypes',
            'dbType' => 'varchar',
            'link' => 'eventcapacitytype',
            'len' => '255',
            'source' => 'non-db'
        ],
        'eventcapacitytype' => [
            'name' => 'eventcapacitytype',
            'vname' => 'LBL_EVENTCAPACITYTYPE',
            'type' => 'link',
            'relationship' => 'eventcapacitytype_eventbookingblockers',
            'source' => 'non-db'
        ],
        */
        'subtype' => [
                'name' => 'subtype',
                'vname' => 'LBL_SUBTYPE',
                'type' => 'enum',
                'len' => 20,
                'options' => 'eventCapacity_subtype_dom'
        ]
    ],
    'relationships' => [
        'consumer_eventbookingblockers' => [
            'lhs_module' => 'Consumers',
            'lhs_table' => 'consumers',
            'lhs_key' => 'id',
            'rhs_module' => 'EventBookingBlockers',
            'rhs_table' => 'eventbookingblockers',
            'rhs_key' => 'participant_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column'=>'participant_type',
            'relationship_role_column_value' => 'Consumers'
        ],
        /*
        'eventcapacitytype_eventbookingblockers' => [
            'lhs_module' => 'EventCapacityTypes',
            'lhs_table' => 'eventcapacitytypes',
            'lhs_key' => 'id',
            'rhs_module' => 'EventBookingBlockers',
            'rhs_table' => 'eventbookingblockers',
            'rhs_key' => 'eventcapacitytype_id',
            'relationship_type' => 'one-to-many'
        ]
        */
    ],
    'indices' => [
        ['name' => 'idx__event_b_blockers__participant', 'type' => 'index', 'fields' => ['participant_id', 'participant_type', 'deleted']],
        ['name' => 'idx__event_b_blockers__subtype', 'type' => 'index', 'fields' => ['subtype', 'deleted']],
        ['name' => 'idx__event_b_blockers__blocked_until', 'type' => 'index', 'fields' => ['booking_blocked_until', 'deleted']],
    ]
];

VardefManager::createVardef('EventBookingBlockers', 'EventBookingBlocker', ['default', 'assignable']);


SpiceDictionaryHandler::getInstance()->dictionary['EventBookingBlocker']['fields']['name'] = [
    'name' => 'name',
    'type' => 'varchar',
    'len' => 50,
    'required' => false,
    'vname' => "LBL_EVENTBOOKINGBLOCKER_NAME"
];