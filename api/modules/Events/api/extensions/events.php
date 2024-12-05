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
use SpiceCRM\includes\SugarObjects\SpiceConfig;
use SpiceCRM\modules\Events\api\controllers\EventsController;

/**
 * get a Rest Manager Instance
 */
$RESTManager = RESTManager::getInstance();

/**
 * register the Extension
 */
$RESTManager->registerExtension('events', '1.0');

$routes = [
    [
        'method'      => 'post',
        'route'       => '/module/Events/{id}/registrations',
        'class'       => EventsController::class,
        'function'    => 'createEventRegistrations',
        'description' => 'Will create event registration to corresponding event for each member in selected target lists',
        'options'     => ['noAuth' => false, 'adminOnly' => false, 'validate' => true],
        'parameters'  => [
            'id'    => [
                'in'          => 'path',
                'description' => 'Event Registrations',
                'type'        => ValidationMiddleware::TYPE_GUID,
            ],
            'targetListIds'    => [
                'in'          => 'body',
                'description' => 'A list of prospectList Ids',
                'type'        => ValidationMiddleware::TYPE_ARRAY,
            ],
            'registrationData'    => [
                'in'          => 'body',
                'description' => 'Additional field values for the Event Registrations',
                'type'        => ValidationMiddleware::TYPE_ARRAY,
            ],
            'eventId'    => [
                'in'          => 'body',
                'description' => 'Event Id',
                'type'        => ValidationMiddleware::TYPE_GUID,
            ],
        ],
        [
            'method'      => 'get',
            'route'       => '/module/Events/{id}/bookings',
            'class'       => EventsController::class,
            'function'    => 'getCapacitiesWithSlots',
            'description' => 'get all EventCapacityTypes with the related EventCapacities and all its slots',
            'options'     => ['noAuth' => false, 'adminOnly' => false, 'validate' => false],
            'parameters'  => [],
        ],
        [
            'method'      => 'get',
            'route'       => '/module/Events/{id}/bookingtable',
            'class'       => EventsController::class,
            'function'    => 'getBookingTable_forEvent',
            'description' => 'get all EventCapacityTypes with the related EventCapacities and all its slots',
            'options'     => ['noAuth' => false, 'adminOnly' => false, 'validate' => false],
            'parameters'  => [],
        ],
        [
            'method'      => 'get',
            'route'       => '/module/EventCapacities/{id}/bookingtable',
            'class'       => EventsController::class,
            'function'    => 'getBookingTable_forCapacity',
            'description' => 'get EventCapacity and all its slots',
            'options'     => ['noAuth' => false, 'adminOnly' => false, 'validate' => false],
            'parameters'  => [],
        ],
        [
            'method'      => 'post',
            'route'       => '/module/EventBookings/{id}/booking',
            'class'       => EventsController::class,
            'function'    => 'saveEventBookingWithoutCapacity',
            'description' => 'Save EventBookings; check if still available; create consumer',
            'options'     => ['noAuth' => false, 'adminOnly' => false, 'validate' => false],
            'parameters'  => [
                'id'   => [
                    'in'          => 'path',
                    'type'        => ValidationMiddleware::TYPE_GUID,
                    'required'    => true,
                    'description' => 'The id of the booking',
                ],
                // bean in body
                ValidationMiddleware::ANONYMOUS_ARRAY => [
                    'in' => 'body',
                    'type' => ValidationMiddleware::TYPE_COMPLEX,
                    'description' => 'array with bean data',
                    'example' => '',
                    'required' => true
                ],
            ],
        ],
    ],
];

$RESTManager->registerRoutes($routes);