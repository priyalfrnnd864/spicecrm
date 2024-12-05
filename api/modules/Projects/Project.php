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
namespace SpiceCRM\modules\Projects;

use SpiceCRM\data\BeanFactory;
use SpiceCRM\data\SpiceBean;
use SpiceCRM\includes\utils\SpiceUtils;

class Project extends SpiceBean {
    // calculated information
    public $total_estimated_effort;
    public $total_actual_effort;
    public $estimated_start_date;
    public $estimated_end_date;


    /**
     * add some calculated values on retrieve
     * @param int $id
     * @param false $encode
     * @param bool $deleted
     * @param bool $relationships
     * @return Project|null
     */
    public function retrieve($id = -1, $encode = false, $deleted = true, $relationships = true)
    {
        $bean =  parent::retrieve($id, $encode, $deleted, $relationships);

        // calculations
        if($bean){
            // calculate planned & actual efforts using wbs elements
            $this->total_estimated_effort = 0;
            $this->total_actual_effort = 0;
            $wbsElements = $this->get_linked_beans('projectwbss');
            foreach($wbsElements as $wbs){
                // handle planned efforts
                if(is_string($wbs->planned_effort)) $wbs->planned_effort = intval($wbs->planned_effort);
                $this->total_estimated_effort += $wbs->planned_effort;
                // handle actual efforts
                if(is_string($wbs->consumed_effort)) $wbs->consumed_effort = intval($wbs->consumed_effort);
                $this->total_actual_effort += $wbs->consumed_effort;
            }

            // calculate start and end dates according to earliest WBS element start date and lastest WBS end date
            if(count($wbsElements) > 0) {
                $this->estimated_start_date = SpiceUtils::getMinDate($wbsElements, 'date_start');
                $this->estimated_end_date = SpiceUtils::getMaxDate($wbsElements, 'date_end');
            }
                    }

        return $bean;
    }




    /**
     *
     */
    function get_summary_text()
    {
        return $this->name;
    }



    function bean_implements($interface){
        switch($interface){
            case 'ACL':return true;
        }
        return false;
    }



}

