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
 * File: test.php
 * Last Modified: 8/24/19, 12:53 PM
 */

?>

<script>

    $(document).ready(function() {

        $('#trigger').click( function() {
            var divname = "inside-div";

            document.getElementById(divname).innerHTML="";

            var zzz = $('#chart').val();

            $.post("update.php", {
                    cn: zzz
                },

                function (data, status) {

                debug_to_console(data);

                var canvname = "newcanvas";

                var workingWidth = 500;
                var workingHeight = 500;

                    var canvObj = document.createElement("canvas");
                    canvObj.setAttribute("id", canvname);
                    canvObj.setAttribute("width", workingWidth + "px");
                    canvObj.setAttribute("height", workingHeight + "px");

                    document.getElementById(divname).appendChild(canvObj);

                    var ctx = document.getElementById(canvname).getContext("2d");


                    window.myPie_1 = new Chart(ctx, JSON.parse(data[1]));

                }
            );
            return false;
        });


    });

    function debug_to_console( data ) {
        console.info( 'Debug in Console:' );
        console.log( data  );
    }


</script>


<div class="container">

    <!-- Modal Name here -->
    <div class="modal fade" id="modal_name" role="dialog">
        <div class="modal-dialog modal-">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <!-- Modal Title here -->
                    <h4 class="modal-title">TEST</h4>
                </div>
                <div class="modal-body container-fluid">
                    <!-- Modal Content here -->

                    <p id="inside"> </p>

                <div id="inside-div" style="width: 500px; height: 500px;"></div>

                    <button type="button" id="trigger" class="btn btn-default" data-dismiss="modal">trigger</button>
                    <input id="chart" name="modal_adduser_username" type="text" placeholder="" class="form-control input-md" required="">
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php mlang_str('CANCEL'); ?></button>
                    <!-- Modal Save/Okay/Close button here -->
                    <a class="btn btn-large btn-info"  href=""><?php mlang_str('CLOSE'); ?></a>
                </div>
            </div>

        </div>
    </div>

</div>
