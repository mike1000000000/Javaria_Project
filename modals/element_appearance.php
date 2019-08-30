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
 * File: element_appearance.php
 * Last Modified: 8/24/19, 12:53 PM
 */

echo call_user_func(function () {
    $modalwriter = new htmlwriter();
    $modalwriter->modalid        = 'modal_elementappearance';
    $modalwriter->labelsize      = 'col-md-4';
    $modalwriter->inputsize      = 'col-md-2';
    $modalwriter->title          = mlang_str('MODAL-ELEMENT_APPEARANCE_TITLE', true);
    $modalwriter->footercancel   = mlang_str('CANCEL', true);
    $modalwriter->footeraccept   = mlang_str('SAVE', true);
    $modalwriter->usenameattribs = true;
    $modalwriter->acceptfunction = 'elementUpdateValues';
    $modalwriter->acceptfunctioncontents = 'location.reload();';
    $modalwriter->usemodalid = true;
    $modalwriter->includescript = '/modals_js/element_appearance.js';


    // Add colours for dropdowns
    $options['FFFFFF'] = 'White';
    $options['000000'] = 'Black';
    $options['898989'] = 'Light Grey';
    $options['E4FFBF'] = 'Light Green';

    $colours = new colour();
    $loadcolours = $colours->getFullList();
    foreach($loadcolours as $key) {$options[$key['colour_code']] = $key['colour_name'];}

    $pxlabel = $modalwriter->tag('span', '', 'label label-default ',   'px');

    $enabletext   = $modalwriter->tag('span', '', ' align-left ', mlang_str('MODAL-ELEMENT_APPEARANCE_LBL_ENABLE', true), array('for' => $modalwriter->modalid . '__chk_' . 'titleenable'));
    $formcontent  = $modalwriter->createCheckbox('MODAL-ELEMENT_APPEARANCE_CHK_TITLE', 'titleenable', '', array('aftercontents'=>$enabletext, 'inputsize'=>'col-md-1'),array('name'=>'showtitle')  );
    $formcontent .= $modalwriter->createFormSelectBasic('MODAL-ELEMENT_APPEARANCE_SELECT_TITLECOLOUR', 'titlecolor',$options, array('name'=>'titlecolor'),'static', array('inputsize'=>'col-md-4') );

    $formcontent .= $modalwriter->createFormTextInput(  'MODAL-ELEMENT_APPEARANCE_TXT_HEIGHT','height',null,array('name'=>'height'), array('aftercontents'=>$pxlabel));
    $formcontent .= $modalwriter->createFormTextInput(  'MODAL-ELEMENT_APPEARANCE_TXT_WIDTH','width',null,array('name'=>'width'), array('aftercontents'=>$pxlabel));
    $formcontent .= $modalwriter->createFormTextInput(  'MODAL-ELEMENT_APPEARANCE_TXT_TOP','top',null,array('name'=>'top'), array('aftercontents'=>$pxlabel));
    $formcontent .= $modalwriter->createFormTextInput(  'MODAL-ELEMENT_APPEARANCE_TXT_LEFT','left',null,array('name'=>'left'), array('aftercontents'=>$pxlabel));

    $formcontent .= $modalwriter->createFormSelectBasic('MODAL-ELEMENT_APPEARANCE_SELECT_BACKGROUNDCOLOR', 'bgcolor',$options, array('name'=>'bgcolor'),'static', array('inputsize'=>'col-md-4') );

    $enabletext   = $modalwriter->tag('span', '', ' align-left ', mlang_str('MODAL-ELEMENT_APPEARANCE_LBL_ENABLE', true), array('for' => $modalwriter->modalid . '__chk_' . 'borderenable'));
    $formcontent .= $modalwriter->createCheckbox('MODAL-ELEMENT_APPEARANCE_CHK_BORDER', 'borderenable', '', array('aftercontents'=>$enabletext, 'inputsize'=>'col-md-1')  );
    $formcontent .= $modalwriter->createFormSelectBasic('MODAL-ELEMENT_APPEARANCE_SELECT_BORDERCOLOR', 'color',$options, array('disabled' => 'true', 'name'=>'bordercolor' ),'static borderenable', array('inputsize'=>'col-md-4') );
    $formcontent .= $modalwriter->createFormTextInput(  'MODAL-ELEMENT_APPEARANCE_TXT_BORDERWIDTH','borderwidth',null,array('disabled' => 'true','name'=>'border' ), array('aftercontents'=>$pxlabel, 'inputclass'=>'borderenable'));
    $formcontent .= $modalwriter->createFormTextInput(  'MODAL-ELEMENT_APPEARANCE_TXT_CORNERS','corners',null,array('name'=>'borderarc'), array('aftercontents'=>$pxlabel));
    $formcontent .= $modalwriter->createFormTextInput(  'MODAL-ELEMENT_APPEARANCE_TXT_ZINDEX','zindex',null, array('name'=>'zindex'));

    $bodybegin  = $modalwriter->tag('div', '', 'col-md-18 centered', $formcontent);

    $modalwriter->createModal($buildpagebegin, $endcontent, $bodybegin);
    return $modalwriter->cleanupHTML($buildpagebegin . $endcontent);
});

