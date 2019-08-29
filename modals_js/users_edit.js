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
 * File: users_edit.js
 * Last Modified: 8/20/19, 9:31 PM
 */

// variables

// on load
$('#modal_useredit').on('show.bs.modal', function(e) {
    $("#modal_useredit__inp_username").prop("disabled", false);
    var useself = !!$(e.relatedTarget).data('targetself');
    load_user(useself);
});

// on close
$('#modal_useredit').on('hidden.bs.modal', function(e) {
    $("#modal_useredit__inp_username").prop("disabled", false);
});

// elements
$('#modal_users__sel_group').change(function () {
    $('#modal_users__btn_add').prop('disabled', $('#modal_users__sel_group').val() === 0);
});

// functions
function load_user(useself = false){

    var s = $('#modal_useredit').val();

    if(s || useself) {

        var title = useself ? jlang_string['profileuser_title'] : jlang_string['edituser_title'];
        $('#modal_useredit__title').text(title);

        $.post("ajaxfunctions.php", {action: 'userGetInfo', id: s},
            function (data, status) {
                var response = JSON.parse(data);
                if(response.error !== 0){ errorHandler(response.value,response.error); return;}
                var result = JSON.parse(response.value);

                $("#modal_useredit__inp_username").val(result.username);
                $('#modal_useredit__inp_firstname').val(result.firstname);
                $('#modal_useredit__inp_lastname').val(result.lastname);
                $('#modal_useredit__inp_password').val(result.password);
                $('#modal_useredit__inp_phone_number').val(result.phone_number);
                $('#modal_useredit__inp_email').val(result.email);
                $('#modal_useredit__inp_note').val(result.note);

                if(useself) {
                    $('#modal_useredit').val(result.id);
                    $("#modal_useredit__inp_username").prop("disabled",true);
                }
            });
    } else {
        $('#modal_useredit__title').text(jlang_string['adduser_title']);

        if($('#modal_useredit').data('group') !== null    ) {
            $('#modal_useredit__inp_group').val($('#modal_useredit').data('group'));
        }
    }
}