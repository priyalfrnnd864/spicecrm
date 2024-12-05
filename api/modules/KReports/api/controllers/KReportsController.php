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

namespace SpiceCRM\modules\KReports\api\controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use SpiceCRM\data\BeanFactory;
use SpiceCRM\includes\database\DBManagerFactory;
use SpiceCRM\includes\ErrorHandlers\ForbiddenException;
use SpiceCRM\includes\SpiceSlim\SpiceResponse as Response;
use SpiceCRM\includes\SugarObjects\SpiceConfig;
use SpiceCRM\modules\SpiceACL\SpiceACL;


class KReportsController
{
    /**
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     * @throws ForbiddenException
     */
    public function saveLayout(Request $req, Response $res, array $args): Response{
        $postBody = $req->getParsedBody();
        $restHandler = new \KReporterRESTHandler();
        return $res->withJson($restHandler->saveStandardLayout($args['id'], $postBody['layout']));
    }

    /**
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     */
    public function getSnapshots(Request $req, Response $res, array $args): Response{
        $thisReport = BeanFactory::getBean('KReports');
        $thisReport->retrieve($args['id']);
        $requestParams = $req->getQueryParams();
        return $res->withJson($thisReport->getSnapshots($requestParams['withoutActual']));
    }

    /**
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     */
    public function deleteSnapshot(Request $req, Response $res, array $args): Response{
        $thisReport = BeanFactory::getBean('KReports');
        $thisReport->retrieve($args['id']);
        $response = $thisReport->deleteSnapshot($args['snapshotid']);
        return $res->withJson($response);
    }


    /**
     * @param $req
     * @param $res
     * @param $args
     * @return array
     * @throws ForbiddenException
     */
    public function getPublishedKReports(Request $req, Response $res, array $args): Response{
        if (!SpiceACL::getInstance()->checkAccess('KReports', 'list', true))
            throw (new ForbiddenException("Forbidden to list in module KReports."))->setErrorCode('noModuleList');
        $db = DBManagerFactory::getInstance();
        $list = [];
        $type = $db->quote($args['type']);
        $params = $req->getQueryParams();
        $searchKey = $params['searchKey'] ? $db->quote($params['searchKey']) : '';
        $offset = $params['offset'] ? $db->quote($params['offset']) : 0;
        $limit = $params['limit'] ? $db->quote($params['limit']) : 40;
        $where = "deleted=0 AND integration_params LIKE '%\"$type\":\"on\"%' AND (integration_params LIKE '%\"kpublishing\":1%' OR integration_params LIKE '%\"kpublishing\":\"1\"%')";
        if ($searchKey != '') {
            $where .= " AND name LIKE '%$searchKey%'";
        }
        $query = "SELECT id, name, description, report_module, integration_params FROM kreports WHERE $where";
        $query = $db->limitQuery($query, $offset, $limit);
        while ($row = $db->fetchByAssoc($query)) $list[] = $row;
        return $res->withJson($list);
    }

    /**
     * @param Request $req
     * @param Response $res
     * @param array $args
     * @return Response
     */
    public function getDLists(Request $req, Response $res, array $args): Response{
        $restHandler = new \KReporterRESTHandler();
        return $res->withJson($restHandler->getDLists());
    }

    /**
     * load report categories for the ui loadtasks
     * @return array
     */
    public function getReportCategories() {
        $db = DBManagerFactory::getInstance();
        $list = [];
        $spice_config = SpiceConfig::getInstance()->config;
        if($spice_config['system']['no_table_exists_check'] === true || $db->tableExists('kreportcategories')) {
            $query = $db->query("SELECT * FROM kreportcategories WHERE deleted <> 1");
            while ($row = $db->fetchByAssoc($query)) $list[] = $row;
        }
        return $list;
    }
}
