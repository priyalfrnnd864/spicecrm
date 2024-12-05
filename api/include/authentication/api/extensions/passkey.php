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

use SpiceCRM\includes\authentication\api\PasskeyController;
use SpiceCRM\includes\Middleware\ValidationMiddleware;
use SpiceCRM\includes\RESTManager;

/**
 * get a Rest Manager Instance
 */
$RESTManager = RESTManager::getInstance();

$routes = [
    [
        'method' => 'post',
        'route' => '/authentication/passkey/getArgs',
        'class' => PasskeyController::class,
        'function' => 'getArgs',
        'description' => 'passkey get args',
        'options' => ['noAuth' => true, 'adminOnly' => false, 'validate' => false],
        'parameters' => [
            'state' => [
                'in' => 'query',
                'description' => 'This is the value passed on from the login page. It should contain the session ID',
                'type' => ValidationMiddleware::TYPE_STRING,
                'required' => false,
            ]
        ]
    ],
    [
        'method' => 'post',
        'route' => '/authentication/passkey/createArgs',
        'class' => PasskeyController::class,
        'function' => 'createArgs',
        'description' => 'passkey create args',
        'options' => ['adminOnly' => false, 'validate' => false],
        'parameters' => [
            'state' => [
                'in' => 'query',
                'description' => 'This is the value passed on from the login page. It should contain the session ID',
                'type' => ValidationMiddleware::TYPE_STRING,
                'required' => false,
            ]
        ]
    ],
    [
        'method' => 'post',
        'route' => '/authentication/passkey/processCreate',
        'class' => PasskeyController::class,
        'function' => 'processCreate',
        'description' => 'passkey process create',
        'options' => ['adminOnly' => false, 'validate' => false],
        'parameters' => [
            'state' => [
                'in' => 'query',
                'description' => 'This is the value passed on from the login page. It should contain the session ID',
                'type' => ValidationMiddleware::TYPE_STRING,
                'required' => false,
            ]
        ]
    ],
    [
        'method' => 'get',
        'route' => '/authentication/passkey/{userId}',
        'class' => PasskeyController::class,
        'function' => 'checkPasskey',
        'description' => 'check passkey for user',
        'options' => ['adminOnly' => false, 'validate' => false],
        'parameters' => [
            'state' => [
                'in' => 'query',
                'description' => 'This is the value passed on from the login page. It should contain the session ID',
                'type' => ValidationMiddleware::TYPE_STRING,
                'required' => false,
            ]
        ]
    ],
    [
        'method' => 'delete',
        'route' => '/authentication/passkey/{userId}',
        'class' => PasskeyController::class,
        'function' => 'removePasskey',
        'description' => 'check passkey for user',
        'options' => ['adminOnly' => false, 'validate' => false],
        'parameters' => [
            'state' => [
                'in' => 'query',
                'description' => 'This is the value passed on from the login page. It should contain the session ID',
                'type' => ValidationMiddleware::TYPE_STRING,
                'required' => false,
            ]
        ]
    ],
];


/**
 * register the Extension
 */
$RESTManager->registerExtension(
    'passkey',
    '1.0',
    [],
    $routes
);