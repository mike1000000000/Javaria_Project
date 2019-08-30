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
 * File: login.class.php
 * Last Modified: 8/24/19, 12:53 PM
 */

class login
{

    public function __construct()
    {

    }

    function userlogin($username, $password){
        $colarray = array('username', 'user_p');

        $wherearray[] = array('type'=>'s','name'=>'username','value'=>$username);
        $wherearray[] = array('type'=>'i','name'=>'deleted','value'=>'0');

        $pre_sql = sqlhandler::parseReturnSQLfromarray('users', $colarray, $wherearray);
        $value = sqlhandler::getsql($pre_sql,$wherearray);

        if(empty($value) || !array_key_exists(0,$value)) return false;

        $value = $value[0];

        return password_verify($password, $value['user_p']);
    }
}