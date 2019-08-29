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
 * File: dashboard.class.php
 * Last Modified: 8/24/19, 12:53 PM
 */

class dashboard
{
    public function __construct() { }

    function availableDashboard($dashboardid)
    {
        if(!is_int(intval($dashboardid))){return false;}

        // Does the dashboard exist?
        $colarray = array('id');
        $wherearray[] = array('type'=>'i','name'=>'id','value'=>$dashboardid);

        $pre_sql = sqlhandler::parseReturnSQLfromarray('dashboards', $colarray, $wherearray);
        $value = sqlhandler::getsql($pre_sql,$wherearray);
        if(!$value){return false;}

        if (has_permission('bypass_dashboardscreening') ){return true;}

        $dashboards = $this->getfiltereddashboardlist();
        $dashboardsArray = array_column($dashboards,'id');

        $value = in_array($dashboardid,$dashboardsArray );

        return $value;
    }

    // Get only the accessible dashboard
    function getfiltereddashboardlist(){

        $loginid = $_SESSION['login_id'];

        if (has_permission('bypass_dashboardscreening') ){
            $colarray = array('id');
            $pre_sql = sqlhandler::parseReturnSQLfromarray('dashboards', $colarray);
            $dashboards = sqlhandler::getsql($pre_sql);
            $dashboardArray = array_column($dashboards,'id');
        }
        else {
            // Get all dashboards user is admin of
            $colarray = array('dashboard_id');

            $wherearray[] = array('type'=>'i','name'=>'admin','value'=>'1');
            $wherearray[] = array('type'=>'i','name'=>'user_id','value'=>$loginid);

            $pre_sql = sqlhandler::parseReturnSQLfromarray('dashboards_assigned', $colarray, $wherearray);

            $dashboards = sqlhandler::getsql($pre_sql,$wherearray);
            $dashboardArray = array_column($dashboards,'dashboard_id');
        }
        $adminArray = array_unique($dashboardArray);
        asort($adminArray);

        // Get all dashboards user is not an admin of
        $colarray = array('dashboard_id');
        $wherearray1[] = array('type'=>'i','name'=>'admin','value'=>'0');
        $wherearray1[] = array('type'=>'i','name'=>'user_id','value'=>$loginid);
        $pre_sql = sqlhandler::parseReturnSQLfromarray('dashboards_assigned', $colarray, $wherearray1);
        $dashboards = sqlhandler::getsql($pre_sql,$wherearray1);

        // Merge both arrays and cleanup - return if no dashboard found
        $userArray = array_column($dashboards,'dashboard_id');
        $mergedarrays = array_merge_recursive($adminArray,$userArray);
        $dashboardArray = array_unique($mergedarrays);
        asort($dashboardArray);
        if(empty($dashboardArray)) return '';

        // Return all dashboards that a user can see
        $colarray = array('id','name');
        $whereIn[] = array('type'=>'s','name'=>'id','value'=>implode(',', $dashboardArray));
        $pre_sql = sqlhandler::parseReturnSQLIn('dashboards',$colarray,$whereIn);
        $value = sqlhandler::getsql($pre_sql,$whereIn);
        return $value;
    }


    function getuserdashboards($userid = null){

        $loginid = isset($userid) ? $userid : $_SESSION['login_id'] ;

        // Get all dashboards user is assigned
        $colarray = array('dashboard_id');
        $wherearray[] = array('type'=>'i','name'=>'user_id','value'=>$loginid);

        $pre_sql = sqlhandler::parseReturnSQLfromarray('dashboards_assigned', $colarray, $wherearray);
        $dashboards = sqlhandler::getsql($pre_sql,$wherearray);
        $dashboardArray = array_column($dashboards,'dashboard_id');

        $cleanArray = array_unique($dashboardArray);
        asort($cleanArray);

        if(empty($cleanArray)) {
            $message = 'No dashboard for user '. $loginid;
            (new log())->write_log($message);
            return '0';
        }
        // Return all dashboards that a user can see
        $colarray = array('id','name');

        $whereIn[] = array('type'=>'s','name'=>'id','value'=>implode(',', $cleanArray));
        $pre_sql = sqlhandler::parseReturnSQLIn('dashboards', $colarray, $whereIn);
        $value = sqlhandler::getsql($pre_sql,$whereIn);

        return $value;
    }


