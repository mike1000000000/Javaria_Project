/*
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
 * File: about.js
 * Last Modified: 8/20/19, 9:14 PM
 */

// variables

// on load
$('#modal_about').on('show.bs.modal', function () {
    $('#modal_about__sel_info').val('Disclaimer');
    loadlicense();
});
// on close

// elements

// elements
$('#modal_about__sel_info').change(function () {
   loadlicense();
});

// functions

function loadlicense(){
    $.post("ajaxfunctions.php", {action:'licenseGet',license: $('#modal_about__sel_info').val() },

        function(data, status){
            const response = JSON.parse(data);
            if(response.error !== 0){ errorHandler(response.value,response.error); return;}
                data = JSON.parse(response.value);

                $('#modal_about__inp_note').val(data)
                                           .scrollTop(0);
        });
}