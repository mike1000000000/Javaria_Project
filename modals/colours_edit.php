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
 * File: colours_edit.php
 * Last Modified: 6/25/19, 10:51 PM
 */

$jlang_str['theme_colouradd_title'] = mlang_str('MODAL-COLOUR_ADDEDIT_TITLE_ADD',true);
$jlang_str['theme_colouredit_title'] = mlang_str('MODAL-COLOUR_ADDEDIT_TITLE_EDIT',true);

echo call_user_func(function () {
    $modalwriter = new htmlwriter();
    $modalwriter->modalid = 'modal_addcolour';
    $modalwriter->labelsize = 'col-md-4';
    $modalwriter->inputsize = 'col-md-6';
    $modalwriter->title = mlang_str('MODAL-COLOUR_ADDEDIT_TITLE_ADD', true);
    $modalwriter->footercancel = mlang_str('CANCEL', true);
    $modalwriter->footeraccept = mlang_str('SAVE', true);
    $modalwriter->includescript = '/modals_js/colours_edit.js';

    $formcontent = $modalwriter->createFormTextInput('MODAL-COLOUR_ADDEDIT_TXT_NAME', 'name');
    $formcontent .= $modalwriter->newline();

    $colourpicker = $modalwriter->createFormInput( 'colourpicker', null,null, array('inputsize'=>'col-md-3'));
    $formcontent .= $modalwriter->createFormTextInput('MODAL-COLOUR_ADDEDIT_TXT_COLOURVALUE', 'cvalue', null,array('disabled' => 'true'), array('inputsize'=>'col-md-3', 'aftercontents'=>$colourpicker ));

    $colourpicker = $modalwriter->createFormInput( 'bordercolourpicker', null,null, array('inputsize'=>'col-md-3'));
    $formcontent .= $modalwriter->createFormTextInput('MODAL-COLOUR_ADDEDIT_TXT_BORDERCOLOURVALUE', 'bcvalue', null,array('disabled' => 'true'), array('inputsize'=>'col-md-3', 'aftercontents'=>$colourpicker ));

    $slider = $modalwriter->createDiv('borderwidth_slider','col-md-3');
    $formcontent .= $modalwriter->createFormTextInput('MODAL-COLOUR_ADDEDIT_SLDR_BORDERSIZE', 'borderwidth_text', null,array('disabled' => 'true'), array('inputsize'=>'col-md-2', 'aftercontents'=>$slider));

    $examplediv   = $modalwriter->createDiv('example','','',array('style'=>'border-style: solid; border-width: 0px;width:50px; height:50px;  background-color:#FFFFFF;  margin: auto'));
    $formcontent .= $modalwriter->createDiv('','',$examplediv,array('style'=>'display: flex;justify-content: center; align-items: center;border-style: solid; border-width: 1px; border-color: #0f0f0f; width:70px; height:70px;  background-color:#FFFFFF;   position:absolute; right: 60px; top: 100px;'));

    $bodybegin = $modalwriter->tag('div', '', 'col-md-18 centered', $formcontent);

    $modalwriter->createModal($buildpagebegin, $endcontent, $bodybegin, 'modal-child');
    return $modalwriter->cleanupHTML($buildpagebegin . $endcontent);
});


