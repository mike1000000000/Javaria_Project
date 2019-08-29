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
 * File: dataconnection_members.class.php
 * Last Modified: 8/24/19, 1:02 PM
 */

class dataconnection_members
{

    function addMember($dataconnectionid, $userid)
    {
        $insertInfo[] = array('type'=>'i','name'=>'dataconnectionid','value'=>$dataconnectionid);
        $insertInfo[] = array('type'=>'i','name'=>'userid','value'=>$userid);

        $pre_sql = sqlhandler::parseInsertSQLfromarray('dataconnection_auths', $insertInfo);
        $value = sqlhandler::pushsql($pre_sql,$insertInfo);
        return $value;
    }

//    function deleteMember($dataconnectionid,$userid){
//        $wherearray[] = array('type'=>'i','name'=>'dataconnectionid','value'=>$dataconnectionid);
//        $wherearray[] = array('type'=>'i','name'=>'userid','value'=>$userid);
//        $pre_sql = parseDeleteSQLfromarrayP('dataconnection_auths', $wherearray );
//        $value = pushsqlP($pre_sql,null, $wherearray);
//        return $value;
//    }

    function deleteAllMembers($dataconnectionid){
        $wherearray[] = array('type'=>'i','name'=>'dataconnectionid','value'=>$dataconnectionid);
        $pre_sql = sqlhandler::parseDeleteSQLfromarray('dataconnection_auths', $wherearray);
        $value = sqlhandler::pushsql($pre_sql,null,$wherearray);
        return $value;
    }

    function getMembers($dataconnectionid){
        $colarray = array('userid');
        $wherearray[] = array('type'=>'i','name'=>'dataconnectionid','value'=>$dataconnectionid);
        $pre_sql = sqlhandler::parseReturnSQLfromarray('dataconnection_auths', $colarray, $wherearray);
        $value = sqlhandler::getsql($pre_sql,$wherearray);
        return $value;
    }
}