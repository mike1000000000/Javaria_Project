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
 * File: colours.js
 * Last Modified: 8/20/19, 9:31 PM
 */

// variables

// on load
$('#modal_colours').on('show.bs.modal', function () {
    load_colours();
});

// on close

// elements

$(document).ready(function() {


    // Show the edit modal when cog is clicked
    $(document).on("click", "#ico_edit_colour", function(e){
        var dsval = $(this).parent().data("value");
        showModal('modal_addcolour',this, dsval, {'usercall':'0'} ,true);
    });

    // Show the delete modal when the x icon is clicked
    $(document).on("click", "#ico_del_colour", function(e){

        var dsval = $(this).parent().data("value");

        var textfields = {'title' : jlang_string['colourdel_title'],
            'spn_question' : placeholderformat(jlang_string['colourdel_text'], $(this).parent().data("name") ),
            'btn_accept' : jlang_string['ok'],
            'btn_decline' : jlang_string['cancel']};
        var ajaxfunction = {'btn_accept':['colourDelete', 'load_colours']};

        showModal('modal_singlequestion',this, dsval, '' ,true, textfields, ajaxfunction );
    });
});

// functions


function load_colours() {

    $('#modal_colours__ul_colourlist').children().remove();

    $.post("ajaxfunctions.php", {action: 'colourGetList'},
        function (data, status) {

            var response = JSON.parse(data);
            if(response.error !== 0){ errorHandler(response.value,response.error); return;}
            data = JSON.parse(response.value);

            for (var key in data) {

                var bgcolor;
                var newcolor = data[key].colour_code.indexOf('rgb') !== -1 ? data[key].colour_code : '#' + data[key].colour_code;

                if(!!data[key].border_colour_code) {
                    bgcolor = data[key].border_colour_code.indexOf('rgb') !== -1 ? data[key].border_colour_code : '#' + data[key].border_colour_code;
                }
                else{
                   bgcolor = newcolor;
                }

                var bwidth = data[key].border_colour_width;

                $('#modal_colours__ul_colourlist').append(
                    "<li class=\"list-group-item learfix colourboxli  \">" +
                    "<div class=\"row\">" +
                    "<div class=\"col-md-1 colourbox\" data-colourid=\"" + data[key].idcolours + "\" style=\"background-color: " + newcolor + ";border:" + bwidth + "px solid " + bgcolor + " \" ></div>" +
                    "<div class=\"col-md-1 colourboxtext\" data-toggle=\"tooltip\" title=\"" + data[key].colour_name + "\" >" + data[key].colour_name + "</div>" +
                    "<div class=\"col-sm-2 pull-right\" >" +
                    "<div class=\"pull-right\" data-name=\"" + data[key].colour_name + "\"  data-value=\"" + data[key].idcolours + "\" >" +
                    "<i align=\"Right\" class=\"fa fa-cogs\" id=\"ico_edit_colour\" title=\"" + jlang_string['tooltip_settings'] + "\" value=\"" + data[key].idcolours + "\" > " + "&nbsp;&nbsp; </i>" +
                    "<i align=\"Right\" class=\"fa fa-times\" id=\"ico_del_colour\"  title=\"" + jlang_string['tooltip_delete'] + "\" ></i>" +
                    "</div>" +
                    "</div>" +
                    "</div>" +
                    "</li> "
                );
            }
        });
}
