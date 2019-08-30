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
 * File: dashboard_members.class.php
 * Last Modified: 8/24/19, 12:53 PM
 */

class dashboard_members
{

    public function __construct()
    {

    }

    function getMembers($dashboardid, $admin = false){
        $colarray = array('user_id');
        $wherearray[] = array('type'=>'i','name'=>'dashboard_id','value'=>$dashboardid);
        $wherearray[] = array('type'=>'i','name'=>'admin','value'=>$admin);
        $pre_sql = sqlhandler::parseReturnSQLfromarray('dashboards_assigned', $colarray, $wherearray);
        $value = sqlhandler::getsql($pre_sql,$wherearray);

        return $value;
    }


    function addMember($dashboardid,$userid,$admin = false){

        if(is_string($dashboardid) ){
            $newdashboard = new dashboard();
            $insertInfo[] = array('type'=>'i','name'=>'dashboard_id','value'=>$newdashboard->getsingledashboard($dashboardid)['id']);
        }
        else {
            $insertInfo[] = array('type'=>'i','name'=>'dashboard_id','value'=>$dashboardid);
        }

        if(is_string($userid) ){
            $user = new user();
            $insertInfo[] = array('type'=>'i','name'=>'user_id','value'=>$user->getsingleuser($userid)['id']);
        }
        else {
            $insertInfo[] = array('type'=>'i','name'=>'user_id','value'=>$userid);
        }
        $insertInfo[] = array('type'=>'i','name'=>'admin','value'=> +$admin);

        $pre_sql =  sqlhandler::parseInsertSQLfromarray('dashboards_assigned',$insertInfo);
        $value = sqlhandler::pushsql($pre_sql,$insertInfo);

        return $value;
    }

//    function deleteMember($dashboardid,$userid,$admin = false){
//        $wherearray[] = array('type'=>'i','name'=>'dashboard_id','value'=>$dashboardid);
//        $wherearray[] = array('type'=>'i','name'=>'user_id','value'=>$userid);
//        $wherearray[] = array('type'=>'i','name'=>'admin','value'=>$admin);
//
//        $pre_sql = parseDeleteSQLfromarrayP('dashboards_assigned', $wherearray);
//        $value = pushsqlP($pre_sql,null, $wherearray);
//        return $value;
//    }

    function deleteAllMembers($dashboardid,$admin = false){
        $wherearray[] = array('type'=>'i','name'=>'dashboard_id','value'=>$dashboardid);
        $wherearray[] = array('type'=>'i','name'=>'admin','value'=>$admin);
        $pre_sql = sqlhandler::parseDeleteSQLfromarray('dashboards_assigned', $wherearray);
        $value = sqlhandler::pushsql($pre_sql,null,$wherearray );
        return $value;
    }
}