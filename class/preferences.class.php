<?php
/**
 * The Javaria Project
 * Copyright © 2019
 * Michel Noel
 * Datalight Analytics
 * http://www.datalightanalytics.com/
 *
 * Creative Commons Attribution-ShareAlike 4.0 International Public License
 * By exercising the Licensed Rights (defined below), You accept and agree to be bound by the terms and conditions of
 * this Creative Commons Attribution-ShareAlike 4.0 International Public License ("Public License"). To the extent this
 * Public License may be interpreted as a contract, You are granted the Licensed Rights in consideration of Your
 * acceptance of these terms and conditions, and the Licensor grants You such rights in consideration of benefits the
 * Licensor receives from making the Licensed Material available under these terms and conditions.
 *
 * File: preferences.class.php
 * Last Modified: 8/24/19, 12:53 PM
 */

class preferences
{
    public function __construct()
    {

    }

    function getPreference($preference){
        $colarray = array('value');
        $wherearray[] = array('type'=>'s','name'=>'preference','value'=>$preference);
        $pre_sql = sqlhandler::parseReturnSQLfromarray('preferences', $colarray,$wherearray);
        $pref =  sqlhandler::getsql($pre_sql,$wherearray);

        if(!empty($pref) && array_key_exists('value',$pref[0])){
            return $pref[0]['value'];
        }
        return false;
    }

    function getAllPreferences(){
        $colarray = array('preference','value');
        $pre_sql = sqlhandler::parseReturnSQLfromarray('preferences', $colarray);
        return sqlhandler::getsql($pre_sql);
    }

    function updateprefences($preferencesArray){
        foreach ($preferencesArray as $item => $value) {
            $valuearray = array();
            $valuearray[] = array('type'=>'s','name'=>'preference','value'=>$item);
            $valuearray[] = array('type'=>'s','name'=>'value', 'value'=>$value);
            $pre_sql = sqlhandler::parseReplaceSQLfromarray('preferences', $valuearray);
            sqlhandler::pushsql($pre_sql,$valuearray);
        }
    }
}