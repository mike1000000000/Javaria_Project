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
 * File: dataconnections_edit.js
 * Last Modified: 8/20/19, 9:31 PM
 */

// variables

// on load


$('#modal_dataconnectionsedit').on('show.bs.modal', function(e) {

    var s = $('#modal_dataconnectionsedit').val();

    if(s) {
        $('#modal_dataconnectionsedit__btn_accept').text(jlang_string['update']);

        $.post("ajaxfunctions.php", {action: 'dataconnectionGetInfo', id: s},
            function (data, status) {
                var response = JSON.parse(data);
                if(response.error !== 0){ errorHandler(response.value,response.error); return;}
                var result = JSON.parse(response.value);

                $('#modal_dataconnectionsedit__inp_name').val(result.name);
                $('#modal_dataconnectionsedit__inp_server').val(result.server);
                $('#modal_dataconnectionsedit__inp_port').val(result.port);
                $('#modal_dataconnectionsedit__inp_instance').val(result.instance);
                $('#modal_dataconnectionsedit__inp_database').val(result.db);
                $('#modal_dataconnectionsedit__inp_username').val(result.username);
                $('#modal_dataconnectionsedit__inp_password').val(result.password);
                $('#modal_dataconnectionsedit__sel_servertype').val(result.servertype);
            });
    } else {
        $('#modal_dataconnectionsedit__btn_accept').text(jlang_string['save']);
        $('#modal_dataconnectionsedit').val(0);
    }
});


// on close

// elements

// functions