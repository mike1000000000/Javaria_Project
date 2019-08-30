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
 * File: ajaxfunctions.php
 * Last Modified: 8/19/19, 9:34 PM
 */

if (session_status() == PHP_SESSION_NONE) session_start();

$value = '';
$error = 0;
include('loadclass.php');

$message = 'Action: ' . $_POST['action'];
(new log())->write_log($message);

if(empty($_SESSION)){
    $data['error'] = 2;
    $data['value'] = mlang_str('ERROR_NOSESSION',true);
}
else if(isset($_POST['action']) && !empty($_POST['action'])) {

    $loginid = $_SESSION['login_id'];

    $action = $_POST['action'];

    switch($action) {

    case 'userGetInfo' :

            $userid = isset($_POST['id']) && $_POST['id'] !== "" ? $_POST['id'] : $loginid;

            // Check if user can see userid
            if(!(new user_filter())->availableUser($userid)) {$error = 1; $value = mlang_str('ERROR_USERINACCESSIBLE',true); continue;}

            $user = new user();
            $value = json_encode($user->getsingleuser($userid));

            break;

        case 'userAddEdit' :

            $result = $_POST['tableVals'];
            $userid = $_POST['modalval'];
            $self = (bool) ( (int)$userid === (int)$_SESSION['login_id'] );
            if(!has_permission('adminusers') && !$self){$error = 1; $value = mlang_str('ERROR_USERINACCESSIBLE',true); continue;}

            if(!empty($userid)) {
                if(!(new user_filter())->availableUser($userid)) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}
                $user = new user();
                $value = json_encode($user->updateuser($userid,$result));
            }else{
                // No group? No user.
                $groupid = (int) $result['group'];

                if($groupid == null || $groupid == 0 ) {return json_encode(array('error'=>'1')); }

                $user = new user();
                $uservalue = $user->createuser($result);

                if($uservalue->result !== -1 ){

                    $userid = $uservalue->insert_id;

                    if(!(new groups())->availableGroup($groupid) ) {return json_encode(array('error'=>'1')); }

                    $group_members = new group_members();
                    $group_members->addMember($groupid, $userid, 0);
                }
            }
            break;

        case 'userDelete' :

            if(!has_permission('adminusers')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $userid = $_POST['singleVal'];

            if(!(new user_filter())->availableUser($userid)) {$error = 1; $value = mlang_str('ERROR_USERINACCESSIBLE',true); continue;}

            $user = new user();
            $value = json_encode($user->deleteuser($userid));

            break;

        case 'userGetAllPossibleMembers':

            if(!has_permission('userview')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            if(has_permission('bypass_userscreening')){
                $userlist = new users();
                $value = $userlist->getFullList();
            }else {
                $userfilter = new user_filter();
                $members = $userfilter->getGroupUserTree();
                $membersArray = array_column($members, 'userid');

                $cleanArray = array_unique($membersArray);
                asort($cleanArray);
                if(empty($cleanArray)) return '';

                // Don't include the loggedin user in list
                if (!empty($_POST['excludeself']) && $_POST['excludeself'] = 1) {
                    $cleanArray = array_diff_assoc($cleanArray, array($loginid));
                }

                $colarray = array('id', 'firstname', 'lastname', 'username');

                $whereIn[]     = array('type'=>'s','name'=>'id','value'=>implode(',', $cleanArray));
                $wherearray[] = array('type'=>'i','name'=>'deleted','value'=>'0');

                $pre_sql = sqlhandler::parseReturnSQLIn('users', $colarray, $whereIn, $wherearray);
                $mergedarrays = array_merge_recursive( $whereIn , $wherearray);

                $value = json_encode(sqlhandler::getsql($pre_sql,$mergedarrays));
            }
            break;

        case 'usergroupGetList' :

            if(!has_permission('adminusers')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $result = $_POST['group'];

            $group = new groups();
            $cansee = $group->availableGroup($result);

            // If group selected and user can see the group
            if ( isset($result) && $result != 0 && ($cansee  || has_permission('bypass_groupscreening') )  )  {

                $groupmembers = new group_members();
                $members = $groupmembers->getMembers($result,false);

                $groupadmins = new group_members();
                $admins = $groupadmins->getMembers($result,true);

                $fullmembership = array_merge_recursive($members, $admins);

                $membersArray = array_column($fullmembership,'userid');

                $cleanArray = array_unique($membersArray);
                asort($cleanArray);
                if(empty($cleanArray)) return '';

                $colarray = array('id','username', 'firstname', 'lastname', 'email');

                $whereIn[]     = array('type'=>'s','name'=>'id','value'=>implode(',', $cleanArray));
                $wherearray[] = array('type'=>'i','name'=>'deleted','value'=>'0');

                $pre_sql = sqlhandler::parseReturnSQLIn('users', $colarray, $whereIn, $wherearray );
                $mergedarrays = array_merge_recursive($whereIn,$wherearray);
                $value = json_encode(sqlhandler::getsql($pre_sql,$mergedarrays));

                // If no group selected and user has full permissions
            }elseif(has_permission('bypass_groupscreening')) {

                $userlist = new users();
                $value = $userlist->getFullList();

                // Else only show users who are available are per their group hierarchy
            } else {

                $userfilter = new user_filter();
                $members = $userfilter->getGroupUserTree();
                $membersArray = array_column($members,'userid');

                $cleanArray = array_unique($membersArray);
                asort($cleanArray);
                if(empty($cleanArray)) continue;

                $colarray     = array('id','username', 'firstname', 'lastname', 'email');
                $whereIn[]    = array('type'=>'s','name'=>'id','value'=>implode(',', $cleanArray));
                $wherearray[] = array('type'=>'i','name'=>'deleted','value'=>'0');

                $pre_sql = sqlhandler::parseReturnSQLIn('users', $colarray, $whereIn, $wherearray);
                $mergedarrays = array_merge_recursive( $whereIn , $wherearray);
                $value = json_encode(sqlhandler::getsql($pre_sql,$mergedarrays));
            }
            break;

        case 'groupGetInfo' :

            if(!has_permission('admingroups')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $groupid = $_POST['id'];

            if(!(new groups())->availableGroup($groupid)) {$error = 1; $value = mlang_str('ERROR_GROUPINACCESSIBLE',true); continue;}

            $group = new group();
            $value = json_encode($group->getsinglegroup($groupid));

            break;

        case 'groupAddEdit' :

            if(!has_permission('admingroups')){$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $result = (array) $_POST['tableVals'];
            $groupid = $_POST['modalval'];

            if(!empty($groupid)) {
                if(!(new groups())->availableGroup($groupid)) {$error = 1; $value = mlang_str('ERROR_GROUPINACCESSIBLE',true); continue;}
                $group = new group();
                $value = json_encode($group->updategroup($groupid,$result));
            }else{
                $group = new group();
                $value = $group->creategroup($result);

                $group_members = new group_members();
                $group_members->addMember($value->insert_id, $loginid,true);

                $value = json_encode($value);
            }

            break;

        case 'groupDelete' :

            if(!has_permission('admingroups')){$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $groupid = $_POST['singleVal'];

            if(!(new groups())->availableGroup($groupid)) {$error = 1; $value = mlang_str('ERROR_GROUPINACCESSIBLE',true); continue;}

            // Set deleted flag
            $group = new group();
            $value = json_encode($group->deletegroup($groupid));

            // Remove group members
            $group_members = new group_members();
            $value = json_encode($group_members->deleteAllMembers($groupid));

            break;

        case 'groupMembers' :

            if(!has_permission('admingroups')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $groupid = $_POST['group_id'];
            $admins = $_POST['admin'];

            if(!(new groups())->availableGroup($groupid)) {$error = 1; $value = mlang_str('ERROR_GROUPINACCESSIBLE',true); continue;}

            $groupmembers = new group_members();
            $members = $groupmembers->getMembers($groupid,$admins);

            $cleanArray = array_column($members, 'userid');
            $cleanMembers = implode(',',$cleanArray);

            $value = json_encode( array('gmembers'=>$cleanMembers));

            break;

        case 'groupMembersUpdate' :

            if(!has_permission('admingroups')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $vals = array();
            $result = (array) $_POST['tableVals'];

            $groupid = $result['id'];
            $admins = $result['admin'];
            $selectedusers = $result['selectedusers'];

            if(!(new groups())->availableGroup($groupid)) {$error = 1; $value = mlang_str('ERROR_GROUPINACCESSIBLE',true); continue;}

            if (array_key_exists('selectedusers', $result) ) {

                // Remove all group users
                $group_members = new group_members();
                $group_members->deleteAllMembers($groupid, $admins);

                // If no users selected exit
                if ($selectedusers == "-") {  continue;  }

                // Add members
                foreach ($selectedusers as $userid) {
                    $group_members->addMember($groupid, $userid, $admins);
                }
            }
            break;

        case 'groupGetList' :

            $grouplist = new groups();
            $value = json_encode($grouplist->getfilteredgrouplist());

            break;

        case 'dashboardAddEdit' :

            if(!has_permission('addeditdashboard')){$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $result = (array) $_POST['tableVals'];

            $dashboardid = $_POST['modalval'];

            if(!empty($dashboardid)) {

                if(!(new dashboard())->availableDashboard($dashboardid)) {$error = 1; $value = mlang_str('ERROR_DASHBOARDINACCESSIBLE',true); continue;}
                $dashboard = new dashboard();
                $value = json_encode($dashboard->updatedashboard($dashboardid,$result));
            }else{
                $result['creator']= $loginid;
                $dashboard = new dashboard();
                $value = json_encode($dashboard->createdashboard($result));
            }
            break;

        case 'dashboardDelete' :

            if(!has_permission('admindashboards')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $dashboardid = $_POST['singleVal'];

            // Do not delete dashboard if this user is currently in it or it's not available
            if(!(new dashboard())->availableDashboard($dashboardid)  || $_SESSION['dashboard'] == $dashboardid
                || (new preferences())->getPreference('default_dashboard') == $dashboardid ) {$error = 1; $value = mlang_str('ERROR_DASHBOARDINACCESSIBLE',true); continue;}

            $dashboard = new dashboard();
            $dashboard->deletedashboard($dashboardid);

            break;

        case 'dashboardMembers' :

            if(!has_permission('admindashboards')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $dashboardid = $_POST['dashboard_id'];
            $admins = $_POST['admin'];

            if(!(new dashboard())->availableDashboard($dashboardid)) {$error = 1; $value = mlang_str('ERROR_DASHBOARDINACCESSIBLE',true); continue;}

            $dashboardmembers = new dashboard_members();
            $members = $dashboardmembers->getMembers($dashboardid,$admins);

            $cleanArray = array_column($members, 'user_id');
            $cleanMembers = implode(',',$cleanArray);

            $value = json_encode( array('dmembers'=>$cleanMembers));

            break;

        case 'dashboardMembersUpdate' :

            if(!has_permission('admindashboards')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $vals = array();
            $result = (array) $_POST['tableVals'];

            $dashboardid = $result['id'];

            if(!(new dashboard())->availableDashboard($dashboardid)) {$error = 1; $value = mlang_str('ERROR_DASHBOARDINACCESSIBLE',true); continue;}

            $admins = $result['admin'];
            $selectedusers = $result['selectedusers'];

            if (array_key_exists('selectedusers', $result) ) {
                // Remove all group users
                $dashboard_members = new dashboard_members();
                $dashboard_members->deleteAllMembers($dashboardid, $admins);

                // If no users selected exit
                if ($selectedusers == "-") {  continue;  }

                // Add members
                foreach ($selectedusers as $userid) {
                    $dashboard_members->addMember($dashboardid, $userid, $admins);
                }
            }
            break;

        case 'dashboardGetInfo' :

            if(!has_permission('addeditdashboard')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $dashboardid = $_POST['id'];

            if(!(new dashboard())->availableDashboard($dashboardid)) {$error = 1; $value = mlang_str('ERROR_DASHBOARDINACCESSIBLE',true); continue;}

            $dashboard = new dashboard();
            $value = json_encode($dashboard->getsingledashboard($dashboardid));

            break;

        case 'dashboardGetList' :

            $dashboard = new dashboard();
            $value = json_encode($dashboard->getfiltereddashboardlist());

            break;

        case 'dashboardSetSessionDB' :

            $dashboardid = $_POST['db'];
            if(!(new dashboard())->availableDashboard($dashboardid)) {$error = 1; $value = mlang_str('ERROR_DASHBOARDINACCESSIBLE',true); continue;}

            $_SESSION['dashboard'] = $dashboardid;

            break;

        case 'dashboardUpdateDefault' :

            $dashboardid = $_POST['db_no'];
            if(!(new dashboard())->availableDashboard($dashboardid)) {$error = 1; $value = mlang_str('ERROR_DASHBOARDINACCESSIBLE',true); continue;}

            $new_default_db['default_dashboard'] = $dashboardid;

            $updateuser = new user();
            $value = $updateuser->updateuser($loginid,$new_default_db);

            break;

        case 'dashboardReset' :

            // Only let users who can add/edit dashboards to reset them
            if(!has_permission('admindashboards')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $dbno = $_POST['db_no'];
            if(!(new dashboard())->availableDashboard($dbno)) {$error = 1; $value = mlang_str('ERROR_DASHBOARDINACCESSIBLE',true); continue;}

            if($dbno !== $_SESSION['dashboard']){$error = 1; $value = mlang_str('ERROR_UNKNOWN',true); continue;}

            $elements = new element();
            $result = $elements->getdashboardelements($dbno);

            $elementArray['divwidth'] = '0';
            $elementArray['divheight'] = '0';
            $elementArray['divposx'] = '0';
            $elementArray['divposy'] = '0';

            foreach ($result as $element){
                $elementId = $element['element_id'];
                $updateelement = new element();
                $value = json_encode($updateelement->updateElement($elementId, $elementArray));
            }
            break;

        case 'dashboardGetCurrentOptions' :

            $dbno = $_POST['db_no'];
            if(!(new dashboard())->availableDashboard($dbno)) {$error = 1; $value = mlang_str('ERROR_DASHBOARDINACCESSIBLE',true); continue;}

            if($dbno === $_SESSION['dashboard']) {
                $dashboard = new dashboard();
                $dashboardinfo = $dashboard->getsingledashboard($dbno);

                $value = json_encode($dashboardinfo['options']);
                $value = $dashboardinfo['options'];
            }
            break;

        case 'dataconnectionAddEdit' :

            if(!has_permission('admindataconnections')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $result = $_POST['tableVals'];
            $dataconnectionid = $_POST['modalval'];

            if(!empty($dataconnectionid)) {
                if(!(new dataconnection())->availableDataconnection($dataconnectionid)) {$error = 1; $value = mlang_str('ERROR_DATACONNECTIONINACCESSIBLE',true); continue;}
                $dataconnector = new dataconnection();
                $value = $dataconnector->updatedataconnection($dataconnectionid,$result);
            }else{
                $result['auth_ids'] = $loginid;
                $dataconnector = new dataconnection();
                $value = $dataconnector->createDataconnection($result);

                if ($value->result === -1) {
                    $value = "Error inserting into dataconnection table";
                    break;
                }
                $dataconnectionmember = new dataconnection_members();
                $dataconnectionmember->addMember($value->insert_id, $loginid);
            }
            $value = json_encode($value);
            break;

        case 'dataconnectionGetInfo' :

            if(!has_permission('admindataconnections')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $dataconnectionid = $_POST['id'];
            if(!(new dataconnection())->availableDataconnection($dataconnectionid)) {$error = 1; $value = mlang_str('ERROR_DATACONNECTIONINACCESSIBLE',true); continue;}

            $dataconnector = new dataconnection();
            $value = json_encode($dataconnector->getsingledataconnection($dataconnectionid));

            break;

        case 'dataconnectionDelete' :

            if(!has_permission('admindataconnections')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $dataconnectionid = $_POST['singleVal'];
            if(!(new dataconnection())->availableDataconnection($dataconnectionid)) {$error = 1; $value = mlang_str('ERROR_DATACONNECTIONINACCESSIBLE',true); continue;}

            $dataconnector = new dataconnection();
            $value = json_encode($dataconnector->deletedataconnection($dataconnectionid));

            break;

        case 'dataconnectionGetList' :

            // filtered list - permission not needed
            $dataconnectors = new dataconnection();
            $tempvalue = $dataconnectors->getfiltereddataconnectionlist();

            if($tempvalue === ''){$error = 1; $value = mlang_str('ERROR_DATACONNECTIONINACCESSIBLE',true); continue;}

            $value = json_encode($tempvalue);

            break;

        case 'dataconnectionGetTableList' :

            $dataconnectionid = $_POST['dc'];
            if(!(new dataconnection())->availableDataconnection($dataconnectionid)) {$error = 1; $value = mlang_str('ERROR_DATACONNECTIONINACCESSIBLE',true); continue;}

            $dataconnection = new dataconnection();
            $database = $dataconnection->getsingledataconnection($dataconnectionid, true);

            $mainsql = sprintf((new sqltrans)::$sqlArr[$database['servertype']]['GET_TABLE_SCHEMA'], $database['db']);
            $value = json_encode(sqlhandler_ext::getsql_ext($database, $mainsql));

            break;

        case 'dataconnectionGetTableColumnList' :

            $tablename = $_POST['tablename'];
            $dataconnectionid = $_POST['dc'];

            if(!(new dataconnection())->availableDataconnection($dataconnectionid)) {$error = 1; $value = mlang_str('ERROR_DATACONNECTIONINACCESSIBLE',true); continue;}

            $dataconnection = new dataconnection();
            $database = $dataconnection->getsingledataconnection($dataconnectionid, true);

            $mainsql = sprintf((new sqltrans)::$sqlArr[$database['servertype']]['GET_TABLE_COLUMNS'], $database['db'], $tablename);
            $value = json_encode(sqlhandler_ext::getsql_ext($database, $mainsql));

            break;

        case 'dataconnectionMembersUpdate' :

            if(!has_permission('admindataconnections')){$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $vals = array();
            $result = (array) $_POST['tableVals'];
            $dataconnectionid = $result['id'];

            if(!(new dataconnection())->availableDataconnection($dataconnectionid)) {$error = 1; $value = mlang_str('ERROR_DATACONNECTIONINACCESSIBLE',true); continue;}

            if (array_key_exists('selectedusers', $result) ) {
                $arr = $result['selectedusers'];

                $newmember = new dataconnection_members();
                $newmember->deleteAllMembers($dataconnectionid);

                if ($arr == "-") { continue; }

                foreach ($arr as $user) {
                    $newmember->addMember($dataconnectionid,$user);
                }
            }
            break;

        case 'dataconnectionGetMembers' :

            if(!has_permission('admindataconnections')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $dataconnectionid = $_POST['dataconnection_id'];

            if(!(new dataconnection())->availableDataconnection($dataconnectionid)) {$error = 1; $value = mlang_str('ERROR_DATACONNECTIONINACCESSIBLE',true); continue;}

            $newdataconnection = new dataconnection_members();
            $members = $newdataconnection->getMembers($dataconnectionid);

            $cleanArray = array_column($members, 'userid');
            $cleanMembers = implode(',',$cleanArray);

            $value = json_encode(array('rmembers'=>$cleanMembers));

            break;

        case 'roleGetList' :

            if(!has_permission('adminroles')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $newrole = new roles();
            $value = json_encode($newrole->getrolelist());

            break;

        case 'roleGetInfo' :

            if(!has_permission('adminroles')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $resultid = $_POST['id'];

            $newrole = new role();
            $value = json_encode($newrole->getsinglerole($resultid));

            break;

        case 'roleAddEdit' :

            if(!has_permission('adminroles')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $result = (array) $_POST['tableVals'];
            $modalval = $result['id'];

            if(!empty($modalval)) {
                $newrole = new role();
                $newrole->updaterole($modalval,$result);
            }else{
                $newrole = new role();
                $insert_value = $newrole->createrole($result);
                $modalval = $insert_value->insert_id;
            }

            if (array_key_exists('selectedpermissions', $result) ) {

                $role = new role();
                $role->deleterolepermissions($modalval);

                $arr = $result['selectedpermissions'];

                if($arr == "-"){continue;}

                foreach ($arr as $permission) {
                    $role->addrolepermission($modalval,$permission);
                }
            }
            break;

        case 'roleMembersUpdate' :

            if(!has_permission('adminroles')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $vals = array();
            $result = (array) $_POST['tableVals'];
            $roleid = $result['id'];

            if (array_key_exists('selectedusers', $result) ) {
                $arr = $result['selectedusers'];

                $newmember = new role_members();
                $newmember->deleteAllMembers($roleid);

                if ($arr == "-") { continue; }

                foreach ($arr as $user) {
                    $newmember->addMember($roleid,$user );
                }
            }
            break;

        case 'roleDelete' :

            if(!has_permission('adminroles')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $roleid = $_POST['singleVal'];

            $delrolemembers = new role_members();
            $delrolemembers->deleteAllMembers($roleid);

            $delrole = new role();
            $delrole->deleterolepermissions($roleid);
            $delrole->deleterole($roleid);

            break;

        case 'roleGetPermissions' :

            if(!has_permission('adminroles')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $roleid = $_POST['role_id'];

            $newrole = new role();
            $value = json_encode($newrole->getpermissions($roleid));

            break;

        case 'roleGetMembers' :

            if(!has_permission('adminroles')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $roleid = $_POST['role_id'];

            $newrole = new role_members();
            $members = $newrole->getMembers($roleid);

            $cleanArray = array_column($members, 'userid');
            $cleanMembers = implode(',',$cleanArray);

            $value = json_encode(array('rmembers'=>$cleanMembers));

            break;

        case 'elementGetInfo' :
            $eleno = $_POST['eleno'];

            if($eleno ===''){return json_encode(array('error'=>'1')); }

            $elementinfo = new element();
            $value = $elementinfo->getsingleelement($eleno);

            $filterarray = array(
                'divwidth'=>'width',
                'divheight'=>'height',
                'divposx',
                'divposy',
                'border',
                'bordercolor'=>'bcolor',
                'borderarc'=>'barc',
                'bgcolor',
                'z_index'=>'zindex',
                'element_type',
                'element_id',
                'options'
            );

            $value = json_encode(utility::cleanArray($value,$filterarray));

            break;

        case 'elementGetDashboardElements' :

            // Check if we're asking for a particular dashboard. If so, check permission and availability - otherwise use the session dashboard
            if (isset($_POST['dashboard'])) {
                $db_no = $_POST['dashboard'];
                if(!(new dashboard())->availableDashboard($db_no)) {$error = 1; $value = mlang_str('ERROR_DASHBOARDINACCESSIBLE',true); continue;}
                $_SESSION['dashboard'] = $db_no;
            } else {
                $db_no = $_SESSION['dashboard'];
            }

            $elements = new element();
            $dashboardelements = (array) $elements->getdashboardelements($db_no);

            if( empty($dashboardelements[0]) ) {$value = ''; continue;}

            $filterarray = array(
                'id',
                'divwidth'=>'width',
                'divheight'=>'height',
                'divposx',
                'divposy',
                'border',
                'bordercolor'=>'bcolor',
                'borderarc'=>'barc',
                'bgcolor',
                'z_index'=>'zindex',
                'element_type',
                'element_id',
                'options'
            );

            foreach ($dashboardelements as $dashboardelement){
                $elementarray[] = utility::cleanArray($dashboardelement,$filterarray);
            }

            $value = !empty($elementarray) ? json_encode($elementarray) : '';

            break;

        case 'elementUpdateValues' :

            if(!has_permission('elementupdate')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}
            $elementArray = (array) $_POST['tableVals'];
            $elementId = $_POST['modalval'];

            $updateelement = new element();
            $value = json_encode($updateelement->updateElement($elementId, $elementArray));

            break;

        case 'elementDelete' :

            if(!has_permission('elementdelete')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}
            $resultid = $_POST['singleVal'];

            $element = new element();
            $value = json_encode($element->deleteElement($resultid));

            break;

        case 'colourGetList' :

            if(!has_permission('adminthemes')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $colourlist = new colour();
            $value = json_encode($colourlist->getFullList());

            break;

        case 'colourGetInfo' :

            if(!has_permission('adminthemes')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $colourid = $_POST['singleVal'];

            $coloursingle = new colour();
            $value = json_encode($coloursingle->getsinglecolour($colourid));

            break;

        case 'colourAddEdit' :

            if(!has_permission('adminthemes')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $result = (array) $_POST['tableVals'];
            $modalval = $_POST['modalval'];

            if(!empty($modalval) && $modalval > 0  ) {
                $newcolour = new colour();
                $newcolour->updatecolour($modalval,$result);
            }else{
                $newcolour = new colour();
                $insert_value = $newcolour->createcolour($result);
                $modalval = $insert_value->insert_id;
            }
            break;

        case 'colourDelete' :

            if(!has_permission('adminthemes')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}
            $colourid = $_POST['singleVal'];

            $colourlist = new colour();
            $value = json_encode($colourlist->deletecolour($colourid));

            break;

        case 'themesGetList' :

            $themelist = new theme();
            $value = json_encode($themelist->getFullList());

            break;

        case 'themeGetInfo':

            if(!has_permission('adminthemes')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $themeid = $_POST['singleVal'];

            $themesingle = new theme();
            $value = json_encode($themesingle->getsingletheme($themeid));

            break;

        case 'themeAddEdit' :

            if(!has_permission('adminthemes')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $result = (array) $_POST['tableVals'];
            $modalval = $_POST['singleVal'];

            if(!empty($modalval)) {
                $newtheme = new theme();
                $newtheme->updatetheme($modalval,$result);
            }else{
                $newtheme = new theme();
                $insert_value = $newtheme->createtheme($result);
                $modalval = $insert_value->insert_id;
            }

            if (array_key_exists('colours', $result) ) {

                $themecolours = new theme();
                $themecolours->deleteAllColours($modalval);

                $arr = $result['colours'];

                if($arr == "-"){continue;}

                $themecolours = new theme();

                foreach ($arr as $colour) {
                    $themecolours->addColour($modalval, $colour);
                }
            }
            break;

        case 'themeDelete' :

            if(!has_permission('adminthemes')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $themeid = $_POST['singleVal'];

            $newtheme = new theme();
            $newtheme->deletetheme($themeid);

            break;

        case 'themeGetColours' :

            if(!has_permission('adminthemes')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $themeid = $_POST['singleVal'];

            $themecolours = new theme();
            $value = json_encode( $themecolours->getThemeColours($themeid) );

            break;

        case 'htmlAddEdit' :

            if(!has_permission('addedithtml')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}
            $result = (array) $_POST['tableVals'];
            $modalval = $_POST['modalval'];

            if(!empty($modalval)) {

                $elementinfo = new element();
                $htmlblockid = $elementinfo->getsingleelement($modalval)['element_id'];

                $newblock = new htmlblock();
                $value = json_encode($newblock->updatehtmlblock($htmlblockid,$result));

            }else {
                  // Create block
                $newblock = new htmlblock();
                $value_q = $newblock->createhtmlblock($result);

                if ($value_q->result === -1) {
                    $value = "Error inserting into html table";
                    break;
                }

                // Create element
                $elementarray['element_type'] = 'html';
                $elementarray['element_id'] = $value_q->insert_id;
                $elementarray['created_by'] = $loginid;

                $newelement = new element();
                $value_z = $newelement->createElement($elementarray);

                if ($value_z->result === -1) {
                    $value = "Error inserting into elements table";
                    break;
                }

                // Add element to dashboard
                $newelementid = $value_z->insert_id;
                $currentdashboard = $_SESSION['dashboard'];

                $dashboardelement = new element();
                $dashboardelement->updateDashboardElement($newelementid, $currentdashboard);

                $value = $value_q;
            }
            break;

        case 'htmlGetInfo' :

            $elementid = $_POST['element_id'];

            $elementinfo = new element();
            $htmlblockid = $elementinfo->getsingleelement($elementid)['element_id'];

            $htmlblock = new htmlblock();
            $value = json_encode($htmlblock->getsinglehtml($htmlblockid));

            break;

        case 'chartGetInfo' :

            if(!has_permission('addeditchart')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}
            $ele_id = $_POST['ele_id'];

            $elementid = $_POST['ele_id'];

            $elementinfo = new element();
            $chartjsblockid = $elementinfo->getsingleelement($elementid)['element_id'];

            $chartjsblock = new chartjs();
            $value = json_encode($chartjsblock->getsinglechartjs($chartjsblockid));

            break;

        case 'chartAddEdit' :

            if(!has_permission('addeditchart')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $result = (array) $_POST['tableVals'];
            $modalval = $_POST['modalval'];

            if(!empty($modalval)) {

                $elementinfo = new element();
                $chartjsblockid = $elementinfo->getsingleelement($modalval)['element_id'];

                $newblock = new chartjs();
                $value = json_encode($newblock->updatechartjsblock($chartjsblockid,$result));
            }
            else{
                // Create block
                $newblock = new chartjs();
                $value_q = $newblock->createchartjsblock($result);

                if ($value_q  === false) {
                    $error = 1; $value = mlang_str('ERROR_UNKNOWN',true);
                    continue;
                }

                // Create element
                $elementarray['element_type'] = 'chartjs';
                $elementarray['element_id'] = $value_q->insert_id;
                $elementarray['created_by'] = $loginid;

                $newelement = new element();
                $value_z = $newelement->createElement($elementarray);

                if ($value_z->result === -1) {
                    $value = "Error inserting into elements table";
                    break;
                }

                // Add element to dashboard
                $newelementid = $value_z->insert_id;
                $currentdashboard = $_SESSION['dashboard'];

                $dashboardelement = new element();
                $dashboardelement->updateDashboardElement($newelementid, $currentdashboard);

                $value = $value_q;
            }
            break;

        case 'chartDraw' :

            $charno = $_POST['charno'];
            $tbvals = isset($_POST['tableVals']) ? $_POST['tableVals'] : null ;

            $chartjs = new chartjs();
            $value = json_encode($chartjs->buildChart($charno,$tbvals));

            break;

        case 'preferencesGetInfo' :

            if(!has_permission('adminpreferences')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $preferences = new preferences();
            $value =  json_encode($preferences->getAllPreferences());

            break;

        case 'preferencesUpdate' :

            if(!has_permission('adminpreferences')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $result = $_POST['tableVals'];

            $preferences = new preferences();
            $preferences->updateprefences($result);

            break;

        case 'permissionsGetList' :

            if(!has_permission('adminroles')) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $permissions = new permissions();
            $value = json_encode($permissions->getallpermissions());

            break;

        case 'licenseGet':
            $result = $_POST['license'];

            $licensetext = new licenses();
            $value = json_encode($licensetext->getsinglelicense($result));

            break;

        case 'loginas' :

            $userid = $_POST['tableVals']['selecteduser'];

            // Check if user does not have bypass, can not see userid, or is passing a nAn.
            if(!(has_permission('adminloginas') && (new user_filter())->availableUser($userid) ) ) {$error = 1; $value = mlang_str('ERROR_PERMISSION',true); continue;}

            $user = new user();
            $value = $user->getsingleuser($userid);

            $_SESSION['login_id']   = $value['id'];
            $_SESSION['login_user'] = $value['username'];

            $dashboard = new dashboard();
            $dashboardid = $dashboard->getuserdashboards($value['id'])[0]['id'];
            $_SESSION['dashboard']  = $dashboardid;

            break;
        }
    $data['error'] = $error;
    $data['value'] = $value;
}

echo isset($data) ? json_encode($data) : '';