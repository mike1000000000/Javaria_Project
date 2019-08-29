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
 * File: session.php
 * Last Modified: 7/27/19, 8:31 PM
 */

include_once('loadclass.php');

   session_start();

   if(!isset($_SESSION['login_user'])){
     header("location:login.php");
     exit;
   }
   elseif (!empty($_SESSION)) {

        if(isset($_SESSION['login_user'])) {

            $usersession = new user();
            $row = $usersession->getsessioninfo($_SESSION['login_user']);

            $login_session = $row['username'];
            $login_id = $row['id'];

            if(isset($_SESSION['dashboard'])) $sessiondashboard = $_SESSION['dashboard'];

            if (!isset($sessiondashboard) || !(new dashboard())->availableDashboard($sessiondashboard) ) { $_SESSION['dashboard'] = $row['default_dashboard'];  }

            $_SESSION['login_id'] = $login_id;
        }
   }