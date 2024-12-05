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

if(!function_exists("randomstring")){ 
    function randomstring(){
        $len = 10;
        $base='abcdefghjkmnpqrstwxyz';
        $max=strlen($base)-1;
        $returnstring = '';
        //2013-09-06 BUG #496 removed ... causing issues in higher php releases
        //mt_srand((double)microtime()*1000000);
        while (strlen($returnstring)<$len+1)
            $returnstring.=$base[mt_rand(0,$max)];

        return $returnstring;

    }
}

function calculate_trendline($values, $offset = true)
{
    // get the total
    $sumX = 0; $sumY = 0;
    foreach($values as $datapointX => $datapointY)    
    {
        $sumY += $datapointY;
        $sumX += $datapointX;
    }

    // get the averages
    $avgX = $sumX / count($values);
    $avgY = $sumY / count($values);

    // get the alpha
    $sumNalpha = 0; $sumZalpha = 0;
    foreach($values as $datapointX => $datapointY)    
    {
        $sumNalpha += ($datapointX - $avgX)*($datapointY - $avgY);
        $sumZalpha += ($datapointX - $avgX) * ($datapointX - $avgX);
    }

    // calculate the alpha value
    $alpha = $sumZalpha > 0 ? $sumNalpha / $sumZalpha : 0;

    $startValue = $avgY - (((count($values) / 2) + 1) * $alpha); 
    $endValue = $avgY + (((count($values) / 2) + 1) * $alpha); 

    return [
    'start' => round($startValue, 0), 
    'end' => round($endValue, 0)
    ];
}
function multisort($array, $sort_by, $key1, $key2=NULL, $key3=NULL, $key4=NULL, $key5=NULL, $key6=NULL){
    // sort by ?
    foreach ($array as $pos =>  $val)
        $tmp_array[$pos] = $val[$sort_by];
    asort($tmp_array);

    // display however you want
    foreach ($tmp_array as $pos =>  $val){
        $return_array[$pos][$sort_by] = $array[$pos][$sort_by];
        $return_array[$pos][$key1] = $array[$pos][$key1];
        if (isset($key2)){
            $return_array[$pos][$key2] = $array[$pos][$key2];
        }
        if (isset($key3)){
            $return_array[$pos][$key3] = $array[$pos][$key3];
        }
        if (isset($key4)){
            $return_array[$pos][$key4] = $array[$pos][$key4];
        }
        if (isset($key5)){
            $return_array[$pos][$key5] = $array[$pos][$key5];
        }
        if (isset($key6)){
            $return_array[$pos][$key6] = $array[$pos][$key6];
        }
    }
    return $return_array;
}

function sortFieldArrayBySequence($first, $second)
{
    return $first['sequence'] - $second['sequence'];
}

function getLastDayOfMonth($month, $year) {
    return date('Y-m-d',strtotime('-1 second',strtotime('+1 month',strtotime($month.'/01/'.$year.' 00:00:00'))));
}


if(file_exists('custom/modules/KReports/utils.php'))
    include('custom/modules/KReports/utils.php');
