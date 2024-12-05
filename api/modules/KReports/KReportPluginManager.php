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

namespace SpiceCRM\modules\KReports;

use SpiceCRM\data\BeanFactory;
use SpiceCRM\modules\SpiceACL\SpiceACL;

class KReportPluginManager
{

    // constructor
    var $plugins = [];

    public function __construct()
    {
        if (file_exists('modules/KReports/plugins.dictionary')) {
            $plugins = [];
            include('modules/KReports/plugins.dictionary');

            foreach ($plugins as $thisPlugin => $thisPluginData) {
                $pluginDirectory = ($thisPluginData['extension'] ? "extensions/" : "") . "modules/KReports/Plugins/";

                // write to the Object varaible so we have all plugins by ID
                $this->plugins[$thisPlugin] = $thisPluginData;

                // add specific plugins metadata to the array
                switch ($thisPluginData['type']) {
                    case 'presentation':
                        $this->plugins[$thisPlugin]['plugindirectory'] = "{$pluginDirectory}Presentation/{$thisPluginData['directory']}";
                        if (file_exists("{$pluginDirectory}Presentation/{$thisPluginData['directory']}/pluginmetadata.php")) {
                            $pluginmetadata = [];
                            include("{$pluginDirectory}Presentation/{$thisPluginData['directory']}/pluginmetadata.php");
                            $this->plugins[$thisPlugin]['metadata'] = $pluginmetadata;
                        }
                        break;
                    case 'visualization':
                        $this->plugins[$thisPlugin]['plugindirectory'] = "{$pluginDirectory}Visualization/{$thisPluginData['directory']}";
                        if (file_exists("{$pluginDirectory}Visualization/{$thisPluginData['directory']}/pluginmetadata.php")) {
                            $pluginmetadata = [];
                            include("{$pluginDirectory}Visualization/{$thisPluginData['directory']}/pluginmetadata.php");
                            $this->plugins[$thisPlugin]['metadata'] = $pluginmetadata;
                        }
                        break;
                    case 'integration':
                        $this->plugins[$thisPlugin]['plugindirectory'] = "{$pluginDirectory}Integration/{$thisPluginData['directory']}";
                        if (file_exists("{$pluginDirectory}Integration/{$thisPluginData['directory']}/pluginmetadata.php")) {
                            $pluginmetadata = [];
                            include("{$pluginDirectory}Integration/{$thisPluginData['directory']}/pluginmetadata.php");
                            $this->plugins[$thisPlugin]['metadata'] = $pluginmetadata;
                        }
                        break;
                }
            }
        }


        // read the plugin metadata
        if (file_exists('custom/modules/KReports/plugins.dictionary')) {
            $plugins = [];
            include('custom/modules/KReports/plugins.dictionary');

            foreach ($plugins as $thisPlugin => $thisPluginData) {

                // write to the Object varaible so we have all plugins by ID
                $this->plugins[$thisPlugin] = $thisPluginData;

                // add specific plugins metadata to the array
                switch ($thisPluginData['type']) {
                    case 'presentation':
                        $this->plugins[$thisPlugin]['plugindirectory'] = 'custom/modules/KReports/Plugins/Presentation/' . $thisPluginData['directory'];
                        if (file_exists('custom/modules/KReports/Plugins/Presentation/' . $thisPluginData['directory'] . '/pluginmetadata.php')) {
                            $pluginmetadata = [];
                            include('custom/modules/KReports/Plugins/Presentation/' . $thisPluginData['directory'] . '/pluginmetadata.php');
                            $this->plugins[$thisPlugin]['metadata'] = $pluginmetadata;
                        }
                        break;
                    case 'visualization':
                        $this->plugins[$thisPlugin]['plugindirectory'] = 'custom/modules/KReports/Plugins/Visualization/' . $thisPluginData['directory'];
                        if (file_exists('custom/modules/KReports/Plugins/Visualization/' . $thisPluginData['directory'] . '/pluginmetadata.php')) {
                            $pluginmetadata = [];
                            include('custom/modules/KReports/Plugins/Visualization/' . $thisPluginData['directory'] . '/pluginmetadata.php');
                            $this->plugins[$thisPlugin]['metadata'] = $pluginmetadata;
                        }
                        break;
                    case 'integration':
                        $this->plugins[$thisPlugin]['plugindirectory'] = 'custom/modules/KReports/Plugins/Integration/' . $thisPluginData['directory'];
                        if (file_exists('custom/modules/KReports/Plugins/Integration/' . $thisPluginData['directory'] . '/pluginmetadata.php')) {
                            $pluginmetadata = [];
                            include('custom/modules/KReports/Plugins/Integration/' . $thisPluginData['directory'] . '/pluginmetadata.php');
                            $this->plugins[$thisPlugin]['metadata'] = $pluginmetadata;
                        }
                        break;
                }
            }
        }
    }

