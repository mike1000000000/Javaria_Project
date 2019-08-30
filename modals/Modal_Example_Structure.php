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

?>



<div class="container">

<!-- Modal Name here -->
    <div class="modal fade" id="modal_name" role="dialog">
        <div class="modal-dialog modal-">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
<!-- Modal Title here -->
                    <h4 class="modal-title"><?php mlang_str('MODAL_TITLE'); ?></h4>
                </div>
                <div class="modal-body">
<!-- Modal Content here -->
                    <p><?php mlang_str('MODAL_TEXT'); ?></p>
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php mlang_str('CANCEL'); ?></button>
<!-- Modal Save/Okay/Close button here -->
                    <a class="btn btn-large btn-info"  href="... "><?php mlang_str('MODAL_CLOSE'); ?></a>
                </div>
            </div>

        </div>
    </div>

</div>


<?php
echo call_user_func(function () {

$modalwriter = new htmlwriter();
$modalwriter->modalid        = '';
$modalwriter->labelsize      = '';
$modalwriter->inputsize      = '';
$modalwriter->title          = mlang_str('', true);
$modalwriter->footercancel   = mlang_str('CANCEL', true);
$modalwriter->footeraccept   = mlang_str('SAVE', true);
$modalwriter->acceptcustomfunction = '';
$modalwriter->includescript = '';


$formcontent = $modalwriter->createFormTextInput(  '',          '');
$formcontent .= $modalwriter->createFormSelectBasic('','');

$bodybegin  = $modalwriter->tag('div', '', 'col-md-18 centered', $formcontent);

$modalwriter->createModal($buildpagebegin, $endcontent, $bodybegin,'modal-child');
return $modalwriter->cleanupHTML($buildpagebegin . $endcontent);

});
