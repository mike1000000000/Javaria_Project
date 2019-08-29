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
 * File: login.php
 * Last Modified: 8/20/19, 9:09 PM
 */

$document_ready = '';
$error = '';

include("config.php");
include("htmlwriter.php");
include_once('loadclass.php');
?>
<!DOCTYPE HTML>
<html lang="<?php mlang_str('lang'); ?>">
<?php include('header.php')?>

<body>

<?php

session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // username and password sent from form
    $db = sqlhandler::connect();
    $myusername = mysqli_real_escape_string($db,$_POST['login']);
    $mypassword = mysqli_real_escape_string($db,$_POST['password']);
    $db = null;

    $logininfo = new login();

    if($logininfo->userlogin($myusername,$mypassword)){
        $_SESSION['login_user'] = $myusername;
        header("location: index.php");
    }else {
        $error = "Your Login Name or Password is invalid";
    }
}

echo call_user_func(function () use ($document_ready, $error) {
    $modalwriter = new htmlwriter();
    $modalwriter->modalid = 'modal_login';
    $modalwriter->title = mlang_str('MODAL-LOGIN_TITLE', true);
    $modalwriter->labelsize = 'col-md-4';
    $modalwriter->inputsize = 'col-md-7';
    $modalwriter->footeraccept = mlang_str('MODAL-LOGIN_BTN_LOGIN', true);
    $modalwriter->modalsize = 'modal-';
    $modalwriter->acceptcustomfunction = 'document.getElementById("loginform").submit();';
    $modalwriter->usenameattribs = true;

    $image = $modalwriter->tag('img', '', '', '',array('src'=>'pictures/datalight-analytics.png','style'=>'height: 120px; width: 120px;'  ));

    $info = $modalwriter->tag('div', '', 'h1', $image .   mlang_str('APP_NAME', true) );
    $info .= $modalwriter->newline();

    $formcontent = $modalwriter->tag('div', '', 'text-center col-md-18', $info);

    $formcontent .= $modalwriter->createFormTextInput('MODAL-LOGIN_TXT_LOGIN','login',null,array('name'=>'login'));
    $formcontent .= $modalwriter->createFormTextInput('MODAL-LOGIN_TXT_PASSWORD','password',null,array('name'=>'password', 'type'=>'password'));
    $formcontent .= $modalwriter->tag('div', '', 'col-md-10 text-right', $error);
    $bodybegin  = $modalwriter->tag('div', '', 'col-md-18 centered', $formcontent);

    $modalwriter->createModal($buildpagebegin, $endcontent, $bodybegin);

    $form  = $modalwriter->tag('form', 'loginform', 'form-horizontal', $buildpagebegin . $endcontent,array('method'=>'post' ));

    return $modalwriter->cleanupHTML($form);
});

?>

<script>
    $(document).ready(function() {
        $("#modal_login").modal('show');
    });

    $('#modal_login').on('hidden.bs.modal', function () {
        setTimeout(function(){$("#modal_login").modal('show');},2000);
    });

    <?php echo $document_ready . PHP_EOL ?>
</script>

</body>
</html>