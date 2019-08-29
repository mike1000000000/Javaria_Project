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
 * File: permissions.class.php
 * Last Modified: 8/24/19, 12:53 PM
 */

class permissions
{
    public function __construct()
    {

    }

    function getDefaultPreference($preference){
        $defaultuserrole = new preferences();
        $pref = $defaultuserrole->getPreference($preference);

        if(!empty($pref)){
            return $pref;
        }
        return false;
    }

    function getsinglepermission($permissionid){

        $colarray = array('id','name','description');

        if(is_numeric($permissionid)) {
            $wherearray[] = array('type'=>'i','name'=>'id','value'=>$permissionid);
        } else {
            $wherearray[] = array('type'=>'s','name'=>'name','value'=>$permissionid);
        }

        $pre_sql = sqlhandler::parseReturnSQLfromarray('permissions', $colarray, $wherearray);
        $value = sqlhandler::getsql($pre_sql,$wherearray)[0];

        return $value;
    }

    function has_permission($permissionid){
        global  $CFG;
        $value = false;

        if(!is_numeric($permissionid)) {
            $permissionid = $this->getsinglepermission($permissionid)['id'];
        }

        if(isset($_SESSION['login_id'])){
            $id = $_SESSION['login_id'];
        }else{
            return false;
        }

        if(isset($CFG->adminid) && $id == $CFG->adminid){return true;}

        // Test permission against default role - everyone gets this profile as per preferences if set
        $colarray = array('permissionid');

        $defaultroleid = $this->getDefaultPreference('default_userrole');

        $wherearray[] = array('type'=>'i','name'=>'roleid','value'=>$defaultroleid);
        $wherearray[] = array('type'=>'i','name'=>'permissionid','value'=>$permissionid);

        $pre_sql = sqlhandler::parseReturnSQLfromarray('permissions_role',$colarray,$wherearray);
        if(sqlhandler::getsql($pre_sql,$wherearray)) {$value = true;};

        // Test permission against admin role - this role is assigned to group admins as per preferences
        $defaultadminroleid = $this->getDefaultPreference('default_adminrole');

        if(!empty($defaultadminroleid) ) {

            $wherearray[] = array('type' => 'i', 'name' => 'roleid', 'value' => $defaultadminroleid);

            $pre_sql = sqlhandler::parseReturnSQLfromarray('permissions_role', $colarray, $wherearray);
            if (sqlhandler::getsql($pre_sql, $wherearray)) {
                $value = true;
            };
        }
        // Test permission against any other roles
        $colarray = array('roleid');
        $wherearray2[] = array('type'=>'i','name'=>'userid','value'=>$id);
        $pre_sql = sqlhandler::parseReturnSQLfromarray('role_members',$colarray,$wherearray2);
        $roles = sqlhandler::getsql($pre_sql,$wherearray2);

        foreach ($roles as $role){
            $colarray = array('permissionid');
            $wherearray3[] = array('type'=>'i','name'=>'roleid','value'=>$role);
            $wherearray3[] = array('type'=>'i','name'=>'permissionid','value'=>$permissionid);

            $pre_sql = sqlhandler::parseReturnSQLfromarray('permissions_role',$colarray,$wherearray3);
            if(sqlhandler::getsql($pre_sql,$wherearray3)) {
                $value = true;
                break;
            };
        }
        return $value;
    }

    function getallpermissions(){
        $colarray = array('id','name','description');
        $pre_sql = sqlhandler::parseReturnSQLfromarray('permissions',$colarray);

        return sqlhandler::getsql($pre_sql);
    }
}