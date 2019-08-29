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
 * File: users.js
 * Last Modified: 8/20/19, 9:31 PM
 */

// variables

// on load
$('#modal_users').on('show.bs.modal', function () {
    getgroups('modal_users__sel_group');
    load_users();
});

// on close
$('#modal_users').on('hidden.bs.modal', function () {
    $('#modal_users__ul_userlist').children().remove();
    $('#modal_users__sel_group').val('0');
});

// elements
$(document).ready(function() {
    $('#modal_users__sel_group').change(function () {
        if( parseInt($('#modal_users__sel_group').val()) === 0) { $('#modal_users__btn_add').attr("disabled", true);  }
        load_users();
    });

    // Show the members modal when members icon is clicked
    $(document).on("click", "#ico_edit_user", function(e){
        var dsval = $(this).parent().data("value");
        showModal('modal_useredit',this, dsval, {'id':$(this).parent().data("value") , 'name': $(this).parent().data("name"), 'group': $('#modal_users__sel_group').val()} ,true);
    });

    // Show the delete modal when the x icon is clicked
    $(document).on("click", "#ico_del_user", function(e){

        var dsval = $(this).parent().data("value");

        var textfields = {'title' : jlang_string['userdel_title'],
            'spn_question' : placeholderformat(jlang_string['userdel_text'], $(this).parent().data("name")),
            'btn_accept' : jlang_string['ok'],
            'btn_decline' : jlang_string['cancel']};
        var ajaxfunction = {'btn_accept':['userDelete', 'load_users']};

        showModal('modal_singlequestion',this, dsval, '' ,true, textfields, ajaxfunction );
    });
});

function load_users() {

    $('#modal_users__ul_userlist').children().remove();

    $.post("ajaxfunctions.php", {action: 'usergroupGetList', group: $('#modal_users__sel_group').val()},
        function (data, status) {

            if(data === ""){return;}

            var response = JSON.parse(data);
            if(response.error !== 0){ errorHandler(response.value,response.error); return;}
            data = JSON.parse(response.value);

            $.each(data, function (index, value) {

                $('#modal_users__ul_userlist').append(
                    "<li class=\"list-group-item\">" +
                    "<div class=\"row\">" +
                    "<div class=\"col-sm-2\">" +
                    value['username'] +
                    "</div>" +

                    "<div class=\"col-sm-3\">" +
                    value['firstname'] + ' ' + value['lastname'] +
                    "</div>" +

                    "<div class=\"col-sm-2\">" +
                    value['email'] +
                    "</div>" +

                    "<div class=\"col-sm-1 pull-right\" data-name=\"" +
                    value['username'] + "\"  data-value=\"" + value['id'] + "\" >" +
                    "<i align=\"Right\" class=\"fa fa-cogs\" id=\"ico_edit_user\"  title=\"" + jlang_string['tooltip_settings'] + "\" value=\"" + value['id'] + "\" > " +
                    "&nbsp;&nbsp; </i>" +
                    "<i align=\"Right\" class=\"fa fa-times\" title=\"" + jlang_string['tooltip_delete'] + "\" id=\"ico_del_user\"></i>" +
                    "</div>" +
                    "</div>" +
                    "</li> "
                );
            });
        });
}