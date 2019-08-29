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
 * File: singlequestion.php
 * Last Modified: 8/24/19, 12:53 PM
 */

echo call_user_func(function () {

    $modalwriter = new htmlwriter();
    $modalwriter->modalid        = 'modal_singlequestion';
    $modalwriter->labelsize      = 'col-md-4';
    $modalwriter->inputsize      = 'col-md-2';
    $modalwriter->title          = mlang_str('', true);
    $modalwriter->footercancel   = mlang_str('', true);
    $modalwriter->footeraccept   = mlang_str('', true);
    $modalwriter->includescript = '/modals_js/singlequestion.js';

    $formcontent = $modalwriter->createJustText('','question','');

    $bodybegin  = $modalwriter->tag('div', '', 'col-md-12 centered', $formcontent);
    $modalwriter->createModal($buildpagebegin, $endcontent, $bodybegin,'modal-child');
    return $modalwriter->cleanupHTML($buildpagebegin . $endcontent);
});