    public function getPlugins($report = '')
    {

        if ($report) {
            $thisReport = BeanFactory::getBean('KReports', $report);
            $integrationParams = json_decode(html_entity_decode($thisReport->integration_params, ENT_QUOTES, 'UTF-8'));
        }

        foreach ($this->plugins as $pluginId => $pluginData) {
            /*
            if (isset($pluginData['metadata']['includes']['edit']))
                $jsIncludes .= "<script type='text/javascript' src='" . $pluginData['plugindirectory'] . '/' . $pluginData['metadata']['includes']['edit'] . "'></script>";
            */

            switch ($pluginData['type']) {
                case 'presentation':
                    $presentationPlugins[$pluginId] = [
                        'id' => $pluginId,
                        'plugindirectory' => $pluginData['plugindirectory'],
                        'displayname' => $pluginData['metadata']['displayname'],
                        'metadata' => $pluginData['metadata']
                    ];
                    break;
                case 'visualization':
                    $visualizationPlugins[$pluginId] = [
                        'id' => $pluginId,
                        'plugindirectory' => $pluginData['plugindirectory'],
                        'displayname' => $pluginData['metadata']['displayname'],
                        'metadata' => $pluginData['metadata']
                    ];
                    break;
                case 'integration':
                    if ($report) {
                        if ($integrationParams->activePlugins->$pluginId == 1) {
                            // for export plugins check export right
                            if ($pluginData['category'] === 'export' && !SpiceACL::getInstance()->checkAccess('KReports', 'export', false))
                                continue;

                            // plugin specific checks
                            if (isset($pluginData['metadata']['integration']['include'])) {
                                require_once($pluginData['plugindirectory'] . '/' . $pluginData['metadata']['integration']['include']);
                                $pluginClass = $pluginData['metadata']['integration']['class'];
                                $thisPlugin = new $pluginClass();
                                if (method_exists($thisPlugin, 'checkAccess') && !$thisPlugin->checkAccess($thisReport))
                                    continue;
                            }

                            $integrationPlugins[$pluginId] = [
                                'id' => $pluginId,
                                'plugindirectory' => $pluginData['plugindirectory'],
                                'displayname' => $pluginData['metadata']['displayname'],
                                'metadata' => $pluginData['metadata']
                            ];
                        }
                    } else {
                        $integrationPlugins[$pluginId] = [
                            'id' => $pluginId,
                            'plugindirectory' => $pluginData['plugindirectory'],
                            'displayname' => $pluginData['metadata']['displayname'],
                            'metadata' => $pluginData['metadata']
                        ];
                    }
                    break;
            }
        }

        return [
            'presentation' => $presentationPlugins,
            'visualization' => $visualizationPlugins,
            'integration' => $integrationPlugins
        ];
    }

    public function getEditViewPlugins($thisView)
    {
        $jsIncludes = '';
        $presentationPlugins = [];
        $visualizationPlugins = [];
        $integrationPlugins = [];
        foreach ($this->plugins as $pluginId => $pluginData) {
            if (isset($pluginData['metadata']['includes']['edit']))
                $jsIncludes .= "<script type='text/javascript' src='" . $pluginData['plugindirectory'] . '/' . $pluginData['metadata']['includes']['edit'] . "'></script>";

            switch ($pluginData['type']) {
                case 'presentation':
                    $presentationPlugins[$pluginId] = [
                        'id' => $pluginId,
                        'displayname' => $pluginData['metadata']['displayname'],
                        'panel' => $pluginData['metadata']['pluginpanel']
                    ];
                    break;
                case 'visualization':
                    $visualizationPlugins[$pluginId] = [
                        'id' => $pluginId,
                        'displayname' => $pluginData['metadata']['displayname'],
                        'panel' => $pluginData['metadata']['pluginpanel']
                    ];
                    break;
                case 'integration':
                    $integrationPlugins[$pluginId] = [
                        'id' => $pluginId,
                        'panel' => $pluginData['metadata']['includes']['editPanel'],
                        'displayname' => $pluginData['metadata']['displayname']
                    ];
                    break;
            }
        }
        $pluginData = '<script type="text/javascript">';

        if (count($presentationPlugins) > 0)
            $pluginData .= 'var kreporterPresentationPlugins = \'' . json_encode($presentationPlugins) . '\';';

        if (count($visualizationPlugins) > 0)
            $pluginData .= 'var kreporterVisualizationPlugins = \'' . json_encode($visualizationPlugins) . '\';';

        if (count($integrationPlugins) > 0)
            $pluginData .= 'var kreporterIntegrationPlugins = \'' . json_encode($integrationPlugins) . '\';';

        $pluginData .= "</script>";

        $thisView->ss->assign('pluginJS', $jsIncludes);
        $thisView->ss->assign('pluginData', $pluginData);
    }

