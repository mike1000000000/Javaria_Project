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
 * File: dashboards.js.php
 * Last Modified: 8/20/19, 9:43 PM
 */

if (session_status() == PHP_SESSION_NONE) session_start();
include('../loadclass.php');

$users = <<<user
                    "<i align=\"Right\" class=\"fa fa-user\" id=\"ico_admins_dashboard\"  title=\"" + jlang_string['tooltip_admins'] + "\" value=\"" + data[key].id + "\" > " +
                    "&nbsp;&nbsp; </i>" +
                    "<i align=\"Right\" class=\"fa fa-users\" id=\"ico_users_dashboard\" title=\"" + jlang_string['tooltip_users'] + "\" value=\"" + data[key].id + "\" > " +
                    "&nbsp;&nbsp; </i>" +
user;

$inserticons = has_permission('userview') ? $users : '';

$js = <<<JS
// variables

// on load
$('#modal_dashboards').on('show.bs.modal', function () {
    load_dashboards();
});

$('#modal_dashboards').on('hidden.bs.modal', function () {
    location.reload();
});

// on close

// elements

$(document).ready(function() {

    // Show Users/Admins modal when icons are clicked
    $(document).on("click", "#ico_users_dashboard, #ico_admins_dashboard", function(e){
        var dsval = $(this).parent().data("value");
        showModal('modal_dashboardmembers',this, dsval, {'id':$(this).parent().data("value") , 'name': $(this).parent().data("name"), 'admin': this.id === "ico_admins_dashboard" ? 1 : 0 } ,true);
    });

    // Show the edit modal when cog is clicked
    $(document).on("click", "#ico_edit_dashboard", function(e){
        var dsval = $(this).parent().data("value");
        showModal('modal_dashboard_addsingle',this, dsval, {'usercall':'0'} ,true);
    });

    // Show the delete modal when the x icon is clicked
    $(document).on("click", "#ico_del_dashboard", function(e){

        var dsval = $(this).parent().data("value");

        var textfields = {'title' : jlang_string['dashboarddel_title'],
            'spn_question' : placeholderformat(jlang_string['dashboarddel_text'], $(this).parent().data("name") ),
            'btn_accept' : jlang_string['ok'],
            'btn_decline' : jlang_string['cancel']};
        var ajaxfunction = {'btn_accept':['dashboardDelete', 'load_dashboards']};

        showModal('modal_singlequestion',this, dsval, '' ,true, textfields, ajaxfunction );
    });
});

// functions

function load_dashboards() {

    $('#modal_dashboards__ul_dashboardlist').children().remove();

    $.post("ajaxfunctions.php", {action: 'dashboardGetList'},
        function (data, status) {

            var response = JSON.parse(data);
            if(response.error !== 0){ errorHandler(response.value,response.error); return;}
            data = JSON.parse(response.value);

            for (var key in data) {

                $('#modal_dashboards__ul_dashboardlist').append(
                    "<li class=\"list-group-item\">" +
                    "<div class=\"row\">" +
                    "<div class=\"col-sm-2\">" +
                    data[key].name +
                    "</div>" +

                    "<div class=\"col-sm-3 pull-right\" >" +
                    "<div class=\"pull-right\" data-name=\"" +
                    data[key].name + "\"  data-value=\"" + data[key].id + "\" >" +
{$inserticons}                     
                    "<i align=\"Right\" class=\"fa fa-cogs\" id=\"ico_edit_dashboard\" title=\"" + jlang_string['tooltip_settings'] + " \" value=\"" + data[key].id + "\" > " +
                    "&nbsp;&nbsp; </i>" +
                    "<i align=\"Right\" class=\"fa fa-times\" id=\"ico_del_dashboard\" title=\"" + jlang_string['tooltip_delete'] + " \"></i>" +
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