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
 * File: groups.js.php
 * Last Modified: 8/20/19, 9:43 PM
 */

if (session_status() == PHP_SESSION_NONE) session_start();
include('../loadclass.php');

$users = <<<user
                    "<i align=\"Right\" class=\"fa fa-user\" id=\"ico_admins_group\" title=\"" + jlang_string['tooltip_admins'] + "\" value=\"" + data[key].id + "\" > " +
                    "&nbsp;&nbsp; </i>" +
                    "<i align=\"Right\" class=\"fa fa-users\" id=\"ico_users_group\" title=\"" + jlang_string['tooltip_users'] + "\" value=\"" + data[key].id + "\" > " +
                    "&nbsp;&nbsp; </i>" +
user;

$inserticons = has_permission('userview') ? $users : '';

$js = <<<JS
// variables

// on load
$('#modal_groups').on('show.bs.modal', function () {
    load_groups();
});

// on close

// elements

$(document).ready(function() {

    // Show Users/Admins modal when icons are clicked
    $(document).on("click", "#ico_users_group, #ico_admins_group", function(e){
        var dsval = $(this).parent().data("value");
        showModal('modal_groupsmembers',this, dsval, {'id':$(this).parent().data("value") , 'name': $(this).parent().data("name"), 'admin': this.id == "ico_admins_group" ? 1 : 0 } ,true);
    });

    // Show the edit modal when cog is clicked
    $(document).on("click", "#ico_edit_group", function(e){
        var dsval = $(this).parent().data("value");
        showModal('modal_groupsedit',this, dsval, '' ,true);
    });

    // Show the delete modal when the x icon is clicked
    $(document).on("click", "#ico_del_group", function(e){

        var dsval = $(this).parent().data("value");

        var textfields = {'title' : jlang_string['groupdel_title'],
            'spn_question' : placeholderformat(jlang_string['groupdel_text'], $(this).parent().data("name") + " (" + $(this).parent().data("value") + ")" ),
            'btn_accept' : jlang_string['ok'],
            'btn_decline' : jlang_string['cancel']};
            var ajaxfunction = {'btn_accept':['groupDelete', 'load_groups']};

        showModal('modal_singlequestion',this, dsval, '' ,true, textfields, ajaxfunction );
    });
});

// functions

function load_groups() {

    $('#modal_groups__ul_grouplist').children().remove();

    $.post("ajaxfunctions.php", {action: 'groupGetList'},
        function (data, status) {

            var response = JSON.parse(data);
            if(response.error !== 0){ errorHandler(response.value,response.error); return;}
            data = JSON.parse(response.value);

            for (var key in data) {

                $('#modal_groups__ul_grouplist').append(
                    "<li class=\"list-group-item\">" +
                    "<div class=\"row\">" +
                    "<div class=\"col-sm-4\">" +
                    data[key].name +
                    "</div>" +
                    
                    "<div class=\"col-sm-3 pull-right\" >" +
                    "<div class=\"pull-right\" data-name=\"" +
                    data[key].name + "\"  data-value=\"" + data[key].id + "\" >" +
{$inserticons}        
                    "<i align=\"Right\" class=\"fa fa-cogs\" id=\"ico_edit_group\" title=\"" + jlang_string['tooltip_settings'] + "\" value=\"" + data[key].id + "\" > " +
                    "&nbsp;&nbsp; </i>" +
                    "<i align=\"Right\" class=\"fa fa-times\" id=\"ico_del_group\" title=\"" + jlang_string['tooltip_delete'] + "\" ></i>" +
                    "</div>" +
                    "</div>" +
                    "</div>" +
                    "</li> "
                );
            }
        });
}
JS;

header("Content-type: text/javascript");
echo $js;
exit();