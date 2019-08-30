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
 * File: users.php
 * Last Modified: 8/24/19, 12:53 PM
 */

$jlang_str['userdel_title'] = mlang_str('MODAL-USERS_DELETE_TITLE',true);
$jlang_str['userdel_text'] = mlang_str('MODAL-USERS_DELETE_TEXT',true);

echo call_user_func(function () {

    $modalwriter = new htmlwriter();
    $modalwriter->modalid        = 'modal_users';
    $modalwriter->labelsize      = 'col-md-4';
    $modalwriter->inputsize      = 'col-md-6';
    $modalwriter->title          = mlang_str('MODAL-USERS_MANAGE_TITLE', true);
    $modalwriter->footercancel   = mlang_str('CLOSE', true);

    $modalwriter->modalsize      = 'modal-lg';
    $modalwriter->acceptcustomfunction = '';
    $modalwriter->includescript = '/modals_js/users.js';

    $groupselect = $modalwriter->createFormSelectBasic('MODAL-USERS_MANAGE_SELECT_GROUPS','group', null,null,'static text-right', array('inputsize'=>'col-md-4', 'labelsize'=>'col-md-8'));

    $rowdiv = $modalwriter->tag('div', '', 'col-md-10 form-group pull-right', $groupselect, array('style'=>'padding-right:30px;'));
    $formcontent = $modalwriter->tag('div', '', 'row', $rowdiv);

    $label1 = $modalwriter->createLabel('MODAL-USERS_MANAGE_LBL_USERNAME', '', array('labelsize'=>''), array('style'=>'margin-left:25px; text-align:left;'));
    $label2 = $modalwriter->createLabel('MODAL-USERS_MANAGE_LBL_FULLNAME', '', array('labelsize'=>''), array('style'=>'margin-left:15px; text-align:left;'));
    $label3 = $modalwriter->createLabel('MODAL-USERS_MANAGE_LBL_EMAIL', '', array('labelsize'=>''), array('style'=>'margin-left:80px; text-align:left;'));

    $col1div = $modalwriter->tag('div', '', 'col-md-2 ', $label1);
    $col2div = $modalwriter->tag('div', '', 'col-md-2 ', $label2);
    $col3div = $modalwriter->tag('div', '', 'col-md-4 ', $label3);

    $labels = $modalwriter->tag('div', '', 'row ', $col1div . $col2div . $col3div);

    $selectbox = $modalwriter->createUserlist('userlist','form-control col-md-12 userlist', array( 'style'=>'height: 300px;'));

    $labelandselect = $modalwriter->tag('div', '', 'col', $labels . $selectbox, array( 'style'=>'margin-left: 15px;margin-right: 15px;')  );

    $formcontent .= $modalwriter->tag('div', '', 'row',   $labelandselect );

    $button = $modalwriter->createButton('PLUS','add', 'btn btn-primary pull-right', array('style'=>'width:35px; margin-right:15px;','disabled'=>''),'showModal(\'modal_useredit\',this, \'\', {\'group\':$(\'#modal_users__sel_group\').val()} );' );
    $formcontent .= $modalwriter->tag('div', '', 'row',   $button );

    $bodybegin  = $modalwriter->tag('div', '', 'col-md-12 centered', $formcontent);

    $modalwriter->createModal($buildpagebegin, $endcontent, $bodybegin,'modal-child');
    return $modalwriter->cleanupHTML($buildpagebegin . $endcontent);

});