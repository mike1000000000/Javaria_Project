/*
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
 * File: admin_loginas.js
 * Last Modified: 8/20/19, 9:31 PM
 */

// variables

// on load

$('#modal_loginas').on('show.bs.modal', function () {
    simpleuserlist('modal_loginas__sel_selectuser');
});

// on close
$('#modal_loginas').on('hidden.bs.modal', function () {
    $('#modal_loginas__sel_selectuser').empty();
});

// elements

// functions

function simpleuserlist(mTarget){
    $passTarget = $('#' + mTarget);

    $.post("ajaxfunctions.php", {action:'userGetAllPossibleMembers',excludeself:'1'},

        function(data, status){
            $passTarget.empty();

            var response = JSON.parse(data);
            if(response.error !== 0){ errorHandler(response.value,response.error); return;}
            data = JSON.parse(response.value);

            $.each(data, function (index, value) {
                $passTarget.append($('<option>', {
                    value: value['id'],
                    text : value['firstname'] + ' ' + value['lastname'] + ' (' + value['username'] + ')'
                }));
            });
        });
}