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
 * File: user_signout.php
 * Last Modified: 6/30/19, 10:48 AM
 */

echo call_user_func(function () {
    $modalwriter = new htmlwriter();
    $modalwriter->modalid        = 'modal_signout';
    $modalwriter->labelsize      = 'col-md-3';
    $modalwriter->inputsize      = 'col-md-8';
    $modalwriter->title          = mlang_str('MODAL-SIGNOUT_TITLE', true);
    $modalwriter->footercancel   = mlang_str('CANCEL', true);
    $modalwriter->footeraccept   = mlang_str('MODAL-SIGNOUT_BTN_SIGNOUT', true);
    $modalwriter->modalsize      = 'modal-';
    $modalwriter->acceptcustomfunction = '$(location).attr(\'href\', \'logout.php\');';

    $formcontent = $modalwriter->createJustText('MODAL-SIGNOUT_TXT_SIGNOUT');

    $bodybegin  = $modalwriter->tag('div', '', 'col-md-12 centered', $formcontent);
    $modalwriter->createModal($buildpagebegin, $endcontent, $bodybegin,'modal-child');
    return $modalwriter->cleanupHTML($buildpagebegin . $endcontent);
});