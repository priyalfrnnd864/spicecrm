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

namespace SpiceCRM\includes\authentication\interfaces;

use SpiceCRM\includes\ErrorHandlers\BadRequestException;
use SpiceCRM\includes\ErrorHandlers\NotFoundException;
use Exception;

interface AccessUtilsI
{
    /**
     * Blocks a user (prevent from login) permanent or for a specific time
     * @param string $username The name of the user.
     * @param string|null $blockingDuration The time in minutes that the user should be blocked from logging in. From now on
     * @throws BadRequestException
     * @throws NotFoundException
     */
    public function blockUserByName(string $username, string $blockingDuration = null);

    /**
     * Checks if a user is blocked permanent or for a specific time
     * @param string $username The name of the user.
     * @return True if permanent or the amount of minutes in case the blocking is for a specific time
     * @throws Exception
     */
    public function isBlocked(string $username): bool;

    /**
     * check if the ip address is blacklisted
     * @param $ipAddress
     * @return bool
     * @throws Exception
     */
    static function checkIpAddress( $ipAddress = null ): bool;

    /**
     * check if ip address is whitelisted
     * @param $ipAddress
     * @return bool
     * @throws Exception
     */
    static function ipAddressIsWhite( $ipAddress = null ): bool;

    /**
     * add an ip address as white/black-listed
     * @param $color
     * @param $description
     * @param $ipAddress
     * @param $createdBy
     * @return array|false
     * @throws BadRequestException
     * @throws \SpiceCRM\includes\ErrorHandlers\Exception
     */
    static function addIpAddress( $color, $description = null, $ipAddress = null, $createdBy = null );

    /**
     * delete an ip address
     * @param $ipAddress
     * @return array
     * @throws NotFoundException
     */
    static function deleteIpAddress( $ipAddress = null ): string;

    /**
     * alter an ip address entry
     * @param $description
     * @param $ipAddress
     * @return array|false
     * @throws NotFoundException
     */
    static function alterIpAddress( $description, $ipAddress = null );

    /**
     * move an ip address between black and white lists
     * @param $color
     * @param $ipAddress
     * @param $createdBy
     * @return array|false
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws \SpiceCRM\includes\ErrorHandlers\Exception
     */
    static function moveIpAddress( $color, $ipAddress = null, $createdBy = null );
}