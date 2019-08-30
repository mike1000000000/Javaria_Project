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
 * File: footer.php
 * Last Modified: 8/24/19, 1:49 PM
 */
?>
<footer class="page-footer fixed-bottom ">
    <!-- Copyright -->
    <div class="footer-copyright text-center py-3">
        <a  href="#" data-toggle="modal" data-target="#modal_about" style="text-decoration: none;"><?php mlang_str('FOOTER_LINK');?></a>
    </div>
</footer>

<?php  if(isset($_GET["xy"]) && $_GET["xy"] == 1) : ?>
    <div id="dragThis" style="position: absolute; top:0; left:0; width:120px; height: 75px;  border:3px solid #73AD21; z-index: 20000; background-color: #FFFFFF; text-align: center;"  >
        <ul class="list-unstyled">
            <li id="element_name"></li>
            <li id="posX"></li>
            <li id="posY"></li>
        </ul>
    </div>
<?php endif; ?>