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

SpiceDictionaryHandler::getInstance()->dictionary['EventCapacityType'] = [
    'table' => 'eventcapacitytypes',
    'comment' => 'EventCapacityTypes Module',
    'audited' => false,
    'duplicate_merge' => false,
    'unified_search' => false,

    'fields' => [
        'label' => [
            'name'  => 'label',
            'vname' => 'LBL_LABEL',
            'type'  => 'varchar',
            'len'   => '255',
            'required' => false
        ],
        'duration' => [
            'name' => 'duration',
            'type' => 'int',
            'vname' => 'LBL_DURATION_MINUTES'
        ],
        'check_class' => [
            'name'			=> 'check_class',
            'type'			=> 'varchar',
            'length'		=> 255,
            'vname'         => 'LBL_CLASS',
            'required'		=> true,
        ],
        'check_method' => [
            'name'			=> 'check_method',
            'type'			=> 'varchar',
            'length'		=> 255,
            'vname'         => 'LBL_METHOD',
            'required'		=> true,
        ],
        'eventcapacities' => [
            'name' => 'eventcapacities',
            'type' => 'link',
            'relationship' => 'eventcapacitytypes_eventcapacities',
            'module' => 'EventCapacities',
            'bean_name' => 'EventCapacity',
            'source' => 'non-db',
            'vname' => 'LBL_EVENTCAPACITY',
        ],
    ],
    'relationships' => [],
    'indices' => []
];

VardefManager::createVardef('EventCapacityTypes', 'EventCapacityType', ['default', 'assignable']);
