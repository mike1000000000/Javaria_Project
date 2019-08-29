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
 * File: html_edit.php
 * Last Modified: 6/24/19, 9:08 PM
 */

echo call_user_func(function () use ($document_ready) {
    $modalwriter = new htmlwriter();
    $modalwriter->modalid = 'modal_htmledit';
    $modalwriter->title = mlang_str('MODAL-ADD_HTML_TITLE_ADD', true);
    $modalwriter->labelsize = 'col-md-2';
    $modalwriter->inputsize = 'col-md-8';
    $modalwriter->footercancel = mlang_str('CANCEL', true);
    $modalwriter->footeraccept = mlang_str('SAVE', true);
    $modalwriter->modalsize = 'modal-lg';
    $modalwriter->acceptfunction = 'htmlAddEdit';
    $modalwriter->usenameattribs = true;
    $modalwriter->acceptfunctioncontents = 'build_dashboard();';
    $modalwriter->usemodalid = true;
    $modalwriter->includescript = '/modals_js/html_edit.js';

    $formcontent   = $modalwriter->createFormTextInput('MODAL-ADD_HTML_TXT_NAME','htmlname',null,array('name'=>'htmlname'));
    $formcontent  .= $modalwriter->createFormTextInput('MODAL-ADD_HTML_TXT_CODE','code', array('tag' =>'textarea'), array('rows' => '12', 'name'=>'code'));

    $bodybegin  = $modalwriter->tag('div', '', 'col-md-18 centered', $formcontent);

    $modalwriter->createModal($buildpagebegin, $endcontent, $bodybegin);
    return $modalwriter->cleanupHTML($buildpagebegin . $endcontent);
});
