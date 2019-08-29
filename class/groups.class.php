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
 * File: groups.class.php
 * Last Modified: 8/24/19, 12:53 PM
 */

class groups
{
    public function __construct()
    {

    }

    function availableGroup($groupid)
    {
        if (has_permission('bypass_groupscreening') ){return true;}
        if(!is_int(intval($groupid))){return false;}

        $groups = $this->getfilteredgrouplist();
        $groupsArray = array_column($groups,'id');

        $value = in_array($groupid,$groupsArray );

        return $value;
    }

    // Get only the accessible groups from the hierarchy
    function getfilteredgrouplist(){

        if (has_permission('bypass_groupscreening') ){
            $colarray = array('id', 'name');
            $wherearray[] = array('type'=>'i','name'=>'deleted','value'=>'0');
            $pre_sql = sqlhandler::parseReturnSQLfromarray('group_info', $colarray, $wherearray);
            $value = sqlhandler::getsql($pre_sql,$wherearray);
        }
        else {
            $loginid = $_SESSION['login_id'];

            // Get all groups user is admin of
            $colarray = array('groupid');
            $wherearray[] = array('type'=>'i','name'=>'groupadmin','value'=>'1');
            $wherearray[] = array('type'=>'i','name'=>'userid','value'=>$loginid);

            $pre_sql = sqlhandler::parseReturnSQLfromarray('group_members', $colarray, $wherearray);
            $groups = sqlhandler::getsql($pre_sql,$wherearray);

            $accessibleGroups = '';

            // Get group hierarchy
            foreach ($groups as $group) {
                if ($accessibleGroups !== '') {
                    $accessibleGroups .= ',';
                }
                $accessibleGroups .= $this->getGroupTree($group['groupid']);
            }

            $cleanArray = array_unique(explode(',', $accessibleGroups));
            asort($cleanArray);
            if(empty($cleanArray)) return '';

            // Return all group info that user can see
            $colarray = array('id', 'name');

            $whereIn[]     = array('type'=>'s','name'=>'id','value'=>implode(',', $cleanArray));
            $wherearray2[] = array('type'=>'i','name'=>'deleted','value'=>'0');

            $pre_sql = sqlhandler::parseReturnSQLIn('group_info', $colarray, $whereIn, $wherearray2);
            $mergedarrays = array_merge_recursive( $whereIn , $wherearray2);
            $value = sqlhandler::getsql($pre_sql,$mergedarrays);
        }
        return $value;
    }

    function getGroupTree($groupid){
        $groups = $groupid;

        //first get child groups
        $colarray = array('id');
        $wherearray[] = array('type'=>'i','name'=>'parentid','value'=>$groupid);
        $pre_sql = sqlhandler::parseReturnSQLfromarray('group_info', $colarray, $wherearray);
        $children = sqlhandler::getsql($pre_sql,$wherearray);

        if (!empty($children)){
            foreach ($children as $child) {
                $childgroups = $this->getGroupTree($child['id']) ;

                if($childgroups !== ''){
                    $groups .= ',';
                    $groups .= $childgroups;
                }
            }
        }
        return $groups;
    }
}