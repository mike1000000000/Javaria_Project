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
 * File: log.class.php
 * Last Modified: 8/24/19, 12:53 PM
 */

class log
{
    public function __construct() {

    }

    function write_log($log_msg)
    {
        global $CFG;
        if(!isset($CFG->logging) || $CFG->logging == 0) { return;}

        $log_filename = $CFG->logfilepath . '/' .$CFG->logfileprefix . '_' . date('d-M-Y') .  '.log';

        if (!file_exists($CFG->logfilepath))
        {
            $result = mkdir($CFG->logfilepath, 0755, true);
        }

        $message = date("Y-m-d H:i:s",time()) . ' ';
        $message .= 'Server: ';
        if(isset($_SERVER['SERVER_NAME'])) $message .= $_SERVER['SERVER_NAME']. ' ';
        if(isset($_SERVER['SERVER_ADDR'])) $message .= $_SERVER['SERVER_ADDR']. ':';
        if(isset($_SERVER['SERVER_PORT'])) $message .= $_SERVER['SERVER_PORT']. ' ';
        if(isset($_SERVER['SCRIPT_NAME'])) $message .= $_SERVER['SCRIPT_NAME']. ' ';

        $message .= '-- HTTP user: ';
        if(isset($_SERVER['REMOTE_ADDR'])) $message .= $_SERVER['REMOTE_ADDR']. ' ';
        if(isset($_SERVER['REQUEST_METHOD'])) $message .= $_SERVER['REQUEST_METHOD']. ' ';
        if(isset($_SERVER['HTTP_USER_AGENT'])) $message .= $_SERVER['HTTP_USER_AGENT']. ' ';

        $message .= '-- Web user: ';
        if(isset($_SESSION['login_user'])) $message .= $_SESSION['login_user']. ' ';
        if(isset($_SESSION['login_id'])) $message .= $_SESSION['login_id']. ' ';
        if(isset($_SESSION['dashboard'])) $message .= $_SESSION['dashboard']. ' ';

        file_put_contents($log_filename, $message . ' -- Log message -- ' . $log_msg . "\n", FILE_APPEND);
    }
}