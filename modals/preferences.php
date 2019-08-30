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
 * File: preferences.php
 * Last Modified: 6/30/19, 10:43 AM
 */

echo call_user_func(function () use ($document_ready) {
    $modalwriter = new htmlwriter();
    $modalwriter->modalid = 'modal_preferences';
    $modalwriter->title = mlang_str('MODAL-PREFERENCES_TITLE', true);
    $modalwriter->labelsize = 'col-md-4';
    $modalwriter->inputsize = 'col-md-6';
    $modalwriter->footercancel = mlang_str('CANCEL', true);
    $modalwriter->footeraccept = mlang_str('SAVE', true);
    $modalwriter->modalsize = 'modal-';
    $modalwriter->acceptfunction = 'preferencesUpdate';
    $modalwriter->includescript = '/modals_js/preferences.js';
    $modalwriter->usenameattribs = true;

    $formcontent = $modalwriter->createFormSelectBasic('MODAL-PREFERENCES_SELECT_DEFAULTUSERROLE','userrole',null,array('name'=>'default_userrole'));
    $formcontent .= $modalwriter->createFormSelectBasic('MODAL-PREFERENCES_SELECT_DEFAULTADMINROLE','adminrole',null,array('name'=>'default_adminrole'));
    $formcontent .= $modalwriter->createFormSelectBasic('MODAL-PREFERENCES_SELECT_DEFAULTDASHBOARD','dashboard',null,array('name'=>'default_dashboard'));

    $bodybegin  = $modalwriter->tag('div', '', 'col-md-12 centered', $formcontent);

    $modalwriter->createModal($buildpagebegin, $endcontent, $bodybegin);
    return $modalwriter->cleanupHTML($buildpagebegin . $endcontent);
});