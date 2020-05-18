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
 * File: chart_edit_datasets.php
 * Last Modified: 6/23/19, 9:10 PM
 */

echo call_user_func(function () {

    $modalwriter = new htmlwriter();
    $modalwriter->modalid        = 'modal_chartds';
    $modalwriter->labelsize      = 'col-md-3';
    $modalwriter->inputsize      = 'col-md-8';
    $modalwriter->title          = mlang_str('MODAL-ADD_DATASET_TITLE', true);
    $modalwriter->footercancel   = mlang_str('CANCEL', true);
    $modalwriter->footeraccept   = mlang_str('SAVE', true);
    $modalwriter->acceptcustomfunction = 'fillChartAddDsDropdown()';
    $modalwriter->includescript = '/modals_js/chart_edit_datasets.js';

    $formcontent  = $modalwriter->createFormTextInput(  'MODAL-ADD_DATASET_TXT_NAME','datasetname');
    $formcontent .= $modalwriter->createFormSelectBasic('MODAL-ADD_DATASET_SELECT_DATACONNECTION','dataconnection');
    $formcontent .= $modalwriter->createFormSelectBasic('MODAL-ADD_DATASET_SELECT_TABLE','tablevalue', null,array('size'=>'5'));
    $formcontent .= $modalwriter->createFormSelectBasic('MODAL-ADD_DATASET_SELECT_THEME','theme', null);
    $formcontent .= $modalwriter->createFormSelectBasic('MODAL-ADD_DATASET_SELECT_LABEL','labelvalue', null);
    $formcontent .= $modalwriter->createFormSelectBasic('MODAL-ADD_DATASET_SELECT_VALUE','sumvalue', null);

    $options['SUM'] = mlang_str('MODAL-ADD_DATASET_SELECT_AGGREGATION_OPTION_SUM', true);
    $options['AVG'] = mlang_str('MODAL-ADD_DATASET_SELECT_AGGREGATION_OPTION_AVG', true);
    $options['COUNT'] = mlang_str('MODAL-ADD_DATASET_SELECT_AGGREGATION_OPTION_COUNT', true);
    $options['NONE'] = mlang_str('MODAL-ADD_DATASET_SELECT_AGGREGATION_OPTION_NONE', true);

    $formcontent .= $modalwriter->createFormSelectBasic('MODAL-ADD_DATASET_SELECT_AGGREGATION', 'aggregation',$options,null,"static");
    $formcontent .= $modalwriter->tag('input', $modalwriter->modalid . '__hdn_' . 'filters', '','', array('type'=>'hidden') );
    $formcontent .= $modalwriter->createLine('', array('style'=>'margin-bottom:15px !important; margin-top:5px !important;'));
    $formcontent .= $modalwriter->createFormSelectBasic('MODAL-ADD_DATASET_SELECT_FILTER', 'list_filters', null,array('size'=>'5'));

    $button = $modalwriter->createButton('MODAL-ADD_DATASET_BTN_ADD_FILTER', 'adddsfilter', 'btn btn-primary', array('style' => 'width:20% !important; height:40px;'));//, 'disabled' => 'false'));
    $button .= $modalwriter->createButton('MODAL-ADD_DATASET_BTN_EDIT_FILTER', 'editdsfilter', 'btn btn-primary', array('style' => 'width:20% !important; height:40px;', 'disabled' => ''));
    $button .= $modalwriter->createButton('MODAL-ADD_DATASET_BTN_DEL_FILTER', 'deldsfilter', 'btn btn-primary', array('style' => 'width:20% !important; height:40px;', 'disabled' => ''));
    $formcontent .= $modalwriter->tag('div', '', 'row', $button, array('style' => 'margin-left:155px; margin-top: 5px;'));

    $bodybegin  = $modalwriter->tag('div', '', 'col-md-18 centered', $formcontent);

    $modalwriter->createModal($buildpagebegin, $endcontent, $bodybegin,'modal-child');
    return $modalwriter->cleanupHTML($buildpagebegin . $endcontent);
});