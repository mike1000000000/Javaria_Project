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
 * File: theme.js
 * Last Modified: 8/20/19, 9:43 PM
 */

// variables

// on load

$('#modal_theme').on('show.bs.modal', function () {

    getthemes();
    getColours();

});

// on close

// elements
$(document).ready(function() {

    $('#modal_theme__btn_update').click( function() {

        var qqq = $('#modal_theme__ul_sortable2 .colourbox');
        var s =  $('#modal_theme__sel_selecttheme option:selected');

        if (!jQuery.isEmptyObject(s.val())  && qqq.length > 0 ) {

            var colours=[];

            $(qqq).each(function( ) {
                colours.push($(this).data('colourid'));
            });

            $.post("ajaxfunctions.php", {
                    action: 'addThemeColour',
                    t_id: $('#modal_theme__sel_selecttheme').val(),
                    c_values: colours
                },
                function (data, status) {

                });
        };
        return false;
    });
});


// functions

$(document).ready(function() {

    $(function() {
        $.contextMenu({
            selector: '#modal_theme__ul_sortable2',
            callback: function(key, options) {

                switch(key){
                    case 'clear_theme':
                        $("#modal_theme__ul_sortable2").children().remove();
                        break;
                }
            },
            items: {
                'clear_theme': {name: 'Clear theme', icon: 'fa-eraser'}
            }
        })
    });

    $("#modal_theme__ul_sortable1").sortable({
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

    $(".connectedSortable").sortable({
        receive: function (e, ui) {
            copyHelper = null;
        }
    });


    $('body').on('click', 'li', (function() {
        $('ul#modal_theme__ul_sortable1>li.selected').removeClass('selected');
        $(this).addClass("selected");
    }));

    $('.list-group li').click(function(e) {
        e.preventDefault();

        $that = $(this);

        $that.parent().find('li').removeClass('active');
        $that.addClass('active');
    });

    $( function() {
        $( "#modal_theme__ul_sortable1, #modal_theme__ul_sortable2" ).sortable({
            connectWith: ".connectedSortable"
        }).disableSelection();
    } );

    $('#modal_theme__sel_selecttheme').change(function() {
        var s = $("#modal_theme__sel_selecttheme").val();
        if (!jQuery.isEmptyObject(s)  && s !== '0' ) {

            $('#modal_addtheme_name').val(  $("#modal_theme__sel_selecttheme option:selected").text() );
            $("#modal_theme__ul_sortable2").children().remove();

            $.post("ajaxfunctions.php", {
                    action: 'getThemeColours',
                    t_id: s
                },
                function (data, status) {

                    var response = JSON.parse(data);
                    if(response.error !== 0){ errorHandler(response.value,response.error); return;}
                    data = response.value;

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

                        $('#modal_theme__ul_sortable2').append(
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
        else{
            $('#modal_addtheme_name').val(  '' );
        }
    });
});



function getthemes( selectedid = 0 ) {

    if(selectedid == -1){
        $('#modal_theme__sel_selecttheme').children().remove();
    }

    $.post("ajaxfunctions.php", {
            action: 'getThemes'
        },
        function (data, status) {

            var response = JSON.parse(data);
            if(response.error !== 0){ errorHandler(response.value,response.error); return;}
            data = response.value;

            for (var key in data) {

                var optionExists = ($('#modal_theme__sel_selecttheme option[value=' + data[key].id + ']').length > 0);

                if(!optionExists) {
                    $('#modal_theme__sel_selecttheme').append($('<option>', {
                        value: data[key].id,
                        text: data[key].colour_theme_name
                    }));
                }
            }

            $('#modal_theme__sel_selecttheme').val(selectedid);
        });
}

function getColours() {

    $("#modal_theme__ul_sortable1").children().remove();

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

                $('#modal_theme__ul_sortable1').append(
                    "<li class=\"list-group-item clearfix colourboxli\" >"
                    + " <div class=\"row\">"
                    + "  <div class=\"col-md-1 colourbox\" data-colourid=\"" + data[key].idcolours + "\" style=\"background-color: " + newcolor + ";border:" + bwidth + "px solid " + bgcolor +  "  \" ></div>"
                    + "  <div class=\"col-md-1 colourboxtext\" data-toggle=\"tooltip\" title=\"" + data[key].colour_name + "\" >" + data[key].colour_name + "</div>"
                    + " </div>"
                    + "</li>");
            }
        });
}