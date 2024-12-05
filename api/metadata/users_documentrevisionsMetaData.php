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

SpiceDictionaryHandler::getInstance()->dictionary["users_documentrevisions"] = [
    'true_relationship_type' => 'many-to-many',
    'relationships' =>
        [
            'users_documentrevisions' =>
                [
                    'lhs_module' => 'Users',
                    'lhs_table' => 'users',
                    'lhs_key' => 'id',
                    'rhs_module' => 'DocumentRevisions',
                    'rhs_table' => 'document_revisions',
                    'rhs_key' => 'id',
                    'relationship_type' => 'many-to-many',
                    'join_table' => 'users_documentrevisions',
                    'join_key_lhs' => 'user_id',
                    'join_key_rhs' => 'document_revision_id',
                ],
        ],
    'table' => 'users_documentrevisions',
    'contenttype'   => 'relationdata',
    'fields' =>
        [
            0 =>
                [
                    'name' => 'id',
                    'type' => 'varchar',
                    'len' => 36,
                ],
            1 =>
                [
                    'name' => 'date_entered',
                    'type' => 'datetime',
                ],
            2 =>
                [
                    'name' => 'deleted',
                    'type' => 'bool',
                    'len' => '1',
                    'default' => '0',
                    'required' => true,
                ],
            3 =>
                [
                    'name' => 'user_id',
                    'type' => 'varchar',
                    'len' => 36,
                ],
            4 =>
                [
                    'name' => 'document_revision_id',
                    'type' => 'varchar',
                    'len' => 36,
                ],
            5 =>
            [
                'name' => 'acceptance_status',
                'type' => 'bool',
            ],
            6 =>
                [
                    'name' => 'date_update_accepted',
                    'type' => 'datetime',
                ],
        ],
    'indices' =>
        [
            0 =>
                [
                    'name' => 'users_documentrevisionspk',
                    'type' => 'primary',
                    'fields' =>
                        [
                            0 => 'id',
                        ],
                ],
            1 =>
                [
                    'name' => 'idx_user_docrev_user_id',
                    'type' => 'alternate_key',
                    'fields' =>
                        [
                            0 => 'user_id',
                            1 => 'document_revision_id',
                        ],
                ],
            2 =>
                [
                    'name' => 'idx_user_docrev_docrev_id',
                    'type' => 'alternate_key',
                    'fields' =>
                        [
                            0 => 'document_revision_id',
                            1 => 'user_id',
                        ],
                ],
        ],
];