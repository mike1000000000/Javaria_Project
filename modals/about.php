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
 * File: about.php
 * Last Modified: 7/6/19, 2:33 PM
 */

echo call_user_func(function () {
    $modalwriter = new htmlwriter();
    $modalwriter->modalid        = 'modal_about';
    $modalwriter->labelsize      = 'col-md-2';
    $modalwriter->inputsize      = 'col-md-9';
    $modalwriter->title          = mlang_str('MODAL-ABOUT_TITLE', true);
    $modalwriter->footercancel   = mlang_str('CLOSE', true);
    $modalwriter->modalsize      = 'modal-';
    $modalwriter->includescript = '/modals_js/about.js';

    $licenses = new licenses();
    $licensenames = $licenses->getlicensenames();
    $options = array_combine($licensenames,$licensenames);

    $image =$modalwriter->tag('img', '', '', '',array('src'=>'pictures/datalight-analytics.png'));

    global $CFG;
    $info =$modalwriter->tag('div', '', '',mlang_str('APP_NAME', true));
    $info .=$modalwriter->tag('div', '', '',mlang_str('MODAL-ABOUT_TXT_VERSION', true) . ' ' . $CFG->version);
    $info .=$modalwriter->tag('div', '', '',mlang_str('MODAL-ABOUT_TXT_REV', true) . ': ' . $CFG->rev);

    $link = $modalwriter->tag('a', '', '',mlang_str('MODAL-ABOUT_TXT_COPYRIGHT', true) . ' Datalight Analytics', array('href'=>'http://www.datalightanalytics.com'));
    $info .=$modalwriter->tag('div', '', '',$link);
    $info .=$modalwriter->tag('div', '', '','Michel Noel');

    $formcontent =$modalwriter->tag('div', '', 'text-center col-md-18', $image . $info);

    $formcontent .=$modalwriter->newline();

    $formcontent .= $modalwriter->createFormSelectBasic('MODAL-ABOUT_TXT_INFO', 'info',$options, array('name'=>'info','size'=>'1'),'static', array('inputsize'=>'col-md-9') );
    $infobox  = $modalwriter->tag('textarea', 'modal_about__inp_note', 'col-md-5 form-control input-md ','',array('name'=>'notes', 'rows'=>'8','style'=>'resize:none;'));
    $formcontent  .= $modalwriter->tag('div', '', 'col-md-12 centered', $infobox);

    $bodybegin  = $modalwriter->tag('div', '', 'col-md-18 centered', $formcontent);
    $modalwriter->createModal($buildpagebegin, $endcontent, $bodybegin,'modal-child');
    return $modalwriter->cleanupHTML($buildpagebegin . $endcontent);
});