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

namespace SpiceCRM\modules\EventBookings;

use SpiceCRM\data\api\handlers\SpiceBeanHandler;
use SpiceCRM\data\BeanFactory;
use SpiceCRM\data\SpiceBean;
use SpiceCRM\includes\database\DBManagerFactory;
use SpiceCRM\includes\ErrorHandlers\BadRequestException;
use SpiceCRM\includes\SpiceNumberRanges\SpiceNumberRanges;
use SpiceCRM\includes\SugarObjects\SpiceConfig;
use SpiceCRM\includes\TimeDate;
use SpiceCRM\modules\EventCapacityTypes\EventCapacityType;
use SpiceCRM\modules\Events\Event;

class EventBooking extends SpiceBean
{

    /**
     * before default save logic; generating a ticket number
     *
     * @param false $check_notify
     * @param bool $fts_index_bean
     * @return int|string
     */
    public function save($check_notify = false, $fts_index_bean = true)
    {
        if($this->isNew())
        $capacity = BeanFactory::getBean('EventCapacities', $this->event_capacity_id);
        $capacityType = BeanFactory::getBean('EventCapacityTypes', $capacity->eventcapacitytype_id);

        # don´t check blocked when hl7 is saving
        if ( !EventCapacityType::participantIsBlocked( $this->date_start, $capacityType, [ 'participantType' => $this->parent_type, 'participantId' => $this->parent_id ] ) or $this->_fromHL7processor )
        {
            if ( $capacityType->callCapacityTypeMethod($this->parent_type, $this->parent_id ) or $this->_fromHL7processor )
            {
                // set eventbooking_number
                if ( empty( $this->name )) {
                    $this->name = str_pad( SpiceNumberRanges::getNextNumberForField('EventBookings', 'name'), 10, '0', STR_PAD_LEFT );
                }
                if ( empty( $this->channel )) $this->channel = 'crm';
                return parent::save( $check_notify );
            }
        }
        return false;
    }



