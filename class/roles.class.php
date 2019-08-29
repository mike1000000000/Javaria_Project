<?php
/**
 * Javaria Project
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
 * File: roles.class.php
 * Last Modified: 8/24/19, 12:53 PM
 */

class roles
{
    function getrolelist(){
        $colarray = array('id','name','notes');

        $wherearray[] = array('type'=>'i','name'=>'deleted','value'=>'0');
        $pre_sql = sqlhandler::parseReturnSQLfromarray('role_info', $colarray, $wherearray);
        $value = sqlhandler::getsql($pre_sql,$wherearray);

        return $value;
    }
}