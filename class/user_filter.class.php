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
 * File: user_filter.class.php
 * Last Modified: 8/24/19, 12:53 PM
 */

class user_filter
{
    function availableUser($userid)
    {
        if(has_permission('bypass_userscreening') || $userid == $_SESSION['login_id']){return true;}
        if(!is_int(intval($userid))){return false;}

        $members = $this->getGroupUserTree();
        $membersArray = array_column($members,'userid');

        $value = in_array($userid,$membersArray );

        return $value;
    }

    function getGroupUserTree(){
        $grouplist = new groups();
        $grouplist = $grouplist->getfilteredgrouplist();

        $groupids = array_column($grouplist,'id');

        $cleanArray = array_unique($groupids);
        asort($cleanArray);
        if(empty($cleanArray)) return '';

        // Return all group members that user can see
        $colarray = array('userid');

        $whereIn[] = array('type'=>'s','name'=>'groupid','value'=>implode(',', $cleanArray));

        $pre_sql = sqlhandler::parseReturnSQLIn('group_members', $colarray, $whereIn);
        $value = sqlhandler::getsql($pre_sql,$whereIn);

        return $value;
    }
}