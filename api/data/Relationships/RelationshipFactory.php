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

namespace SpiceCRM\data\Relationships;

use SpiceCRM\includes\database\DBManagerFactory;
use SpiceCRM\includes\Logger\LoggerManager;
use SpiceCRM\includes\SpiceCache\SpiceCache;
use SpiceCRM\includes\SpiceDictionary\SpiceDictionaryVardefs;

/**
 * Create relationship objects
 * @api
 */
class RelationshipFactory {
    static $rfInstance;

    protected $relationships;
    protected $relationshiptypes;

    protected function __construct(){
        //Load the relationship definitions from the cache.
        $this->loadRelationships();
        $this->loadRelationshipTypes();
    }

    /**
     * @static
     * @return RelationshipFactory
     */
    public static function getInstance()
    {
        if (is_null(self::$rfInstance))
            self::$rfInstance = new RelationshipFactory();
        return self::$rfInstance;
    }


    /**
     * @param  $relationshipName String name of relationship to load
     * @return false|EmailAddressRelationship|M2MRelationship|One2MBeanRelationship|One2MRelationship|One2OneBeanRelationship|One2OneRelationship
     */
    public function getRelationship($relationshipName)
    {
        if (empty($this->relationships[$relationshipName])) {
            LoggerManager::getLogger()->debug("Unable to find relationship in ".__CLASS__." ".__FUNCTION__."() on line ".__LINE__." $relationshipName");
            return false;
        }

        $def = $this->relationships[$relationshipName];

        $type = isset($def['true_relationship_type']) ? $def['true_relationship_type'] : $def['relationship_type'];
        if(isset($this->relationshiptypes[$type])){
            return new $this->relationshiptypes[$type]($def);
        }
        /*
        switch($type)
        {
            case "many-to-many-bean":
                return new M2MBeanRelationship($def);
            case "many-to-many":
                if (isset($def['rhs_module']) && $def['rhs_module'] == 'EmailAddresses')
                {
                    return new EmailAddressRelationship($def);
                }
                return new M2MRelationship($def);
            case "one-to-many":
                //If a relationship has no table or join keys, it must be bean based
                if (empty($def['true_relationship_type']) || (empty($def['table']) && empty($def['join_table'])) || empty($def['join_key_rhs'])){
                    return new One2MBeanRelationship($def);
                }
                else {
                    return new One2MRelationship($def);
                }
            case "one-to-one":
                if (empty($def['true_relationship_type'])){
                    return new One2OneBeanRelationship($def);
                }
                else {
                    return new One2OneRelationship($def);
                }
        }
        */

        LoggerManager::getLogger()->fatal ("$relationshipName had an unknown type $type ");

        return false;
    }


    /**
     * @param false $forceLoadFromDb
     */
    public function loadRelationships($forceLoadFromDb = false)
    {
        $cached = SpiceCache::get('relationships');
        if(!$cached || $forceLoadFromDb) {
            $this->relationships = SpiceDictionaryVardefs::loadRelationships();
            SpiceCache::set('relationships', $this->relationships);
        } else {
            $this->relationships = $cached;
        }
    }


    /**
     * @param false $forceLoadFromDb
     */
    public function loadRelationshipTypes($forceLoadFromDb = false)
    {
        $this->relationshiptypes = [];
        $cached = SpiceCache::get('relationshiptypes');
        if(!$cached || $forceLoadFromDb) {
            $relTypes = DBManagerFactory::getInstance()->fetchAll('SELECT name, class FROM sysdictionaryrelationshiptypes');
            foreach ($relTypes as $relType) $this->relationshiptypes[$relType['name']] = $relType['class'];

            SpiceCache::set('relationshiptypes', $this->relationshiptypes);
        } else {
            $this->relationshiptypes = $cached;
        }
    }


    /**
     * load relationships from cache table
     * @param string $module filter on module
     * @return void
     */
    private function loadRelationshipsCacheFromDb($module = null){
        $relationships = SpiceDictionaryVardefs::getRelationshipsCacheFromDb($module);
        $this->relationships = $relationships;
    }



}
