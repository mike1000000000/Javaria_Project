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
 * File: colours.php
 * Last Modified: 8/24/19, 12:53 PM
 */

$jlang_str['colourdel_title'] = mlang_str('MODAL-COLOUR_DELETE_TITLE',true);
$jlang_str['colourdel_text'] = mlang_str('MODAL-COLOUR_DELETE_TEXT',true);

echo call_user_func(function () {
    $modalwriter = new htmlwriter();
    $modalwriter->modalid        = 'modal_colours';
    $modalwriter->labelsize      = 'col-md-4';
    $modalwriter->inputsize      = 'col-md-6';
    $modalwriter->title          = mlang_str('MODAL-COLOUR_MANAGE_TITLE', true);
    $modalwriter->footercancel   = mlang_str('CLOSE', true);
    $modalwriter->modalsize      = 'modal-';
    $modalwriter->acceptcustomfunction = '';
    $modalwriter->usenameattribs = true;
    $modalwriter->includescript = '/modals_js/colours.js';

    $label = $modalwriter->createLabel('MODAL-COLOUR_MANAGE_LBL_COLOUR', '', array('labelsize'=>''), array('style'=>'margin-left:10px; text-align:left;'));
    $col1div = $modalwriter->tag('div', '', 'col-md-1 ', $label);
    $labels = $modalwriter->tag('div', '', 'row ', $col1div);

    $selectbox = $modalwriter->createUserlist('colourlist','form-control col-md-12 userlist', array( 'style'=>'height: 300px;'));

    $labelandselect = $modalwriter->tag('div', '', 'col', $labels . $selectbox, array( 'style'=>'margin-left: 15px;margin-right: 15px;')  );

    $formcontent = $modalwriter->tag('div', '', 'row',   $labelandselect );

    $button = $modalwriter->createButton('PLUS','add', 'btn btn-primary pull-right', array('style'=>'width:35px; margin-right:15px;'),'showModal(\'modal_addcolour\',this, \'\', {\'usercall\':\'0\'}, false  );' );
    $formcontent .= $modalwriter->tag('div', '', 'row',   $button );

    $bodybegin  = $modalwriter->tag('div', '', 'col-md-12 centered', $formcontent);

    $modalwriter->createModal($buildpagebegin, $endcontent, $bodybegin,'modal-child');
    return $modalwriter->cleanupHTML($buildpagebegin . $endcontent);

});