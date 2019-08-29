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
 * File: user.class.php
 * Last Modified: 8/24/19, 12:53 PM
 */

class user
{

    public function __construct()
    {

    }

    function getsingleuser($userid){
        $colarray = array('id','username', 'firstname', 'lastname', 'phone_number', 'email', 'user_p', 'note','default_dashboard');

        if(is_numeric($userid)) {
            $wherearray[] = array('type'=>'i','name'=>'id','value'=>$userid);
        } else {
            $wherearray[] = array('type'=>'s','name'=>'username','value'=>$userid);
        }

        $wherearray[] = array('type'=>'i','name'=>'deleted','value'=>'0');

        $pre_sql = sqlhandler::parseReturnSQLfromarray('users', $colarray, $wherearray);
        $value =   sqlhandler::getsql($pre_sql,$wherearray)[0];

        // no password send
        utility::changekey($value, 'user_p','password');
        $value['password'] = str_repeat('•', 8 );

        return $value;
    }

    function createuser($userinfo){
        $userinfo = $this->cleanUserArray($userinfo,true);
        if(!$userinfo)return (bool) false;

        $key = array_search('user_p', array_column($userinfo, 'name'));
        $options = array('cost'=>12);
        $userinfo[$key]['value'] = password_hash($userinfo[$key]['value'],PASSWORD_BCRYPT, $options);

        $pre_sql = sqlhandler::parseInsertSQLfromarray('users', $userinfo);

        $value = sqlhandler::pushsql($pre_sql, $userinfo);

        // Assign default dashboard to ALL users
        if ($value->insert_id > 0) {
            $defaultdb = new preferences();
            $default = $defaultdb->getPreference('default_dashboard');

            if(!empty($default)) {
                $dbmember = new dashboard_members();
                $dbmember->addMember((int)$default, $value->insert_id);
            }
        }
        return $value;
    }

    function updateuser($userid,$userinfo){
        $userinfo = $this->cleanUserArray($userinfo);
        if(!$userinfo)return (bool) false;

        // No editing the same username or empty
        $key = array_search('username', array_column($userinfo, 'name'));
        if($key !== false && ($userid === $_SESSION['login_id'] || $userinfo[$key]['value'] == '' )){unset($userinfo[$key]);}

        // Remove password if not changed
        $key = array_search('user_p', array_column($userinfo, 'name'));
        if($key !== false && (str_replace("•", "", $userinfo[$key]['value']) === "" )){unset($userinfo[$key]);}

        $wherearray[] = array('type'=>'i','name'=>'id','value'=>$userid);
        $wherearray[] = array('type'=>'i','name'=>'deleted','value'=>'0');

        $sql_line = sqlhandler::parseUpdateSQLfromarray('users', $userinfo, $wherearray);
        $fullpreparedarray = array_merge(array_values($userinfo), array_values($wherearray));
        $value = sqlhandler::pushsql($sql_line,$fullpreparedarray);

        return $value;
    }

    function deleteuser($userid){
        $userinfo = array('deleted'=>'1');
        $value = self::updateuser($userid, $userinfo);

        return $value;
    }

    function getsessioninfo($login_user){
        $colarray = array('id','username', 'default_dashboard');

        $wherearray[] = array('type'=>'s','name'=>'username','value'=>$login_user);
        $wherearray[] = array('type'=>'i','name'=>'deleted','value'=>'0');

        $pre_sql = sqlhandler::parseReturnSQLfromarray('users', $colarray, $wherearray);
        $value =   sqlhandler::getsql($pre_sql,$wherearray)[0];

        $_SESSION['login_id']= $value['id'];

        $dboard = $value['default_dashboard'];

        if(is_null($dboard) || !(new dashboard())->availableDashboard($dboard)){
            $dashbs = new dashboard();
            $value['default_dashboard']  = $dashbs->getuserdashboards($value['id'])[0]['id'];
        }

        return $value;
    }

    /**
     * Take user input and push it through a filter that also manages the prepared statement arrays
     *
     * @param $userArray - Array to be filtered
     * @param bool $required - Do we respect the required call
     * @return array|bool - return filtered array or false when failed
     */
    private function cleanUserArray($userArray, $required = false){
        // Clean user array
        // Prepared Type, Alias/Name, Actual Name if different, Rules, Required
        $filterarray = array(
            array('s','name','username','/^([a-zA-Z0-9._]{5,16})$/',1),
            array('s','firstname','','',1),
            array('s','lastname','','',1),
            array('s','password','user_p','/^[a-zA-Z0-9._]{5,10}$/',1),
            array('s','phonenumber','phone_number','',0),
            array('s','email','','',0),
            array('i','default_dashboard','','',0),
            array('s','note','','',0),
            array('i','deleted','','',0)
        );
        return utility::cleanArrayP($userArray,$filterarray, $required);
    }
}