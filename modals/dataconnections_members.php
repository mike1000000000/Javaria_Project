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
 * File: dataconnections_members.php
 * Last Modified: 8/24/19, 12:53 PM
 */

echo call_user_func(function () {
    $modalwriter = new htmlwriter();
    $modalwriter->modalid        = 'modal_dataconnectionsmembers';
    $modalwriter->labelsize      = 'col-md-4';
    $modalwriter->inputsize      = 'col-md-6';
    $modalwriter->title          = mlang_str('MODAL-DATACONNECTIONS_MEMBERS_TITLE', true);
    $modalwriter->footercancel   = mlang_str('CANCEL', true);
    $modalwriter->footeraccept   = mlang_str('UPDATE', true);
    $modalwriter->modalsize      = 'modal-';
    $modalwriter->acceptcustomfunction = 'dataconnectionsmemberupdate()';
    $modalwriter->includescript = '/modals_js/dataconnections_members.js';

    $formcontent  = $modalwriter->createFormTextInput('MODAL-DATACONNECTIONS_MEMBERS_TXT_NAME','name',null,array('disabled'=>''));
    $formcontent .= $modalwriter->createLine();

    $label1 = $modalwriter->createLabel('MODAL-DATACONNECTIONS_MEMBERS_LBL_USERS', '', array('labelsize'=>''), array('style'=>'margin-left:5px; text-align:left;'));
    $label2 = $modalwriter->createLabel('MODAL-DATACONNECTIONS_MEMBERS_LBL_SELECTEDUSERS', '', array('labelsize'=>''), array('style'=>'margin-left:10px; text-align:left;'));

    $col1div = $modalwriter->tag('div', '', 'col-md-6 ', $label1);
    $col2div = $modalwriter->tag('div', '', 'col-md-6 ', $label2);
    $labels = $modalwriter->tag('div', '', 'row ', $col1div . $col2div );
    $selectbox = $modalwriter->createSelectBasic( 'selectdataconnectionusers',null, array('multiple'=>'multiple'));
    $labelandselect = $modalwriter->tag('div', '', 'col', $labels . $selectbox, array( 'style'=>'margin-left: 15px;')  );

    $formcontent .= $modalwriter->tag('div', '', 'row',   $labelandselect );

    $bodybegin  = $modalwriter->tag('div', '', 'col-md-12 centered', $formcontent);

    $modalwriter->createModal($buildpagebegin, $endcontent, $bodybegin,'modal-child');
    return $modalwriter->cleanupHTML($buildpagebegin . $endcontent);
});