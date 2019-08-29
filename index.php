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
 * File: index.php
 * Last Modified: 8/20/19, 9:04 PM
 */

$document_ready = '';
$jlang_str = [];
$java_scripts[''] = '';

include("config.php");
include('htmlwriter.php');
include('session.php');
?>
<!DOCTYPE HTML>
<html lang="<?php mlang_str('lang'); ?>">
<?php include('header.php')?>

<body onresize='resizecontentheight()'>

<?php
    include('navbar.php');
    include('modals.php');
    include('jlang.php');
?>
<!--body-->
    <div id="content" class="container-fluid" >
        <div class="row text-center"  >
            <div id="middle-block"  class="col-md-16 center-block">
            </div>
        </div>
    </div>

<?php include('footer.php'); ?>

</body >
</html>