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

use SpiceCRM\includes\DataStreams\StreamFactory;
use SpiceCRM\includes\SugarObjects\SpiceConfig;
use SpiceCRM\includes\Soap\SpiceSoap;
use SpiceCRM\includes\database\DBManagerFactory;
use SpiceCRM\includes\SugarObjects\SpiceModules;

/**
 * generate wssdl with <url>/apoi/soap?wsdl?style=rpc|document&use=encoded|literal
 */

// ensure error reporting and display_errors is set properly
error_reporting(E_ERROR);
ini_set('display_errors', 0);

$GLOBALS['soapstart'] = microtime(true);

chdir('../..');
require_once 'vendor/autoload.php';

if(!SpiceConfig::getInstance()->configExists()){
    throw new \SpiceCRM\includes\ErrorHandlers\SystemNotInstalledException();
}

DBManagerFactory::setDBConfig();

SpiceConfig::getInstance()->reloadConfig();

StreamFactory::initialize();

// load the modules first
SpiceModules::getInstance()->loadModules();

// load the vardefs
//SpiceDictionaryHandler::getInstance()->loadCachedVardefs();

if (!empty(SpiceConfig::getInstance()->config['session_dir'])) {
    session_save_path(SpiceConfig::getInstance()->config['session_dir']);
}

// start the
$location = '/soap';

$url = SpiceConfig::getInstance()->config['site_url'].$location;
$service = new SpiceSoap($url);
$service->registerClass('\SpiceCRM\includes\Soap\SpiceSoapRegistry');
$service->register();
$service->registerImplClass('\SpiceCRM\includes\Soap\SpiceSoapServiceImpl');

// set the service object in the global scope so that any error, if happens, can be set on this object
global $service_object;
$service_object = $service;

$service->serve();
