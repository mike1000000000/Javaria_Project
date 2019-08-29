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
 * File: role.class.php
 * Last Modified: 8/24/19, 12:53 PM
 */

class role
{

    public function __construct()
    {

    }

    function getsinglerole($roleid){
        $colarray = array('id','name','notes');

        if(is_numeric($roleid)) {
            $wherearray[] = array('type'=>'i','name'=>'id','value'=>$roleid);
        } else {
            $wherearray[] = array('type'=>'s','name'=>'name','value'=>$roleid);
        }
        $wherearray[] = array('type'=>'i','name'=>'deleted','value'=>'0');

        $pre_sql = sqlhandler::parseReturnSQLfromarray('role_info', $colarray, $wherearray);
        $value =   sqlhandler::getsql($pre_sql,$wherearray)[0];

        return $value;
    }

    function createrole($roleinfo){
        $roleinfo = $this->cleanRoleArray($roleinfo,true);
        if(!$roleinfo)return (bool) false;

        $pre_sql = sqlhandler::parseInsertSQLfromarray('role_info', $roleinfo);
        $value = sqlhandler::pushsql($pre_sql,$roleinfo);

        return $value;
    }

    function updaterole($roleid,$roleinfo){
        $roleinfo = $this->cleanRoleArray($roleinfo,false);
        if(!$roleinfo)return (bool) false;
        $wherearray[] = array('type'=>'i','name'=>'id','value'=>$roleid);

        $sql_line = sqlhandler::parseUpdateSQLfromarray('role_info', $roleinfo,$wherearray);
        $value = sqlhandler::pushsql($sql_line,$roleinfo,$wherearray);

        return $value;
    }

    function deleterole($roleid){
        $updateInfo[] = array('type'=>'i','name'=>'deleted','value'=>'1');
        $wherearray[] = array('type'=>'i','name'=>'id','value'=>$roleid);

        $pre_sql = sqlhandler::parseUpdateSQLfromarray('role_info', $updateInfo, $wherearray);
        $mergedarrays = array_merge_recursive($updateInfo,$wherearray);
        $value = sqlhandler::pushsql($pre_sql,$mergedarrays);

        return $value;
    }

    function deleterolepermissions($roleid){
        $wherearray[] = array('type'=>'i','name'=>'roleid','value'=>$roleid);
        $pre_sql = sqlhandler::parseDeleteSQLfromarray('permissions_role', $wherearray);
        $value = sqlhandler::pushsql($pre_sql,null,$wherearray);
        return $value;
    }

    function addrolepermission($roleid, $permissionid){

        if(!is_numeric($permissionid)) {
            $testpermission = new permissions();
            $permissionid = $testpermission->getsinglepermission($permissionid)['id'];
        }

        $insertInfo[] = array('type'=>'i','name'=>'roleid','value'=>$roleid);
        $insertInfo[] = array('type'=>'i','name'=>'permissionid','value'=>$permissionid);

        $pre_sql = sqlhandler::parseInsertSQLfromarray('permissions_role', $insertInfo);
        $value = sqlhandler::pushsql($pre_sql,$insertInfo);

        return $value;
    }

    function getpermissions($roleid){
        $colarray = array('permissionid');
        $wherearray[] = array('type'=>'i','name'=>'roleid','value'=>$roleid);

        $pre_sql = sqlhandler::parseReturnSQLfromarray('permissions_role', $colarray, $wherearray);
        // We need to output a group_concat
        $pre_sql = str_replace('{permissions_role}.`permissionid`', 'group_concat({permissions_role}.permissionid separator \',\') as perms', $pre_sql);

        $value = sqlhandler::getsql($pre_sql,$wherearray)[0];
        return $value;
    }

    private function cleanRoleArray($userArray, $required = false){
        // Prepared Type, Alias/Name, Actual Name if different, Rules, Required
        $filterarray = array(
            array('s','rolename','name','/^([a-zA-Z0-9 ._]{3,26})$/',1),
            array('b','note','','',0),
        );
        return utility::cleanArrayP($userArray,$filterarray, $required);
    }
}