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
 * File: group.class.php
 * Last Modified: 8/24/19, 12:53 PM
 */

class group
{
    public function __construct()
    {

    }

    function getsinglegroup($groupid){

        $colarray = array('id','name','notes', 'parentid');

        if(is_numeric($groupid)) {
            $wherearray[] = array('type'=>'i','name'=>'id','value'=>$groupid);
        } else {
            $wherearray[] = array('type'=>'s','name'=>'name','value'=>$groupid);
        }
        $wherearray[] = array('type'=>'i','name'=>'deleted','value'=>'0');

        $pre_sql = sqlhandler::parseReturnSQLfromarray('group_info', $colarray, $wherearray);
        $value =   sqlhandler::getsql($pre_sql,$wherearray)[0];

        return $value;
    }

    function creategroup($groupinfo){

        $groupinfo = $this->cleanGroupArrayP($groupinfo,true);
        if(!$groupinfo)return (bool) false;

        $pre_sql = sqlhandler::parseInsertSQLfromarray('group_info', $groupinfo);
        $value = sqlhandler::pushsql($pre_sql,$groupinfo);

        return $value;
    }

    function updategroup($groupid,$groupinfo){

        $groupinfo = $this->cleanGroupArrayP($groupinfo,false);
        if(!$groupinfo)return (bool) false;

        // No parent id should equal the groud id
        $vals = array_column($groupinfo,'value','name');
        if($vals['parentid'] === $groupid) { return false; }

        $wherearray[] = array('type'=>'i','name'=>'id','value'=>$groupid);

        $sql_line = sqlhandler::parseUpdateSQLfromarray('group_info', $groupinfo, $wherearray);
        $value = sqlhandler::pushsql($sql_line,$groupinfo,$wherearray);

        return $value;
    }

    function deletegroup($groupid){
        // Set group to deleted
        $updateInfo[] = array('type'=>'i','name'=>'deleted','value'=>'1');
        $wherearray[] = array('type'=>'i','name'=>'id','value'=>$groupid);

        $pre_sql = sqlhandler::parseUpdateSQLfromarray('group_info', $updateInfo, $wherearray);
        $mergedarrays = array_merge_recursive($updateInfo,$wherearray);
        $value = sqlhandler::pushsql($pre_sql,$mergedarrays);
        return $value;
    }

    private function cleanGroupArrayP($groupArray,$required){
        // Clean group array
        $filterarray = array(
            array('s','name','','/^([a-zA-Z0-9._ ]{1,16})$/',1),
            array('i','parent','parentid','',0),
            array('b','notes','','',0)
        );
        return utility::cleanArrayP($groupArray,$filterarray,$required);
    }
}