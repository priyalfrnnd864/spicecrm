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

SpiceDictionaryHandler::getInstance()->dictionary['calls_contacts'] = [
    'table' => 'calls_contacts',
    'contenttype'   => 'relationdata',
    'fields' => [
        'id' => ['name' =>'id', 'type' =>'varchar', 'len'=>'36']
      , 'call_id' => ['name' =>'call_id', 'type' =>'varchar', 'len'=>'36',]
      , 'contact_id' => ['name' =>'contact_id', 'type' =>'varchar', 'len'=>'36',]
      , 'required' => ['name' =>'required', 'type' =>'varchar', 'len'=>'1', 'default'=>'1']
      , 'accept_status' => ['name' =>'accept_status', 'type' =>'varchar', 'len'=>'25', 'default'=>'none']
      , 'date_modified' => ['name' => 'date_modified','type' => 'datetime']
      , 'deleted' => ['name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'default'=>'0', 'required'=>false]
    ]
                                  , 'indices' => [
       ['name' =>'calls_contactspk', 'type' =>'primary', 'fields'=> ['id']]
      , ['name' =>'idx_con_call_call', 'type' =>'index', 'fields'=> ['call_id']]
      , ['name' =>'idx_con_call_con', 'type' =>'index', 'fields'=> ['contact_id']]
      , ['name' => 'idx_call_contact', 'type'=>'alternate_key', 'fields'=> ['call_id','contact_id']]
    ]

 	  , 'relationships' => ['calls_contacts' => ['lhs_module'=> 'Calls', 'lhs_table'=> 'calls', 'lhs_key' => 'id',
							  'rhs_module'=> 'Contacts', 'rhs_table'=> 'contacts', 'rhs_key' => 'id',
							  'relationship_type'=>'many-to-many',
							  'join_table'=> 'calls_contacts', 'join_key_lhs'=>'call_id', 'join_key_rhs'=>'contact_id']]

];
