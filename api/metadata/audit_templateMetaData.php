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
/* this table should never get created, it should only be used as a template for the acutal audit tables
 * for each moudule.
 */
# migrated
/*SpiceDictionaryHandler::getInstance()->dictionary['audit'] =
    ['table' => 'audit_template',
        'fields' => [
            'id'=> ['name' =>'id', 'type' =>'id', 'len'=>'36','required'=>true],
            'parent_id'=> ['name' =>'parent_id', 'type' =>'id', 'len'=>'36','required'=>true],
            'transaction_id'=> ['name' =>'transaction_id', 'type' =>'varchar', 'len'=>'36','required'=>false],
            'date_created'=> ['name' =>'date_created','type' => 'datetime'],
            'created_by'=> ['name' =>'created_by','type' => 'varchar','len' => 36],
            'field_name'=> ['name' =>'field_name','type' => 'varchar','len' => 100],
            'data_type'=> ['name' =>'data_type','type' => 'varchar','len' => 100],
            'before_value_string'=> ['name' =>'before_value_string','type' => 'varchar'],
            'after_value_string'=> ['name' =>'after_value_string','type' => 'varchar'],
            'before_value_text'=> ['name' =>'before_value_text','type' => 'text'],
            'after_value_text'=> ['name' =>'after_value_text','type' => 'text'],
        ],
        'indices' => [
            //name will be re-constructed adding idx_ and table name as the prefix like 'idx_accounts_'
            ['name' => 'pk', 'type' => 'primary', 'fields' => ['id']],
            ['name' => 'parent_id', 'type' => 'index', 'fields' => ['parent_id']],
            ['name' => 'field_name', 'type' => 'index', 'fields' => ['field_name']],
        ]
    ];*/
