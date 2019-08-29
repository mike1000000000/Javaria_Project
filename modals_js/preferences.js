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
 * File: preferences.js
 * Last Modified: 8/20/19, 9:31 PM
 */

// variables

// on load
$('#modal_preferences').on('show.bs.modal', function () {

    $('#modal_preferences__sel_userrole').empty();
    $('#modal_preferences__sel_userrole').append($('<option>', { value: 0, text: 'None'}));
    $('#modal_preferences__sel_userrole').val(0);

    $('#modal_preferences__sel_adminrole').empty();
    $('#modal_preferences__sel_adminrole').append($('<option>', { value: 0, text: 'None'}));
    $('#modal_preferences__sel_adminrole').val(0);

    $('#modal_preferences__sel_dashboard').empty();
    $('#modal_preferences__sel_dashboard').append($('<option>', { value: 0, text: 'None'}));
    $('#modal_preferences__sel_dashboard').val(0);

    $.when(get_roles(), get_dashboards(), load_preferences()).done(function(r1,d1,p1){

        //load values
        var response = JSON.parse(r1[0]);
        if(response.error !== 0){ errorHandler(response.value,response.error); return;}
        data = JSON.parse(response.value);

        for (var key in data) {

            $('#modal_preferences__sel_userrole').append($('<option>', {
                value: data[key].id,
                text: data[key].name
            }));

            $('#modal_preferences__sel_adminrole').append($('<option>', {
                value: data[key].id,
                text: data[key].name
            }));
        }

        var response2 = JSON.parse(d1[0]);
        if(response2.error === 1){ errorHandler(response2.value); return;}
        data = JSON.parse(response2.value);

        for (var key in data) {

            $('#modal_preferences__sel_dashboard').append($('<option>', {
                value: data[key].id,
                text: data[key].name
            }));
        }

        //load preferences
        var response3 = JSON.parse(p1[0]);
        if(response3.error === 1){ errorHandler(response3.value); return;}
        data = JSON.parse(response3.value);

        for (var key in data) {
            $('[name="' + data[key].preference  + '"]'  ).val(data[key].value);
        }
    });
});
// on close

// elements

// functions


function get_roles(){
    return $.post("ajaxfunctions.php", { action: 'roleGetList'}, function (data, status) { });
}

function get_dashboards(){
    return $.post("ajaxfunctions.php", { action: 'dashboardGetList'}, function (data, status) { });
}

function load_preferences(){
   return $.post("ajaxfunctions.php", {action: 'preferencesGetInfo'}, function (data, status) { });
}

