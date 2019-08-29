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
 * File: install.php
 * Last Modified: 8/24/19, 12:53 PM
 */

(PHP_SAPI !== 'cli' || isset($_SERVER['HTTP_USER_AGENT'])) && die('cli only');

$engine = "InnoDB";
$charset = "latin1";

include( __DIR__ . "/../config.php" );
include($CFG->path . "/loadclass.php");
include("tables.php");
include("defaultDB.php");

$CFG->prefix = $CFG->main_prefix;

echo  nl2br( "Installing environment" . PHP_EOL );
echo  nl2br( "----------------------------" . PHP_EOL .PHP_EOL );

echo  nl2br( "Creating default tables" . PHP_EOL );
echo  nl2br( "-----------------------" . PHP_EOL );


foreach($tables as $table => $tableval){

    $sql = "CREATE TABLE `" . $CFG->prefix . $table . "` (" . $tableval . ") ENGINE=" . $engine . " AUTO_INCREMENT=0 DEFAULT CHARSET=" . $charset .";";
    if(sqlhandler::putsql($sql)) {
        echo  nl2br($CFG->prefix . $table . " table created successfully." . PHP_EOL );
    }
    else {
        echo nl2br("Error trying to create " . $CFG->prefix . $table . " table." . PHP_EOL);
    }
}


//echo  nl2br( PHP_EOL . "Creating default views" . PHP_EOL );
//echo  nl2br( "-----------------------" . PHP_EOL );
//
//
//foreach($views as $view => $sql){
//
//    if(putsql($sql)) {
//        echo  nl2br($CFG->prefix . $view . " view created successfully." . PHP_EOL );
//    }
//    else {
//        echo nl2br("Error trying to create " . $CFG->prefix . $view . " view." . PHP_EOL);
//    }
//}

echo  nl2br( PHP_EOL ."Populating tables with default values" . PHP_EOL );
echo  nl2br( "-------------------------------------" . PHP_EOL );

foreach($defaultDB as $superArray){

    $tablename = $superArray[0];

    $tabledata = array();
    foreach($superArray[1] as $key=>$value){
        $dbinfo['type'] = 's';
        $dbinfo['name'] = $key;
        $dbinfo['value'] = $value;

        $tabledata[] = $dbinfo;
    }

    $sql = sqlhandler::parseInsertSQLfromarray( $tablename ,$tabledata);
    $obj = sqlhandler::pushsql($sql,$tabledata);
    $result =  $obj->result;

    echo nl2br('Insert into ' . $CFG->prefix . $tablename . ' values ' .  serialize( $superArray[1] ) . '   - '  .     $result . PHP_EOL);

}

$userinfo = array(
    'username'=>'admin' ,
    'user_p'=>'admin',
    'firstname'=>'admin',
    'lastname'=>'admin'

);

$admin = new user();
$admin->createuser($userinfo);