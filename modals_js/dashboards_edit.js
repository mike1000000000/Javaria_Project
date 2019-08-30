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
 * File: dashboards_edit.js
 * Last Modified: 8/20/19, 9:31 PM
 */

// variables

// on load


$('#modal_dashboard_addsingle').on('show.bs.modal', function(e) {

    var s = $('#modal_dashboard_addsingle').val();

    if(s) {
        $('#modal_dashboard_addsingle__btn_accept').text(jlang_string['update']);

        $.post("ajaxfunctions.php", {action: 'dashboardGetInfo', id: s},
            function (data, status) {

                var response = JSON.parse(data);
                if(response.error !== 0){ errorHandler(response.value,response.error); return;}
                var result = JSON.parse(response.value);

                if (parseInt(result.error) === 1) { return; }

                var options = $.parseJSON(result.options);
                $('#modal_dashboard_addsingle__inp_name').val(result.name);

                if (!(result.options == null)) {
                    $('#modal_dashboard_addsingle__sel_dbcolor').val(options.bgcolor);
                }
            });
    } else {
        $('#modal_dashboard_addsingle__btn_accept').text(jlang_string['save']);
        $('#modal_dashboard_addsingle__sel_dbcolor').val('FFFFFF');
    }
});


// on close


// elements

// functions

function closeeditdashboard(data = null){

    if (parseInt($('#modal_dashboard_addsingle').data('usercall')) === 0) {
        load_dashboards();
    }
    else if(data != null){

        var obj = jQuery.parseJSON(data);

        if(obj.insert_id !== 0){
            changetodb(obj.insert_id);
        }
        else {
            location.reload();
        }
    }
}


