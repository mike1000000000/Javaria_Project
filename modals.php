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
 * File: modals.php
 * Last Modified: 8/24/19, 1:52 PM
 */

echo '<link rel="stylesheet" href="css/modal.css">';

if(has_permission('addeditchart')) {
    include('modals/chart_edit.php');
    include('modals/chart_edit_datasets.php');
}

if(has_permission('addedithtml')) {
    include('modals/html_edit.php');
}

if(has_permission('addeditdashboard')) {
    include_once('modals/dashboards_addsingle.php');
}

if(has_permission('admindashboards')) {
    include('modals/dashboards.php');
    include_once('modals/dashboards_addsingle.php');
    include('modals/dashboards_members.php');
}

if(has_permission('adminusers')){
    include('modals/users.php');
}

if(has_permission('adminthemes')) {
    include('modals/themes.php');
    include('modals/theme_edit.php');
    include('modals/colours.php');
    include('modals/colours_edit.php');
}

if(has_permission('admingroups')) {
    include('modals/groups.php');
    include('modals/groups_edit.php');
    include('modals/groups_members.php');
}

if(has_permission('adminroles')) {
    include('modals/roles.php');
    include('modals/roles_edit.php');
    include('modals/roles_members.php');
}

if(has_permission('admindataconnections')) {
    include('modals/dataconnections.php');
    include('modals/dataconnections_edit.php');
    include('modals/dataconnections_members.php');
}

if(has_permission('adminloginas')) {
    include('modals/admin_loginas.php');
}

if(has_permission('elementappearance')) {
    include('modals/element_appearance.php');
}

if(has_permission('adminpreferences')) {
    include('modals/preferences.php');
}

//USER Menu
include('modals/user_signout.php');
include('modals/singlequestion.php');
include('modals/users_edit.php');
include('modals/about.php');