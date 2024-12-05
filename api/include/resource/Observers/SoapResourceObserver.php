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
namespace SpiceCRM\includes\resource\Observers;

use SpiceCRM\includes\utils\SpiceUtils;

class SoapResourceObserver extends ResourceObserver
{
    private $soapServer;

    function __construct($module) {
       parent::__construct($module);
    }


    /**
     * set_soap_server
     * This method accepts an instance of the nusoap soap server so that a proper
     * response can be returned when the notify method is triggered.
     * @param $server The instance of the nusoap soap server
     */
    function set_soap_server(& $server) {
       $this->soapServer = $server;
    }


    /**
     * notify
     * Soap implementation to notify the soap clients of a resource management error
     * @param msg String message to possibly display
     */
    public function notify($msg = '') {

        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
        header('Content-Type: text/xml; charset="ISO-8859-1"');
        $error = new SoapError();
        $error->set_error('resource_management_error');
        //Override the description
        $error->description = $msg;
        $this->soapServer->methodreturn = ['result'=>$msg, 'error'=>$error->get_soap_array()];
        $this->soapServer->serialize_return();
        $this->soapServer->send_response();
        SpiceUtils::spiceCleanup(true);

    }
	
}
