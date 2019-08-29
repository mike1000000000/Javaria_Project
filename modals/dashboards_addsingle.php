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
 * File: dashboards_addsingle.php
 * Last Modified: 8/24/19, 12:53 PM
 */

echo call_user_func(function () {

    $modalwriter = new htmlwriter();
    $modalwriter->modalid        = 'modal_dashboard_addsingle';
    $modalwriter->labelsize      = 'col-md-4';
    $modalwriter->inputsize      = 'col-md-6';
    $modalwriter->title          = mlang_str('MODAL-ADD_DASHBOARD_TITLE', true);
    $modalwriter->footercancel   = mlang_str('CANCEL', true);
    $modalwriter->footeraccept   = mlang_str('SAVE', true);
    $modalwriter->usenameattribs = true;
    $modalwriter->modalsize      = 'modal-';
    $modalwriter->acceptfunction = 'dashboardAddEdit';
    $modalwriter->usemodalid = true;
    $modalwriter->acceptfunctioncontents = 'closeeditdashboard(data);';
    $modalwriter->includescript = '/modals_js/dashboards_edit.js';

    $formcontent  = $modalwriter->createFormTextInput('MODAL-ADD_DASHBOARD_TXT_DASHBOARDNAME','name',null,array('name'=>'name'));

    $options['FFFFFF'] = 'White';
    $options['000000'] = 'Black';
    $options['898989'] = 'Light Grey';
    $options['E4FFBF'] = 'Light Green';

    $colours = new colour();
    $loadcolours = $colours->getFullList();

    foreach($loadcolours as $key) {$options[$key['colour_code']] = $key['colour_name'];}

    $formcontent .= $modalwriter->createFormSelectBasic('MODAL-ADD_DASHBOARD_SELECT_BACKGROUNDCOLOR', 'dbcolor',$options, array('name'=>'colour'),'static', array('inputsize'=>'col-md-6'));

    $bodybegin  = $modalwriter->tag('div', '', 'col-md-18 centered', $formcontent);

    $modalwriter->createModal($buildpagebegin, $endcontent, $bodybegin,'modal-child');
    return $modalwriter->cleanupHTML($buildpagebegin . $endcontent);
});

