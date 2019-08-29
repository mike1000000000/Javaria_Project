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
 * File: group_members.class.php
 * Last Modified: 8/24/19, 12:53 PM
 */

class group_members
{

    public function __construct()
    {

    }

    function getMembers($groupid, $admin = false){

        $colarray = array('userid');
        $wherearray[] = array('type'=>'i','name'=>'groupid','value'=>$groupid);
        $wherearray[] = array('type'=>'i','name'=>'groupadmin','value'=>$admin);

        $pre_sql = sqlhandler::parseReturnSQLfromarray('group_members', $colarray, $wherearray);
        $value = sqlhandler::getsql($pre_sql,$wherearray);

        return $value;
    }

    function addMember($groupid,$userid,$admin = false){

        if(is_string($groupid) ){
            $newgroup = new group();
            $insertInfo[] = array('type'=>'i','name'=>'groupid','value'=>$newgroup->getsinglegroup($groupid)['id']);
        }
        else {
            $insertInfo[] = array('type'=>'i','name'=>'groupid','value'=>$groupid);
        }

        if(is_string($userid) ){
            $user = new user();
            $gmarray['userid'] =  $user->getsingleuser($userid)['id'] ;
            $insertInfo[] = array('type'=>'i','name'=>'userid','value'=>$user->getsingleuser($userid)['id']);
        }
        else {
            $insertInfo[] = array('type'=>'i','name'=>'userid','value'=>$userid);
        }

        $gmarray['groupadmin'] = (int)$admin;

        $insertInfo[] = array('type'=>'i','name'=>'groupadmin','value'=>(int)$admin);

        $pre_sql =  sqlhandler::parseInsertSQLfromarray('group_members',$insertInfo);
        $value = sqlhandler::pushsql($pre_sql,$insertInfo);

        return $value;
    }

//    function deleteMember($groupdid,$userid,$admin = false){
//        $gmarray['groupid'] = $groupdid;
//        $gmarray['userid'] = $userid;
//        $gmarray['groupadmin'] = $admin;
//        $pre_sql = parseDeleteSQLfromarray('group_members', $gmarray);
//        $value = pushsql($pre_sql);
//        return $value;
//    }

    function deleteAllMembers($groupdid,$admin = false){
        $wherearray[] = array('type'=>'i','name'=>'groupid','value'=>$groupdid);
        $wherearray[] = array('type'=>'i','name'=>'groupadmin','value'=>$admin);
        $pre_sql = sqlhandler::parseDeleteSQLfromarray('group_members', $wherearray);
        $value = sqlhandler::pushsql($pre_sql,null,$wherearray);
        return $value;
    }
}