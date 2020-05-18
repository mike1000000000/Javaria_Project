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
 * File: Modal_Example_Structure.php
 * Last Modified: 8/24/19, 12:53 PM
 */

echo call_user_func(function () {

$modalwriter = new htmlwriter();
$modalwriter->modalid        = 'modal_filters';
$modalwriter->labelsize      = 'col-md-3';
$modalwriter->inputsize      = 'col-md-8';
$modalwriter->title          = mlang_str('MODAL-ADD_DATASET_FILTER_TITLE', true);
$modalwriter->footercancel   = mlang_str('CANCEL', true);
$modalwriter->footeraccept   = mlang_str('SAVE', true);
$modalwriter->acceptcustomfunction = 'fillChartAddDsFilterDropdown()';
$modalwriter->includescript = '/modals_js/chart_edit_datasets_filters.js';

$formcontent = $modalwriter->createFormSelectBasic('MODAL-ADD_DATASET_FILTER_SELECT_FIELD','tablefield', null);

$options['EQUAL'] = mlang_str('MODAL-ADD_DATASET_FILTER_OPTION_EQUAL', true);
$options['LESSTHAN'] = mlang_str('MODAL-ADD_DATASET_FILTER_OPTION_LESSTHAN', true);
$options['GREATERTHAN'] = mlang_str('MODAL-ADD_DATASET_FILTER_GREATERTHAN', true);
$options['LESSTHANEQUAL'] = mlang_str('MODAL-ADD_DATASET_FILTER_OPTION_LESSTHANEQUAL', true);
$options['GREATERTHANEQUAL'] = mlang_str('MODAL-ADD_DATASET_FILTER_GREATERTHANEQUAL', true);
$options['NOTEQUAL'] = mlang_str('MODAL-ADD_DATASET_FILTER_NOTEQUAL', true);
$options['EMPTY'] = mlang_str('MODAL-ADD_DATASET_FILTER_EMPTY', true);

$formcontent .= $modalwriter->createFormSelectBasic('MODAL-ADD_DATASET_FILTER_SELECT_EQUATOR','filterequator',$options,null,"static");
$formcontent .= $modalwriter->createFormTextInput(  'MODAL-ADD_DATASET_FILTER_TXT_VALUE','filtervalue');

$bodybegin  = $modalwriter->tag('div', '', 'col-md-18 centered', $formcontent);

$modalwriter->createModal($buildpagebegin, $endcontent, $bodybegin,'modal-child');
return $modalwriter->cleanupHTML($buildpagebegin . $endcontent);

});
