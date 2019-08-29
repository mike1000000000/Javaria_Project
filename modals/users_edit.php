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
 * File: users_edit.php
 * Last Modified: 6/24/19, 9:36 PM
 */

$jlang_str['adduser_title'] = mlang_str('MODAL-USERS_ADDEDIT_TITLE_ADD',true);
$jlang_str['edituser_title'] = mlang_str('MODAL-USERS_ADDEDIT_TITLE_EDIT',true);
$jlang_str['profileuser_title'] = mlang_str('MODAL-USERS_ADDEDIT_TITLE_SELF',true);

echo call_user_func(function () {

    $modalwriter = new htmlwriter();

    $modalwriter->modalid        = 'modal_useredit';
    $modalwriter->labelsize      = 'col-md-3';
    $modalwriter->inputsize      = 'col-md-8';
    $modalwriter->title          = mlang_str('MODAL-USERS_ADDEDIT_TITLE_ADD', true);
    $modalwriter->footercancel   = mlang_str('CANCEL', true);
    $modalwriter->footeraccept   = mlang_str('SAVE', true);
    $modalwriter->modalsize      = 'modal-';
    $modalwriter->acceptfunction = 'userAddEdit';
    $modalwriter->acceptfunctioncontents = 'load_users();';
    $modalwriter->usenameattribs = true;
    $modalwriter->usemodalid = true;
    $modalwriter->includescript = '/modals_js/users_edit.js';

    $formcontent  = $modalwriter->createFormTextInput('MODAL-USERS_ADDEDIT_TXT_USERNAME','username', null, array('name'=>'name'));
    $formcontent .= $modalwriter->createFormTextInput('MODAL-USERS_ADDEDIT_TXT_FIRSTNAME','firstname', null, array('name'=>'firstname'));
    $formcontent .= $modalwriter->createFormTextInput('MODAL-USERS_ADDEDIT_TXT_LASTNAME','lastname', null, array('name'=>'lastname'));
    $formcontent .= $modalwriter->createFormTextInput('MODAL-USERS_ADDEDIT_TXT_PASSWORD','password',array() ,array('type' => 'password', 'name'=>'password'));
    $formcontent .= $modalwriter->createFormTextInput('MODAL-USERS_ADDEDIT_TXT_PHONENUMBER','phone_number', null, array('name'=>'phonenumber'));
    $formcontent .= $modalwriter->createFormTextInput('MODAL-USERS_ADDEDIT_TXT_EMAIL','email', null, array('name'=>'email'));
    $formcontent .= $modalwriter->createFormTextInput('MODAL-USERS_ADDEDIT_TXT_NOTES','note', array('tag'=>'textarea'), array('name'=>'note'));
    $formcontent .= $modalwriter->tag('input', $modalwriter->modalid . '__inp_' . 'group', '','', array('type'=>'hidden', 'name'=>'group') );

    $bodybegin  = $modalwriter->tag('div', '', 'col-md-18 centered', $formcontent);

    $modalwriter->createModal($buildpagebegin, $endcontent, $bodybegin);
    return $modalwriter->cleanupHTML($buildpagebegin . $endcontent);
});

