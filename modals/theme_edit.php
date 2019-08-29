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
 * File: theme_edit.php
 * Last Modified: 8/24/19, 12:53 PM
 */

$jlang_str['addtheme_title'] = mlang_str('MODAL-THEMES_ADDEDIT_TITLE_ADD',true);
$jlang_str['edittheme_title'] = mlang_str('MODAL-THEMES_ADDEDIT_TITLE_EDIT',true);

echo call_user_func(function () {
    $modalwriter = new htmlwriter();
    $modalwriter->modalid        = 'modal_themeedit';
    $modalwriter->labelsize      = 'col-md-4';
    $modalwriter->inputsize      = 'col-md-6';
    $modalwriter->title          = mlang_str('MODAL-THEMES_ADDEDIT_TITLE_ADD', true);
    $modalwriter->footercancel   = mlang_str('CANCEL', true);
    $modalwriter->footeraccept   = mlang_str('SAVE', true);
    $modalwriter->acceptcustomfunction = 'themeupdate()';
    $modalwriter->usenameattribs = true;
    $modalwriter->usemodalid = true;
    $modalwriter->includescript = '/modals_js/theme_edit.js';

    $formcontent = $modalwriter->createFormTextInput('MODAL-THEMES_ADDEDIT_TXT_NAME', 'addtheme_name',null,array('name'=>'name'));
    $formcontent .= $modalwriter->createLine();

// Colour list
    $label = $modalwriter->createLabel('MODAL-THEMES_ADDEDIT_LBL_COLOURLIST', '', array('labelsize'=>''), array('style'=>'margin-left:5px; text-align:left;'));
    $userlist = $modalwriter->createUserlist('sortable1','list-group ul2 connectedSortable');
    $col1div = $modalwriter->tag('div', '', 'col-md-4 listboxes', $label . $userlist);
    $col1 = $modalwriter->tag('div', '', 'col', $col1div);

// Theme colour list
    $label = $modalwriter->createLabel('MODAL-THEMES_ADDEDIT_LBL_THEMECOLOURS', '', array('labelsize'=>''), array('style'=>'margin-left:5px; text-align:left;'));
    $userlist = $modalwriter->createUserlist('sortable2','list-group ul2 connectedSortable');
    $col2div = $modalwriter->tag('div', '', 'col-md-4 listboxes', $label . $userlist);
    $col2 = $modalwriter->tag('div', '', 'col',   $col2div);

    $formcontent .= $modalwriter->tag('div', '', 'row',   $col1 . $col2);

    $bodybegin  = $modalwriter->tag('div', '', 'col-md-12 centered', $formcontent);
    $modalwriter->createModal($buildpagebegin, $endcontent, $bodybegin,'modal-child', array('data-themename'=>''));

    return $modalwriter->cleanupHTML($buildpagebegin . $endcontent);
});

