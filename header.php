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
 * File: header.php
 * Last Modified: 8/24/19, 1:50 PM
 */
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <meta name="robots" content="noarchive">

<title><?php mlang_str('PAGE_TITLE'); ?></title>

    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <link rel='shortcut icon' type='image/x-icon' href='/pictures/favicon.ico' />

    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/modal.css">

    <script src="bootstrap/js/bootstrap.min.js"></script>

<?php if($_SERVER['PHP_SELF'] !== '/login.php') : ?>

    <script type="text/javascript" src="/node_modules/chart.js/dist/Chart.bundle.js"></script>
    <script type="text/javascript" src="/node_modules/chart.js/samples/utils.js"></script>

    <script type="text/javascript" src="utilities/utilities.js"></script>
    <script type="text/javascript" src="utilities/dashboardutils.js"></script>

    <script src="jquery-ui-1.12.1/jquery-ui.js"></script>
    <script src="contextmenu/jquery.contextMenu.min.js"></script>
    <link href="contextmenu/jquery.contextMenu.css" rel="stylesheet" type="text/css" />

    <link href="jqmulti/css/multi-select.css" media="screen" rel="stylesheet" type="text/css">
    <script src="jqmulti/js/jquery.multi-select.js" type="text/javascript"></script>

    <script src="bootstrap/js/collapse.js"></script>

    <script src='spectrum/spectrum.js'></script>
    <link rel='stylesheet' href='spectrum/spectrum.css' />
<?php endif; ?>

</head>