    /**
     * save from landing page
     *
     * @param array $bodyParams
     * @return array
     * @throws NotFoundException
     */
    public static function saveFromLandingPage($bodyParams, Event $event) {

        $eventbooking = BeanFactory::getBean('EventBookings');

        // find EventCapacityType by subtype
        $capacityTypesWithMatchingSubtype = [];
        foreach ( $event->getCapacityTypes() as $capacityType ) {
            if ( empty( $capacityType->no_booking_offer )
                and (   $capacityType['subtype'] == $bodyParams['eventType']
                        or ( empty( $capacityType['subtype'] ) and empty( $bodyParams['eventType'] )))) {
                $capacityTypesWithMatchingSubtype[] = BeanFactory::getBean('EventCapacityTypes', $capacityType['id']);
            }
        }

        $eventbooking->date_start = $bodyParams['selectedSlot'];

        if ( isset( $bodyParams['bookerId'] )) {

            $bookerIdCheck = empty( SpiceConfig::getInstance()->config['eventmanagement']['bookerIdCheck'] ) ? '{}' : SpiceConfig::getInstance()->config['eventmanagement']['bookerIdCheck'];
            $bookerIdCheck = json_decode( $bookerIdCheck, true );
            $bookerIdCheck = isset( $bookerIdCheck[$event->category] ) ? $bookerIdCheck[$event->category] : [];

            $bodyParams['bookerId'] = trim( $bodyParams['bookerId'] );
            if ( empty( $bodyParams['bookerId'] ) or ( !empty( $bookerIdCheck['regex'] ) and !preg_match( '/'.$bookerIdCheck['regex'].'/', $bodyParams['bookerId'], $matches ))) {
                throw ( new BadRequestException('Invalid Data.'))->setErrorCode('invalidBookerID');
            }
            $sanitizedBookerId = $matches[$bookerIdCheck['idIsSubpattern']*1];
            $sanitizedBookerId = ( $bookerIdCheck['isNumeric'] ? intval( $sanitizedBookerId ) : $sanitizedBookerId );

            // get participant by bookerId
            $participant = $eventbooking->getBookingParticipant( $sanitizedBookerId );

            if ( $participant ) {
                if ( $participant->birthdate !== $bodyParams['birthdate'] ) {
                    throw (new BadRequestException('Invalid Data.'))->setErrorCode('birthdateNotMatching');
                }
                if ( EventCapacityType::participantIsBlocked( $bodyParams['selectedSlot'], $bodyParams['eventType'], $participant )) {
                    throw (new BadRequestException('Consumer is blocked'))->setErrorCode('consumerIsBlocked')->setLbl('LBL_BLOCKED_FOR_THIS_EVENT');
                }
                $eventbooking->parent_type = 'Consumers';
                $eventbooking->parent_id = $participant->id;
            } else {
                throw ( new BadRequestException('Invalid Data.'))->setErrorCode('unknownBookerID');
            }

        } else {

            if ( !isset( $bodyParams['lastName'][0] )
                 or !isset( $bodyParams['firstName'][0] )
                 or !isset( $bodyParams['mobilePhone'][0] )
                 or !isset( $bodyParams['street'][0] )
                 or !isset( $bodyParams['postalCode'][0] )
                 or !isset( $bodyParams['city'][0] )
                 or !isset( $bodyParams['birthdate'][0] )
                 or !preg_match('#^(\d{4})-(\d\d)-(\d\d)$#', $bodyParams['birthdate'], $matches )
                 or !checkdate( $matches[2], $matches[3], $matches[1] )
                 or !isset( $bodyParams['gender'] )
                 or ( $bodyParams['gender'] !== 'm' and $bodyParams['gender'] !== 'f' )
            ) {
                throw ( new BadRequestException('Invalid Data.'))->setErrorCode('invalidPersonalData');
            }

            if ( !isset( $bodyParams['gdpr'] ) or $bodyParams['gdpr'] !== true ) throw ( new BadRequestException('Missing GDPR Consent.'))->setErrorCode('noGdprConsent');

            $participant = BeanFactory::getBean('Consumers');

            $participant->birthdate = $bodyParams['birthdate'];
            $participant->first_name = $bodyParams['firstName'];
            $participant->last_name = $bodyParams['lastName'];
            $participant->phone1 = $bodyParams['mobilePhone'];
            $participant->salutation = $bodyParams['gender'] === 'm' ? 'Mr.' : ( $bodyParams['gender'] === 'f' ? 'Ms.' : null );
            $participant->primary_address_city = $bodyParams['city'];
            $participant->primary_address_street = $bodyParams['street'];
            $participant->primary_address_postalcode = $bodyParams['postalCode'];
            $participant->primary_address_country = 'AT';
            $participant->gdpr_data_agreement = 1;
            $participant->gdpr_data_source = 'LandingPage/EventBooking; '.gmdate('Y-m-d H:i:s').' (UTC)';
            if ( isset( $bodyParams['emailAddress'][0] )) $participant->email1 = $bodyParams['emailAddress'];

            $participant->save();
            $participant->call_custom_logic('after_save_completed', '');
            $eventbooking->parent_type = 'Consumers';
            $eventbooking->parent_id = $participant->id;
        }


        if($bodyParams['event_capacity_id']) {
            $eventbooking->event_capacity_id = $bodyParams['event_capacity_id'];
        } else if( $event && count( $capacityTypesWithMatchingSubtype )) {
            # foreach ( $capacityTypesWithMatchingSubtype as $eventcapacitytype ) {
                $eventbooking->findCapacity($event, $capacityTypesWithMatchingSubtype);
            #    if ( !empty( $eventbooking->event_capacity_id )) break;
            #}
        }
        if ( empty( $eventbooking->event_capacity_id )) {
            throw ( new BadRequestException('Invalid Data.'))->setErrorCode('noCapacityFound');
        }

        $eventbooking->channel = 'web';
        $eventbooking->save();

        return ['notificationTo' => $participant->phone1];
        # return $moduleHandler->mapBeanToArray('EventBookings', $eventbooking);
    }


    /**
     * get participant
     *
     * @param $participant_type
     * @param $participant_id
     * @return array
     * @throws NotFoundException
     */
    public function getBookingParticipant( string $ext_id_card ): ?object
    {
        $db = DBManagerFactory::getInstance();

        #$sql = "SELECT * FROM consumers WHERE ext_id_card='" . $ext_id_card . "' AND deleted=0";
        $consumerId = $db->getOne("SELECT id FROM consumers WHERE ext_id_card='" . $ext_id_card . "' AND deleted <> 1");
        if ( $consumerId ) {
            $participant = BeanFactory::getBean('Consumers', $consumerId );
            # todo: throw exception
            return $participant;
        } else return null;
    }


    /**
     * if there is no event_capacity_id -> find the capacity
     * take the first with free slot in timeslot; if full -> take the last one (overbooked)
     *
     * @param $participant_type
     * @param $participant_id
     * @return array
     * @throws NotFoundException
     */
    public function findCapacity($event, array $capacityTypes ) {

        $event = BeanFactory::getBean('Events', $event->id);

        /** @var EventCapacityType $eventcapacitytype */
        foreach ($capacityTypes as $capacityType ) {
            $typedata = $event->getEventTableCapacityType($capacityType, true, false, null);
            foreach ($typedata['calculated_timeslots'] as $timeslot) {
                if ($timeslot['from'] == $this->date_start) {
                    $counter = 0;

                    foreach ($timeslot['capacities'] as $capacity) {
                        for ($i = 0; $i < $capacity['slots']; $i++) {
                            $counter++;
                            if($counter == count($timeslot['bookings']) + 1) {
                                $this->event_capacity_id = $capacity['id'];
                                return;
                            }
                        }
                    }
                    if(!$this->event_capacity_id) {
                        $this->event_capacity_id = $timeslot['capacities'][count($timeslot['capacities']) - 1]['id'];
                        return;
                    }
                }
            }
        }
    }

}
