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
 * File: dataconnections_edit.php
 * Last Modified: 6/30/19, 10:33 AM
 */

echo call_user_func(function () {
    $modalwriter = new htmlwriter();
    $modalwriter->modalid        = 'modal_dataconnectionsedit';
    $modalwriter->labelsize      = 'col-md-3';
    $modalwriter->inputsize      = 'col-md-8';
    $modalwriter->title          = mlang_str('MODAL-DATACONNECTIONS_ADDEDIT_TITLE', true);
    $modalwriter->footercancel   = mlang_str('CANCEL', true);
    $modalwriter->footeraccept   = mlang_str('SAVE', true);
    $modalwriter->modalsize      = 'modal-';
    $modalwriter->usenameattribs = true;
    $modalwriter->usemodalid     = true;
    $modalwriter->acceptfunction = 'dataconnectionAddEdit';
    $modalwriter->acceptfunctioncontents = 'load_dataconnections();';
    $modalwriter->includescript  = '/modals_js/dataconnections_edit.js';

    $formcontent  = $modalwriter->createFormTextInput('MODAL-DATACONNECTIONS_ADDEDIT_TXT_NAME','name', null,array('name'=>'name'));
    $formcontent .= $modalwriter->createFormTextInput('MODAL-DATACONNECTIONS_ADDEDIT_TXT_SERVER','server', null, array('name'=>'server'));
    $formcontent .= $modalwriter->createFormTextInput('MODAL-DATACONNECTIONS_ADDEDIT_TXT_PORT','port', null, array('name'=>'port'));
    $formcontent .= $modalwriter->createFormTextInput('MODAL-DATACONNECTIONS_ADDEDIT_TXT_INSTANCE','instance', null, array('name'=>'instance'));
    $formcontent .= $modalwriter->createFormTextInput('MODAL-DATACONNECTIONS_ADDEDIT_TXT_DATABASE','database', null, array('name'=>'database'));
    $formcontent .= $modalwriter->createFormTextInput('MODAL-DATACONNECTIONS_ADDEDIT_TXT_USERNAME','username', null, array('name'=>'username'));
    $formcontent .= $modalwriter->createFormTextInput('MODAL-DATACONNECTIONS_ADDEDIT_TXT_PASSWORD','password', null , array('name'=>'password','type' => 'password'));

    $options['mysql'] = 'MySQL';
    $options['mssql'] = 'MSSQL';
    $formcontent .= $modalwriter->createFormSelectBasic('MODAL-DATACONNECTIONS_ADDEDIT_SELECT_SERVERTYPE', 'servertype',$options,array('name'=>'servertype'),'static');

    $bodybegin  = $modalwriter->tag('div', '', 'col-md-18 centered', $formcontent);

    $modalwriter->createModal($buildpagebegin, $endcontent, $bodybegin);
    return $modalwriter->cleanupHTML($buildpagebegin . $endcontent);
});