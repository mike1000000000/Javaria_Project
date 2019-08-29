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
 * File: groups_edit.php
 * Last Modified: 8/24/19, 12:53 PM
 */

echo call_user_func(function () {
    $modalwriter = new htmlwriter();
    $modalwriter->modalid        = 'modal_groupsedit';
    $modalwriter->labelsize      = 'col-md-4';
    $modalwriter->inputsize      = 'col-md-6';
    $modalwriter->title          = mlang_str('MODAL-GROUPS_ADDEDIT_TITLE', true);
    $modalwriter->footercancel   = mlang_str('CANCEL', true);
    $modalwriter->footeraccept   = mlang_str('SAVE', true);
    $modalwriter->usenameattribs = true;
    $modalwriter->modalsize      = 'modal-';
    $modalwriter->acceptfunction = 'groupAddEdit';
    $modalwriter->usemodalid = true;
    $modalwriter->acceptfunctioncontents = 'load_groups();';
    $modalwriter->includescript = '/modals_js/groups_edit.js';

    $formcontent  = $modalwriter->createFormTextInput('MODAL-GROUPS_ADDEDIT_TXT_NAME','name',null,array('name'=>'name'));
    $formcontent  .= $modalwriter->createFormTextInput('MODAL-GROUPS_ADDEDIT_TXT_NOTES','note', array('tag'=>'textarea'),array('name'=>'notes'));

    $formcontent .= $modalwriter->createFormSelectBasic('MODAL-GROUPS_ADDEDIT_SELECT_PARENT','parent', null,array('name'=>'parent'),'static' );

    $bodybegin  = $modalwriter->tag('div', '', 'col-md-18 centered', $formcontent);

    $modalwriter->createModal($buildpagebegin, $endcontent, $bodybegin,'modal-child');
    return $modalwriter->cleanupHTML($buildpagebegin . $endcontent);
});
