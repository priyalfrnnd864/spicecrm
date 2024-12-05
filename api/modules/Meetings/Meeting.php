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
namespace SpiceCRM\modules\Meetings;

use SpiceCRM\data\SpiceBean;
use DateTime;
use SpiceCRM\includes\TimeDate;

class Meeting extends SpiceBean
{
    var $date_changed = false;

    // save date_end by calculating user input
    // this is for calendar
    function save($check_notify = FALSE, $fts_index_bean = TRUE)
    {
        $timedate = TimeDate::getInstance();

        if (isset($this->date_start)) {
            $td = $timedate->fromDb($this->date_start);
            if (!$td) {
                $this->date_start = $timedate->to_db($this->date_start);
                $td = $timedate->fromDb($this->date_start);
            }
            if ($td) {
                if (isset($this->duration_hours) && $this->duration_hours != '') {
                    $this->duration_hours = (int)$this->duration_hours;
                    $td->modify("+{$this->duration_hours} hours");
                }
                if (isset($this->duration_minutes) && $this->duration_minutes != '') {
                    $this->duration_minutes = (int)$this->duration_minutes;
                    $td->modify("+{$this->duration_minutes} mins");
                }
                if (isset($this->date_end)) {
                    $dateEnd = $timedate->fromDb($this->date_end);
                    if ($dateEnd) {
                        $td = $dateEnd;
                    }
                }
                $this->date_end = $td->format($timedate::DB_DATETIME_FORMAT);
            }
        }

        # reset the email_reminder_sent flag, in case the start date has been changed
        if ( !empty( $this->fetched_row['date_start'] ) and $this->email_reminder_sent and $this->date_start !== $this->fetched_row['date_start'] ) {
            $this->email_reminder_sent = false;
        }

        $return_id = parent::save($check_notify, $fts_index_bean);

        // check if contact_id is set
        if (!empty($this->contact_id)) {
            $this->load_relationship('contacts');
            $this->contacts->add($this->contact_id);
        }

        return $return_id;
    }

    function retrieve($id = -1, $encode = false, $deleted = true, $relationships = true)
    {
        $ret = parent::retrieve($id, $encode, $deleted, $relationships);

        if($ret && !is_null($this->date_start) && !is_null($this->date_end)){
            $startDateObj = new DateTime($this->date_start);
            $endDateObj = new DateTime($this->date_end);
            $interval = $startDateObj->diff($endDateObj);
            $this->duration_hours = $interval->format('%h');
            $this->duration_minutes = $interval->format('%i');
        }

        return $ret;
    }

}

