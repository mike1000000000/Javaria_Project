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
 * File: loadclass.php
 * Last Modified: 8/24/19, 12:53 PM
 */

include_once('config.php');
include_once('lang.php');

global $CFG;
spl_autoload_register(function (String $class) use ($CFG) {
        $filename = strtolower($class) . '.class.php';
        $filepath = $CFG->path . '/class/' . $filename;
        if (is_readable($filepath)) {
            include_once $filepath;
        }
    }
);

// Global function to handle permissions
function has_permission($permissionid){
    $permission = new permissions();
    return $permission->has_permission($permissionid);
}
