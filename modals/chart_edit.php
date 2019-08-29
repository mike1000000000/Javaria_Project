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
 * File: chart_edit.php
 * Last Modified: 8/24/19, 12:53 PM
 */

$jlang_str['chartadd_title'] = mlang_str('MODAL-ADD_CHART_TITLE_ADD',true);
$jlang_str['chartadd_btn_text'] = mlang_str('MODAL-ADD_CHART_BTN_ADD_CHART',true);

$jlang_str['chartedit_title'] = mlang_str('MODAL-ADD_CHART_TITLE_EDIT',true);
$jlang_str['chartedit_btn_text'] = mlang_str('MODAL-ADD_CHART_BTN_EDIT_CHART',true);

// Used in single question modal
$jlang_str['chartdelete_title'] = mlang_str('DELETE',true);
$jlang_str['chartdelete_text'] = mlang_str('MODAL-SINGLE_DELETE_ELEMENT',true);

echo call_user_func(function () {

    $modalwriter = new htmlwriter();
    $modalwriter->modalid        = 'modal_addchart';
    $modalwriter->labelsize      = 'col-md-4';
    $modalwriter->inputsize      = 'col-md-8';
    $modalwriter->title          = mlang_str('MODAL-ADD_CHART_TITLE_ADD', true);
    $modalwriter->footercancel   = mlang_str('CANCEL', true);
    $modalwriter->footeraccept   = mlang_str('ADDCHART', true);
    $modalwriter->modalsize      = 'modal-lg';
    $modalwriter->acceptfunction = '';
    $modalwriter->includescript = '/modals_js/chart_edit.js';

//    Left side
    $formcontent = $modalwriter->createFormTextInput('MODAL-ADD_CHART_TXT_NAME','name');

    $options['pie'] = mlang_str('MODAL-ADD_CHART_SELECT_TYPE_OPTION_PIE', true);
    $options['polarArea'] = mlang_str('MODAL-ADD_CHART_SELECT_TYPE_OPTION_POLAR', true);
    $options['bar'] = mlang_str('MODAL-ADD_CHART_SELECT_TYPE_OPTION_BAR', true);
    $options['horizontalBar'] = mlang_str('MODAL-ADD_CHART_SELECT_TYPE_OPTION_HBAR', true);
    $options['line'] = mlang_str('MODAL-ADD_CHART_SELECT_TYPE_OPTION_LINE', true);
    $options['doughnut'] = mlang_str('MODAL-ADD_CHART_SELECT_TYPE_OPTION_DOUGHNUT', true);
    $formcontent .= $modalwriter->createFormSelectBasic('MODAL-ADD_CHART_SELECT_TYPE', 'charttype',$options, null,'static');

    $formcontent .= $modalwriter->createFormTextInput('MODAL-ADD_CHART_TXT_XAXIS','xaxis');

    $xaxisoptions   = $modalwriter->tag('label', '', ' control-label small', mlang_str('MODAL-ADD_CHART_CHK_SHOWXAXIS', true), array('for' => $modalwriter->modalid . '__chk_' . 'showXaxis'));
    $xaxisoptions  .= $modalwriter->tag('input', $modalwriter->modalid . '__chk_' . 'showXaxis', '','', array('type'=>'checkbox') );
    $xaxisoptions  .= $modalwriter->tag('label', '', ' control-label small', mlang_str('MODAL-ADD_CHART_CHK_SHOWXAXISGRID', true), array('for' => $modalwriter->modalid . '__chk_' . 'gridXaxis'));
    $xaxisoptions  .= $modalwriter->tag('input', $modalwriter->modalid . '__chk_' . 'gridXaxis', '','', array('type'=>'checkbox') );
    $xaxisoptions  .= $modalwriter->tag('label', '', ' control-label small', mlang_str('MODAL-ADD_CHART_CHK_SHOWXAXISTICKS', true), array('for' => $modalwriter->modalid . '__chk_' . 'tickXaxis'));
    $xaxisoptions  .= $modalwriter->tag('input', $modalwriter->modalid . '__chk_' . 'tickXaxis', '','', array('type'=>'checkbox') );
    $xaxisoptions  .= $modalwriter->tag('label', '', ' control-label small', mlang_str('MODAL-ADD_CHART_CHK_SHOWXAXISSTACKED', true), array('for' => $modalwriter->modalid . '__chk_' . 'stackedXaxis'));
    $xaxisoptions  .= $modalwriter->tag('input', $modalwriter->modalid . '__chk_' . 'stackedXaxis', '','', array('type'=>'checkbox') );
    $xaxisoptions  .= $modalwriter->tag('label', '', ' control-label small', mlang_str('MODAL-ADD_CHART_CHK_SHOWXAXISLINE', true), array('for' => $modalwriter->modalid . '__chk_' . 'lineXaxis'));
    $xaxisoptions  .= $modalwriter->tag('input', $modalwriter->modalid . '__chk_' . 'lineXaxis', '','', array('type'=>'checkbox') );
    $xaxisoptions  .= $modalwriter->tag('label', '', ' control-label small', mlang_str('MODAL-ADD_CHART_CHK_SHOWXAXISLABEL', true), array('for' => $modalwriter->modalid . '__chk_' . 'labelXaxis'));
    $xaxisoptions  .= $modalwriter->tag('input', $modalwriter->modalid . '__chk_' . 'labelXaxis', '','', array('type'=>'checkbox') );
    $xoptionsgroup = $modalwriter->tag('div', '', 'row col-lg-9 pull-right',   $xaxisoptions);

    $formcontent .= $modalwriter->tag('div', '', 'form-group',  $xoptionsgroup, array('style'=>'margin-bottom:15px !important; margin-top:-15px !important;') );

    $formcontent .= $modalwriter->createFormTextInput('MODAL-ADD_CHART_ELEMENT_YAXIS','yaxis');

    $yaxisoptions   = $modalwriter->tag('label', '', ' control-label small', mlang_str('MODAL-ADD_CHART_CHK_SHOWYAXIS', true), array('for' => $modalwriter->modalid . '__chk_' . 'showYaxis'));
    $yaxisoptions  .= $modalwriter->tag('input', $modalwriter->modalid . '__chk_' . 'showYaxis', '','', array('type'=>'checkbox') );
    $yaxisoptions  .= $modalwriter->tag('label', '', ' control-label small', mlang_str('MODAL-ADD_CHART_CHK_SHOWYAXISGRID', true), array('for' => $modalwriter->modalid . '__chk_' . 'gridYaxis'));
    $yaxisoptions  .= $modalwriter->tag('input', $modalwriter->modalid . '__chk_' . 'gridYaxis', '','', array('type'=>'checkbox') );
    $yaxisoptions  .= $modalwriter->tag('label', '', ' control-label small', mlang_str('MODAL-ADD_CHART_CHK_SHOWYAXISTICKS', true), array('for' => $modalwriter->modalid . '__chk_' . 'tickYaxis'));
    $yaxisoptions  .= $modalwriter->tag('input', $modalwriter->modalid . '__chk_' . 'tickYaxis', '','', array('type'=>'checkbox') );
    $yaxisoptions  .= $modalwriter->tag('label', '', ' control-label small', mlang_str('MODAL-ADD_CHART_CHK_SHOWYAXISSTACKED', true), array('for' => $modalwriter->modalid . '__chk_' . 'stackedYaxis'));
    $yaxisoptions  .= $modalwriter->tag('input', $modalwriter->modalid . '__chk_' . 'stackedYaxis', '','', array('type'=>'checkbox') );
    $yaxisoptions  .= $modalwriter->tag('label', '', ' control-label small', mlang_str('MODAL-ADD_CHART_CHK_SHOWYAXISLINE', true), array('for' => $modalwriter->modalid . '__chk_' . 'lineYaxis'));
    $yaxisoptions  .= $modalwriter->tag('input', $modalwriter->modalid . '__chk_' . 'lineYaxis', '','', array('type'=>'checkbox') );
    $yaxisoptions  .= $modalwriter->tag('label', '', ' control-label small', mlang_str('MODAL-ADD_CHART_CHK_SHOWYAXISLABEL', true), array('for' => $modalwriter->modalid . '__chk_' . 'labelYaxis'));
    $yaxisoptions  .= $modalwriter->tag('input', $modalwriter->modalid . '__chk_' . 'labelYaxis', '','', array('type'=>'checkbox') );
    $yoptionsgroup = $modalwriter->tag('div', '', 'row col-lg-9 pull-right',   $yaxisoptions);

    $formcontent .= $modalwriter->tag('div', '', 'form-group',  $yoptionsgroup, array('style'=>'margin-bottom:15px !important; margin-top:-15px !important;') );
    $formcontent .= $modalwriter->createCheckbox('MODAL-ADD_CHART_CHK_SHOWLEGEND', 'legend');
    $formcontent .= $modalwriter->createLine('', array('style'=>'margin-bottom:15px !important; margin-top:5px !important;'));

    $formcontent .= $modalwriter->tag('input', $modalwriter->modalid . '__hdn_' . 'dataset', '','', array('type'=>'hidden') );
    $formcontent .= $modalwriter->createFormSelectBasic('MODAL-ADD_CHART_SELECT_DATASET', 'list_datasets', null,array('size'=>'5'));

    if(has_permission('viewdataconnections') && (new dataconnection())->getfiltereddataconnectionlist() !== '' ) {
        $button = $modalwriter->createButton('MODAL-ADD_CHART_BTN_ADD_DATASET', 'addds', 'btn btn-primary', array('style' => 'width:45% !important; height:40px;'));
        $button .= $modalwriter->createButton('MODAL-ADD_CHART_BTN_DELETE_DATASET', 'delds', 'btn btn-primary', array('style' => 'width:45% !important; height:40px;', 'disabled' => ''));
        $formcontent .= $modalwriter->tag('div', '', 'row', $button, array('style' => 'margin-left:155px;'));
        $button = $modalwriter->createButton('MODAL-ADD_CHART_BTN_EDIT_DATASET', 'editds', 'btn btn-primary', array('style' => 'width:45% !important; height:40px;', 'disabled' => ''));
//    Left side
//
//    Right side
        $formcontent .= $modalwriter->tag('div', '', 'row', $button, array('style' => 'margin-left:155px; margin-top: 5px;'));
    }

    $canvas = $modalwriter->tag('canvas', 'canvasID_ADDCHART', '',  '', array('style'=>'display: block; height: 380px; width: 380px;') );

    $rightside = $modalwriter->tag('div', '', '',  $canvas, array('style'=>'height: 400px; width: 400px; border-radius: 15px; background-color: white; margin: 0px 0px 5px 5px; padding: 0px 0px 5px; border: 5px solid rgb(228, 255, 191) !important;') );

    $button = $modalwriter->createButton('MODAL-ADD_CHART_BTN_USE_DATACONNECTION','useDataConnection', 'btn btn-primary', array('style'=>'height:40px;'),'javascript:test_params();' );
    $rightside .= $modalwriter->tag('div', '', 'col-md-6 col-md-offset-3',   $button);
//    Right side

    $tableright = $modalwriter->tag('td', '', 'col-md-6',  $rightside );
    $tableleft = $modalwriter->tag('td', '', 'col-md-6',  $formcontent );
    $tablerow =  $modalwriter->tag('tr', '', '',  $tableleft . $tableright );
    $table = $modalwriter->tag('table', '', 'table',  $tablerow);

    $bodybegin  = $modalwriter->tag('div', '', 'col-md-18 centered', $table);

    $modalwriter->createModal($buildpagebegin, $endcontent, $bodybegin, 'modal-parent');

    return $modalwriter->cleanupHTML($buildpagebegin . $endcontent);
});