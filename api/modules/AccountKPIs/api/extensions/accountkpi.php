<?php
/***** SPICE-HEADER-SPACEHOLDER *****/

use SpiceCRM\includes\RESTManager;
use SpiceCRM\modules\AccountKPIs\api\controllers\AccountKpiController;
use SpiceCRM\includes\Middleware\ValidationMiddleware;

/**
 * get a Rest Manager Instance
 */
$RESTManager = RESTManager::getInstance();

/**
 * register the Extension
 */
$RESTManager->registerExtension('accountkpis', '1.0');

$routes = [
    [
        'method'      => 'get',
        'route'       => '/module/AccountKPIs/{id}/getsummary',
        'oldroute'    => '/module/AccountKPIs/{accountid}/getsummary',
        'class'       => AccountKpiController::class,
        'function'    => 'getAccountKpi',
        'description' => 'get sales account details',
        'options'     => ['noAuth' => false, 'adminOnly' => false, 'validate' => true],
        'parameters'  => [
            'id'      => [
                'in'          => 'path',
                'description' => 'Years data',
                'type'        => ValidationMiddleware::TYPE_GUID,
                'required'    => true
                ],
            'yearto'           => [
                'in' => 'query',
                'description' => 'Year to data',
                'type'        => ValidationMiddleware::TYPE_DATE,
                'required'    => false,
            ],
            'yearfrom'         => [
                'in' => 'query',
                'description' => 'Year from data',
                'type'        => ValidationMiddleware::TYPE_DATE,
                'required'    => false,
                'validationOptions' => [
                    ValidationMiddleware::VOPT_MAX_SIZE => 8,
                ],
            ],
        ],
    ],
];
$RESTManager->registerRoutes($routes);