    public function getPresentationObject($pluginId)
    {
        if (isset($this->plugins[$pluginId])) {
            if (file_exists('custom/modules/KReports/Plugins/Presentation/' . $this->plugins[$pluginId]['directory'] . '/' . $this->plugins[$pluginId]['metadata']['phpinclude'])) {
                require_once('custom/modules/KReports/Plugins/Presentation/' . $this->plugins[$pluginId]['directory'] . '/' . $this->plugins[$pluginId]['metadata']['phpinclude']);

                // eval($this->plugins[$pluginId]['id'] . 'detailviewdisplay($view);');
                $className = 'kreportpresentation' . $this->plugins[$pluginId]['id'];
                return new $className();

                return true;
            }
            if (file_exists('extensions/modules/KReports/Plugins/Presentation/' . $this->plugins[$pluginId]['directory'] . '/' . $this->plugins[$pluginId]['metadata']['phpinclude'])) {
                require_once('extensions/modules/KReports/Plugins/Presentation/' . $this->plugins[$pluginId]['directory'] . '/' . $this->plugins[$pluginId]['metadata']['phpinclude']);

                //eval($this->plugins[$pluginId]['id'] . 'detailviewdisplay($view);');
                $className = 'kreportpresentation' . $this->plugins[$pluginId]['id'];
                return new $className();
            }
            if (file_exists('modules/KReports/Plugins/Presentation/' . $this->plugins[$pluginId]['directory'] . '/' . $this->plugins[$pluginId]['metadata']['phpinclude'])) {
                require_once('modules/KReports/Plugins/Presentation/' . $this->plugins[$pluginId]['directory'] . '/' . $this->plugins[$pluginId]['metadata']['phpinclude']);

                //eval($this->plugins[$pluginId]['id'] . 'detailviewdisplay($view);');
                $className = 'kreportpresentation' . $this->plugins[$pluginId]['id'];
                return new $className();
            }
        }

        return false;
    }

    public function getVisualizationObject($plugin)
    {
        if (isset($this->plugins[$plugin])) {
            $file = $this->plugins[$plugin]['plugindirectory'] . '/' . $this->plugins[$plugin]['metadata']['visualization']['include'];
            require_once($this->plugins[$plugin]['plugindirectory'] . '/' . $this->plugins[$plugin]['metadata']['visualization']['include']);
            $visualizationClass = $this->plugins[$plugin]['metadata']['visualization']['class'];
            return new $visualizationClass();
        } else
            return false;
    }

    public function getIntegrationPlugins($thisReport)
    {

        return '';

    }

    public function processPluginAction($pluginId, $pluginAction, $getParams)
    {
        // the namespace way
        $namespaceclass = "\\SpiceCRM\\".preg_replace("|\/|", "\\", $this->plugins[$pluginId]['plugindirectory']);
        $namespaceclass.= "\\controller\\plugin".$this->plugins[$pluginId]['id']."controller";

        if(class_exists($namespaceclass)){
            $pluginController = new $namespaceclass();
        } else{
            // the old way
            $controllerclass = 'plugin' . $this->plugins[$pluginId]['id'] . 'controller';
            if(!class_exists($controllerclass)){
                require_once($this->plugins[$pluginId]['plugindirectory'] . '/controller/plugin' . $this->plugins[$pluginId]['id'] . 'controller.php');
            }

            $pluginController = new $controllerclass();
        }

        return $pluginController->$pluginAction($getParams);
    }

}

