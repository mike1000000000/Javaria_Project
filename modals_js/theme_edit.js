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
 * File: theme_edit.js
 * Last Modified: 8/20/19, 9:31 PM
 */

// variables

// on load

$('#modal_themeedit').on('show.bs.modal', function () {
    $("#modal_themeedit__ul_sortable1").children().remove();
    $("#modal_themeedit__ul_sortable2").children().remove();

    getColours();

    var s = $('#modal_themeedit').val();
    if(s){
        $('#modal_themeedit__title').text(jlang_string['edittheme_title']);
        $('#modal_themeedit__btn_accept').text(jlang_string['update']);

        getThemeColours(s);
        getThemeInfo(s);
    }
    else{
        $('#modal_themeedit__title').text(jlang_string['addtheme_title']);
        $('#modal_themeedit__btn_accept').text(jlang_string['save']);
    }
});

// on close

// elements

// functions

$(document).ready(function() {

    $(function() {
        $.contextMenu({
            selector: '#modal_themeedit__ul_sortable2',
            callback: function(key, options) {

                switch(key){
                    case 'clear_theme':
                        $("#modal_themeedit__ul_sortable2").children().remove();
                        break;
                }
            },
            items: {
                'clear_theme': {name: 'Clear theme', icon: 'fa-eraser'}
            }
        })
    });

    $("#modal_themeedit__ul_sortable1").sortable({
        connectWith: ".connectedSortable",
        forcePlaceholderSize: false,
        helper: function (e, li) {
            copyHelper = li.clone().insertAfter(li);
            return li.clone();
        },
        start: function(e, info) {

        },
        stop: function (e, info) {
            copyHelper && copyHelper.remove();

        }
    });

    // Don't copy the same colour back to the colour list
    $("#modal_themeedit__ul_sortable1").sortable({
        receive: function (e, ui) {
            $(ui.item).remove();
        }
    });

    // Don't remove colour from the colour list
    $("#modal_themeedit__ul_sortable2").sortable({
        receive: function (e, ui) {
             copyHelper = null;
        }
    });


    $('body').on('click', 'li', (function() {
        $('ul#modal_themeedit__ul_sortable1>li.selected').removeClass('selected');
        $(this).addClass("selected");
    }));

    $('.list-group li').click(function(e) {
        e.preventDefault();

        $that = $(this);

        $that.parent().find('li').removeClass('active');
        $that.addClass('active');
    });

    $( function() {
        $( "#modal_themeedit__ul_sortable1, #modal_themeedit__ul_sortable2" ).sortable({
            connectWith: ".connectedSortable"
        }).disableSelection();
    } );

});


function getColours() {

    $("#modal_themeedit__ul_sortable1").children().remove();

    $.post("ajaxfunctions.php", { action: 'colourGetList' },
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

                $('#modal_themeedit__ul_sortable1').append(
                    "<li class=\"list-group-item clearfix colourboxli\" >"
                    + " <div class=\"row\">"
                    + "  <div class=\"col-md-1 colourbox\" data-colourid=\"" + data[key].idcolours + "\" style=\"background-color: " + newcolor + ";border:" + bwidth + "px solid " + bgcolor +  "  \" ></div>"
                    + "  <div class=\"col-md-1 colourboxtext\" data-toggle=\"tooltip\" title=\"" + data[key].colour_name + "\" >" + data[key].colour_name + "</div>"
                    + " </div>"
                    + "</li>");
            }
        });
}


function getThemeColours(s){

    $("#modal_themeedit__ul_sortable2").children().remove();

    $.post("ajaxfunctions.php", {  action: 'themeGetColours', singleVal: s },
        function (data, status) {

            var response = JSON.parse(data);
            if(response.error !== 0){ errorHandler(response.value,response.error); return;}
            data = JSON.parse(response.value);

            for(var key in data) {

                var bgcolor;
                var newcolor = data[key].colour_code.indexOf('rgb') !== -1 ? data[key].colour_code : '#' + data[key].colour_code;

                if(!!data[key].border_colour_code) {
                    bgcolor = data[key].border_colour_code.indexOf('rgb') !== -1 ? data[key].border_colour_code : '#' + data[key].border_colour_code;
                }
                else{
                    bgcolor = newcolor;
                }

                var bwidth = data[key].border_colour_width;

                $('#modal_themeedit__ul_sortable2').append(
                    "<li class=\"list-group-item clearfix colourboxli\" >"
                    + " <div class=\"row\">"
                    + "  <div class=\"col-md-1 colourbox\" data-colourid=\"" + data[key].idcolours + "\" style=\"background-color: " + newcolor + ";border:" + bwidth + "px solid " + bgcolor +  "  \" ></div>"
                    + "  <div class=\"col-md-1 colourboxtext\" data-toggle=\"tooltip\" title=\"" + data[key].colour_name + "\" >" + data[key].colour_name + "</div>"
                    + " </div>"
                    + "</li>");
            }
        }
    );
}

function getThemeInfo(s){
    $.post("ajaxfunctions.php", {  action: 'themeGetInfo', singleVal: s },
        function (data, status) {
            var response = JSON.parse(data);
            if(response.error !== 0){ errorHandler(response.value,response.error); return;}
            data = JSON.parse(response.value);

            $('#modal_themeedit__inp_addtheme_name').val(data['theme_name']);
    });
}

function themeupdate(){

    var tableVals = {};
    var themeid = $('#modal_themeedit').val();

    $("input[type='text'][id^=modal_themeedit]").each(function() {
        tableVals[$(this).attr('name')] = $(this).val();
    });

    var colourlist = $('#modal_themeedit__ul_sortable2 .colourbox');

    var colours = [];
    if (colourlist.length > 0 ) {
         $(colourlist).each(function () {
             colours.push($(this).data('colourid'));
         });
     }
     else{
         colours.push('-');
     }

     tableVals['colours'] = colours;

    $.post("ajaxfunctions.php", {action:'themeAddEdit', tableVals:tableVals, singleVal:themeid} , function(data, status){ load_themes();  });

}