    function getsingledashboard($dashboardid){

        $colarray = array('id','name','options','user_id');

        if(is_numeric($dashboardid)) {
            $wherearray[] = array('type'=>'i','name'=>'id','value'=>$dashboardid);
        } else {
            $wherearray[] = array('type'=>'s','name'=>'name','value'=>$dashboardid);
        }
        $pre_sql = sqlhandler::parseReturnSQLfromarray('dashboards', $colarray, $wherearray);
        $value =   sqlhandler::getsql($pre_sql,$wherearray)[0];

        return $value;
    }


    function createdashboard($dashboardinfo){

        $dashboardinfo = $this->cleanDashboardArrayP($dashboardinfo,true);
        if(!$dashboardinfo)return (bool) false;

        $arrayinfo = array_column($dashboardinfo, 'value','name');

        // Create Dashboard
        $insertInfo[] = array('type'=>'s','name'=>'name','value'=>$arrayinfo['name']);
        $insertInfo[] = array('type'=>'i','name'=>'user_id','value'=>$arrayinfo['user_id']);

        // Save all dashboard options here in JSON
        $options['bgcolor'] = $arrayinfo['colour'] ?? '';
        $insertInfo[] = array('type'=>'s','name'=>'options','value'=>json_encode($options));

        $pre_sql = sqlhandler::parseInsertSQLfromarray('dashboards', $insertInfo);
        $value_q = sqlhandler::pushsql($pre_sql,$insertInfo);

        // Make creator dashboard admin
        $insertInfo2[] = array('type'=>'i','name'=>'dashboard_id','value'=>$value_q->insert_id);
        $insertInfo2[] = array('type'=>'i','name'=>'user_id','value'=>$arrayinfo['user_id']);
        $insertInfo2[] = array('type'=>'i','name'=>'admin','value'=>'1');
        $pre_sql = sqlhandler::parseInsertSQLfromarray('dashboards_assigned', $insertInfo2);
        $value = sqlhandler::pushsql($pre_sql,$insertInfo2);

        $value = $value_q;

        return $value;
    }


    function updatedashboard($dashboardid,$dashboardinfo){

        $dashboardinfo = $this->cleandashboardarrayP($dashboardinfo,false);
        if(!$dashboardinfo)return (bool) false;

        $arrayinfo = array_column($dashboardinfo, 'value','name');

        $insertInfo[] = array('type'=>'s','name'=>'name','value'=>$arrayinfo['name']);
        $insertInfo[] = array('type'=>'i','name'=>'user_id','value'=>$arrayinfo['user_id']);

        // Save all dashboard options here in JSON
        $options['bgcolor'] = $arrayinfo['colour'];
        $insertInfo[] = array('type'=>'s','name'=>'options','value'=>json_encode($options));

        $wherearray[] = array('type'=>'i','name'=>'id','value'=>$dashboardid);

        $sql_line = sqlhandler::parseUpdateSQLfromarray('dashboards', $insertInfo,$wherearray);
        $value = sqlhandler::pushsql($sql_line,$insertInfo,$wherearray);

        return $value;
    }

    function deletedashboard($dashboardid){
        // Delete dashboard elements
        $element = new element();
        $dashboardelements = $element->getdashboardelements($dashboardid);
        foreach ($dashboardelements as $dashboardelement) {
            $element->deleteElement($dashboardelement['id']);
        }

        $wherearray[] = array('type'=>'i','name'=>'id','value'=>$dashboardid);
        $pre_sql = sqlhandler::parseDeleteSQLfromarray('dashboards', $wherearray);
        $value = sqlhandler::pushsql($pre_sql,null,$wherearray);
        return $value;
    }

    private function cleanDashboardArrayP($vals,$required){

        $filterarray = array(
            array('s','name','','/^([a-zA-Z0-9 ._]{1,26})$/',1),
            array('s','colour','','',0),
            array('s','creator','user_id','',0)
        );
        return utility::cleanArrayP($vals,$filterarray,$required);
    }
}