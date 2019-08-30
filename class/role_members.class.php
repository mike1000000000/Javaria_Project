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
 * File: role_members.class.php
 * Last Modified: 8/24/19, 12:53 PM
 */

class role_members
{
    function addMember($roleid, $userid)
    {
        if(is_string($roleid) ){
            $newrole = new role();
            $rolemember[] = array('type'=>'i','name'=>'roleid','value'=>$newrole->getsinglerole($roleid)['id']);
        }
        else {
            $rolemember[] = array('type'=>'i','name'=>'roleid','value'=> $roleid);
        }

        if(is_string($userid) ){
            $user = new user();
            $rolemember[] = array('type'=>'i','name'=>'userid','value'=>$user->getsingleuser($userid)['id']);
        }
        else {
            $rolemember[] = array('type'=>'i','name'=>'userid','value'=>$userid);
        }
        $pre_sql = sqlhandler::parseInsertSQLfromarray('role_members', $rolemember);
        $value = sqlhandler::pushsql($pre_sql,$rolemember);

        return $value;
    }

//    function deleteMember($roleid,$userid){
//        $rolearray['roleid'] = $roleid;
//        $rolearray['userid'] = $userid;
//        $pre_sql = parseDeleteSQLfromarray('role_members', $rolearray);
//        $value = pushsql($pre_sql);
//        return $value;
//    }

    function deleteAllMembers($roleid){
        $wherearray[] = array('type'=>'i','name'=>'roleid','value'=>$roleid);
        $pre_sql = sqlhandler::parseDeleteSQLfromarray('role_members', $wherearray);
        $value = sqlhandler::pushsql($pre_sql,null, $wherearray);
        return $value;
    }

    function getMembers($roleid){
        $colarray = array('userid');
        $wherearray[] = array('type'=>'i','name'=>'roleid','value'=>$roleid);
        $pre_sql = sqlhandler::parseReturnSQLfromarray('role_members', $colarray, $wherearray);
        $value = sqlhandler::getsql($pre_sql,$wherearray);
        return $value;
    }
}