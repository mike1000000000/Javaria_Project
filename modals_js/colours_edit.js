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
 * File: colours_edit.js
 * Last Modified: 8/20/19, 9:31 PM
 */

// variables


// on load
$('#modal_addcolour').on('show.bs.modal', function () {

    var elevalue = $('#modal_addcolour').val();

    if (elevalue == '' || elevalue == -1) {
        $('#modal_addcolour__title').text(jlang_string['theme_colouradd_title']);
        $('#modal_addcolour__btn_accept').text(jlang_string['save']);

        $('#modal_addcolour').val(-1);
        $('#modal_addcolour__inp_borderwidth_text').val('0');
        $('#modal_addcolour__div_example').css('border-color', '#FFFFFF');
    }
    else
    {
        $('#modal_addcolour__title').text(jlang_string['theme_colouredit_title']);
        $('#modal_addcolour__btn_accept').text(jlang_string['update']);

        $.post("ajaxfunctions.php", {
                action: 'colourGetInfo', singleVal: elevalue},

            function (data, status) {
                var response = JSON.parse(data);
                if(response.error !== 0){ errorHandler(response.value,response.error); return;}
                tempvarall = JSON.parse(response.value);

                // Colour Name
                $('#modal_addcolour__inp_name').val(tempvarall['colour_name']);

                // Colour Code
                $('#modal_addcolour__inp_colourpicker').spectrum('set', tempvarall['colour_code']);
                $('#modal_addcolour__inp_cvalue').val($('#modal_addcolour__inp_colourpicker').spectrum("get").toHexString());

                $('#modal_addcolour__div_example').css('background-color', $('#modal_addcolour__inp_colourpicker').spectrum("get").toRgbString());

                // Border Colour
                if(!!tempvarall['border_colour_code']) {
                    $('#modal_addcolour__inp_bordercolourpicker').spectrum('set', tempvarall['border_colour_code']);
                    $('#modal_addcolour__inp_bcvalue').val($('#modal_addcolour__inp_bordercolourpicker').spectrum("get").toHexString());
                    $('#modal_addcolour__div_example').css('border-color', $('#modal_addcolour__inp_bordercolourpicker').spectrum("get").toRgbString() );
                }

                // Border Width
                $('#modal_addcolour__div_borderwidth_slider').slider('option', 'value', tempvarall['border_colour_width']);
                $('#modal_addcolour__inp_borderwidth_text').val(tempvarall['border_colour_width']);
                $('#modal_addcolour__div_example').css('border-width', tempvarall['border_colour_width']  + 'px');
            });
    }
});


// on close

$('#modal_addcolour').on('hide.bs.modal', function () {

    $('#modal_addcolour').val('');
    $('#modal_addcolour__inp_name').val('');
    $('#modal_addcolour__inp_colourpicker').spectrum('set', 'FFFFFF');
    $('#modal_addcolour__inp_bordercolourpicker').spectrum('set', null);
    $('#modal_addcolour__div_borderwidth_slider').slider('option', 'value', 0);
    $('#modal_addcolour__inp_borderwidth_text').val('0');
    $('#modal_addcolour__inp_cvalue').val('');
    $('#modal_addcolour__inp_bcvalue').val('');

    $('#modal_addcolour__div_example').css('background-color', '#FFFFFF');
    $('#modal_addcolour__div_example').css('border-color', '#FFFFFF');
    $('#modal_addcolour__div_example').css('border-width', '0px');
});


// elements

$(document).ready(function() {
     $( function() {
        $("#modal_addcolour__div_borderwidth_slider").slider({
                range: false,
                min: 0,
                max: 10,
                value: 0,
                slide: function (event, ui) {


                    $('#modal_addcolour__inp_borderwidth_text').val(ui.value);

                     if (ui.value == 0) {
                         $('#modal_addcolour__inp_bcvalue').val('');
                         $('#modal_addcolour__inp_bordercolourpicker').spectrum('set', null);
                         $('#modal_addcolour__div_example').css('border-color', $('#modal_addcolour__inp_bordercolourpicker').spectrum('get'));
                         $('#modal_addcolour__div_example').css('border-color', '#FFFFFF');
                    }

                    $('#modal_addcolour__div_example').css('border-width', ui.value + 'px');

                }
            }
        );

    });


    $('#modal_addcolour__btn_accept').click( function() {

        if ( $('#modal_addcolour__inp_name').val().length !== 0  &&  $('#modal_addcolour__inp_cvalue').val().length !== 0    ) {

            var updateid = $('#modal_addcolour').val();
            var cpicker = $('#modal_addcolour__inp_colourpicker').spectrum("get");
            var bcpicker = $('#modal_addcolour__inp_bordercolourpicker').spectrum("get");

            var tableVals = {};

            tableVals['newcname'] = $('#modal_addcolour__inp_name').val();
            tableVals['newcvalue'] = cpicker._a < 1 ? cpicker.toRgbString() : cpicker.toHex();
            tableVals['bwidth']  = $('#modal_addcolour__div_borderwidth_slider').slider("value");

            if (bcpicker != null) {
                tableVals['newbcvalue'] = bcpicker._a < 1 ? bcpicker.toRgbString() : bcpicker.toHex();
            }

            $.post("ajaxfunctions.php", { action: 'colourAddEdit', modalval: updateid, tableVals: tableVals},
                function (data, status) {
                    load_colours();
                });
        }
        else{
            alert("Missing name");
            return false;
        }
    });


    $('#modal_addcolour__inp_colourpicker').spectrum({
        color: "#fff",
        showInput: true,
        showAlpha: true,
        preferredFormat: "hex",
        change: function(color){

            $('#modal_addcolour__inp_cvalue').val(color.toHexString());
            $('#modal_addcolour__div_example').css('background-color', color.toRgbString());

            if( $('#modal_addcolour__inp_bcvalue').val() == '') {
                $('#modal_addcolour__div_example').css('border-color', $('#modal_addcolour__inp_colourpicker').spectrum('get'));
            }
        }
    });

    $("#modal_addcolour__inp_bordercolourpicker").spectrum({
        color: null,
        showInput: true,
        showAlpha: true,
        allowEmpty: true,
        preferredFormat: "hex",
        change: function(color){

            if(color != null) {
                $('#modal_addcolour__inp_bcvalue').val(color.toHexString());
                $('#modal_addcolour__div_example').css('border-color', color.toRgbString());

            }
            else{
                $('#modal_addcolour__inp_bcvalue').val('');
                $('#modal_addcolour__div_example').css('border-color', $('#colourpicker').spectrum('get'));
                $('#modal_addcolour__div_borderwidth_slider').slider('option', 'value', 0);
                $('#modal_addcolour__inp_borderwidth_text').val(0);
            }
        }
    });


    $('#modal_addcolour_cancel').click( function() {
        $('#modal_addcolour_dbname').val("");
    });

});

// functions