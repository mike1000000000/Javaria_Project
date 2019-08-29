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
 * File: defaultDB.php
 * Last Modified: 8/24/19, 12:53 PM
 */

(PHP_SAPI !== 'cli' || isset($_SERVER['HTTP_USER_AGENT'])) && die('cli only');

//Theme and colours
$defaultDB[] = array('colours', array('colour_name'=>'Red'  , 'colour_code'=>'FF0000'));
$defaultDB[] = array('colours', array('colour_name'=>'Green', 'colour_code'=>'00FF00'));
$defaultDB[] = array('colours', array('colour_name'=>'Blue' , 'colour_code'=>'0000FF'));

$defaultDB[] = array('themes', array('theme_name'=>'Default'));

$defaultDB[] = array('colours_theme_colours', array('theme_id'=>'1','colour_ids'=>'1'));
$defaultDB[] = array('colours_theme_colours', array('theme_id'=>'1','colour_ids'=>'2'));
$defaultDB[] = array('colours_theme_colours', array('theme_id'=>'1','colour_ids'=>'3'));

//Default dashboard
$defaultDB[] = array('dashboards', array('name'=>'Default', 'user_id'=>'0'));
$defaultDB[] = array('preferences', array('preference'=>'default_dashboard', 'value'=>'1'));

//Permissions
$defaultDB[] = array('permissions', array('name'=>'dashboardmenu'));
$defaultDB[] = array('permissions', array('name'=>'addeditdashboard'));

$defaultDB[] = array('permissions', array('name'=>'blockmenu'));
$defaultDB[] = array('permissions', array('name'=>'addeditchart'));
$defaultDB[] = array('permissions', array('name'=>'addedithtml'));
$defaultDB[] = array('permissions', array('name'=>'elementdelete'));
$defaultDB[] = array('permissions', array('name'=>'elementupdate'));
$defaultDB[] = array('permissions', array('name'=>'viewdataconnections'));

$defaultDB[] = array('permissions', array('name'=>'contextshow'));

$defaultDB[] = array('permissions', array('name'=>'adminmenu'));
$defaultDB[] = array('permissions', array('name'=>'adminusers'));
$defaultDB[] = array('permissions', array('name'=>'admindashboards'));
$defaultDB[] = array('permissions', array('name'=>'adminthemes'));
$defaultDB[] = array('permissions', array('name'=>'admingroups'));
$defaultDB[] = array('permissions', array('name'=>'adminroles'));
$defaultDB[] = array('permissions', array('name'=>'admindataconnections'));
$defaultDB[] = array('permissions', array('name'=>'adminloginas'));
$defaultDB[] = array('permissions', array('name'=>'adminpreferences'));

$defaultDB[] = array('permissions', array('name'=>'userview'));
$defaultDB[] = array('permissions', array('name'=>'elementappearance'));

$defaultDB[] = array('permissions', array('name'=>'bypass_userscreening'));
$defaultDB[] = array('permissions', array('name'=>'bypass_groupscreening'));
$defaultDB[] = array('permissions', array('name'=>'bypass_dashboardscreening'));
$defaultDB[] = array('permissions', array('name'=>'bypass_dataconnectionscreening'));

//Example Data
$defaultDB[] = array('example_data_simple', array('customer_name'=>'Company A' , 'total_revenue'=>'100'));
$defaultDB[] = array('example_data_simple', array('customer_name'=>'Company B' , 'total_revenue'=>'200'));
$defaultDB[] = array('example_data_simple', array('customer_name'=>'Company C' , 'total_revenue'=>'50'));
$defaultDB[] = array('example_data_simple', array('customer_name'=>'Company D' , 'total_revenue'=>'0'));

