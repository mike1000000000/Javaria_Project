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
 * File: themes.js
 * Last Modified: 8/20/19, 9:31 PM
 */

// variables

// on load
$('#modal_themes').on('show.bs.modal', function () {
    load_themes();
});

// on close

// elements

$(document).ready(function() {

    // Show the edit modal when cog is clicked
    $(document).on("click", "#ico_edit_theme", function(e){
        var dsval = $(this).parent().data("value");
        showModal('modal_themeedit',this, dsval,'',true);
    });

    // Show the delete modal when the x icon is clicked
    $(document).on("click", "#ico_del_theme", function(e){

        var dsval = $(this).parent().data("value");

        var textfields = {'title' : jlang_string['themedel_title'],
            'spn_question' : placeholderformat(jlang_string['themedel_text'], $(this).parent().data("name") ),
            'btn_accept' : jlang_string['ok'],
            'btn_decline' : jlang_string['cancel']};
        var ajaxfunction = {'btn_accept':['themeDelete', 'load_themes']};

        showModal('modal_singlequestion',this, dsval, '' ,true, textfields, ajaxfunction );
    });
});

// functions


function load_themes() {

    $('#modal_themes__ul_themelist').children().remove();

    $.post("ajaxfunctions.php", {action: 'themesGetList'},
        function (data, status) {
            var response = JSON.parse(data);
            if(response.error !== 0){ errorHandler(response.value,response.error); return;}
            data = JSON.parse(response.value);

            for (var key in data) {

                $('#modal_themes__ul_themelist').append(
                    "<li class=\"list-group-item\">" +
                    "<div class=\"row\">" +
                    "<div class=\"col-sm-2\">" +
                    data[key].theme_name +
                    "</div>" +

                    "<div class=\"col-sm-2 pull-right\" >" +
                    "<div class=\"pull-right\" data-name=\"" +
                    data[key].theme_name + "\"  data-value=\"" + data[key].id + "\" >" +
                    "<i align=\"Right\" class=\"fa fa-cogs\" id=\"ico_edit_theme\" title=\"" + jlang_string['tooltip_settings'] + "\" value=\"" + data[key].id + "\" > " +
                    "&nbsp;&nbsp; </i>" +
                    "<i align=\"Right\" class=\"fa fa-times\" id=\"ico_del_theme\" title=\"" + jlang_string['tooltip_delete'] + "\"></i>" +
                    "</div>" +
                    "</div>" +
                    "</div>" +
                    "</li> "
                );
            }
        });
}
