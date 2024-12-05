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

use SpiceCRM\includes\RESTManager;
use SpiceCRM\includes\Middleware\ValidationMiddleware;
use SpiceCRM\includes\SpiceUI\api\controllers\SystemUIController;
use SpiceCRM\includes\SpiceUI\api\controllers\SpiceUISysTextIdsController;
/**
 * get a Rest Manager Instance
 */
$RESTManager = RESTManager::getInstance();

RESTManager::getInstance()->registerExtension('spiceuisystextids', '1.0');

$routes = [
    [
        'method' => 'post',
        'route' => '/system/spiceuisystextids/core/addsystext',
        'class' => SpiceUISysTextIdsController::class,
        'function' => 'addSysText',
        'description' => 'creates a new entries in the systextid & systextids_modules tables',
        'options' => ['noAuth' => false, 'adminOnly' => true, 'validate' => true],
        'parameters' => [
            'textId' => [
                'in' => 'body',
                'description' => 'the text_id of the systext',
                'type' => ValidationMiddleware::TYPE_STRING,
                'example' => 'pg-000-001',
            ],
            'name' => [
                'in' => 'body',
                'description' => 'the name of the entry',
                'type' => ValidationMiddleware::TYPE_STRING,
                'example' => 'Product Group Text One',
            ],
            'label' => [
                'in' => 'body',
                'description' => 'the label',
                'type' => ValidationMiddleware::TYPE_STRING,
                'example' => 'LBL_ENTRY',
            ],
            'module' => [
                'in' => 'body',
                'description' => 'module',
                'type' => ValidationMiddleware::TYPE_STRING,
                'example' => 'Module of systtext',
            ]
        ]
    ],
];

$RESTManager->registerRoutes($routes);
