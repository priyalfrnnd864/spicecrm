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

namespace SpiceCRM\modules\Calendar\api\handlers;

use Exception;
use SpiceCRM\includes\database\DBManagerFactory;
use SpiceCRM\includes\SpiceFTSManager\SpiceFTSActivityHandler;
use SpiceCRM\includes\SpiceFTSManager\SpiceFTSUtils;

class CalendarRestHandler
{
    public function getCalendarModules(): array {
        $result = [];
        $modules = SpiceFTSUtils::getCalendarModules();
        foreach ($modules as $module => $data) {
            $dateStartFieldName = null;
            $dateEndFieldName = null;
            foreach ($data['ftsfields'] as $field) {
                if ($field['activitytype'] == 'activitydate')
                    $dateStartFieldName = $field['fieldname'];
                if ($field['activitytype'] == 'activityenddate')
                    $dateEndFieldName = $field['fieldname'];
            }
            if ($dateStartFieldName) {
                $result[] = [
                    'name' => $module,
                    'dateStartFieldName' => $dateStartFieldName,
                    'dateEndFieldName' => $dateEndFieldName
                ];
            }
        }
        return $result;
    }

    /**
     * get user calendar events
     * @param string $userId
     * @param string $calendarId
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function getUserCalendarEvents(string $userId, string $calendarId, array $params): array {
        $db = DBManagerFactory::getInstance();
        $start = $db->quote($params['start']);
        $end = $db->quote($params['end']);
        $modules = [];

        if ($calendarId != 'owner') {
            foreach ($this->getCalendarItems($calendarId, $userId) as $item) {
                $modules[$item['module']] = [
                    'settings' => ['calendarfilter' => $item['module_filter']],
                    'type' => $item['type'],
                    'allUsers' => true
                ];
            }
        }

        return SpiceFTSActivityHandler::loadCalendarEvents($start, $end, $userId, $params['searchTerm'], $modules);
    }

    /**
     * get available calendars
     * @return array
     * @throws Exception
     */
    public function getCalendars(): array {
        $db = DBManagerFactory::getInstance();
        $retArray = [];
        $calendars = "SELECT id, name, icon FROM sysuicalendars WHERE is_default = 1";
        $calendars = $db->query($calendars);

        while($calendar = $db->fetchByAssoc($calendars)) {
                $retArray[] = $calendar;
        }
        return $retArray;
    }

    /**
     * get calendar items
     * @param string $calendarId
     * @param string $userId
     * @return array
     * @throws Exception
     */
    private function getCalendarItems(string $calendarId, string $userId): array
    {
        $db = DBManagerFactory::getInstance();
        return $db->fetchAll("SELECT module, type, module_filter FROM sysuicalendaritems WHERE calendar_id = '$calendarId' AND owner = '$userId' UNION SELECT module, type, module_filter FROM sysuicustomcalendaritems WHERE calendar_id = '$calendarId' AND owner = '$userId'") ?: [];
    }
}
