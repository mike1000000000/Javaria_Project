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
 * File: element_appearance.js
 * Last Modified: 8/20/19, 9:31 PM
 */

// Variables
//var elements_id = 0;


// On load
$('#modal_elementappearance').on('show.bs.modal', function(e) {

    elements_id = $(this).val();

    $.post("ajaxfunctions.php", {action:'elementGetInfo', eleno: elements_id }, function(data, status){

            var response = JSON.parse(data);
            if(response.error !== 0){ errorHandler(response.value,response.error); return;}
            data = JSON.parse(response.value);

            var options = JSON.parse(data.options);

            checktitle = ($.isEmptyObject( options['showtitle'] ) ? true : options['showtitle'] === 'true' );
            $('#modal_elementappearance__chk_titleenable').prop('checked', checktitle);
            $('#modal_elementappearance__sel_titlecolor').val( options['titlecolor'] );

            $('#modal_elementappearance__inp_height').val( options['height'] );
            $('#modal_elementappearance__inp_width').val( options['width'] );
            $('#modal_elementappearance__inp_top').val( options['divposx'] );
            $('#modal_elementappearance__inp_left').val( options['divposy'] );
            $('#modal_elementappearance__inp_corners').val( options['barc'] );
            $('#modal_elementappearance__sel_bgcolor').val( options['bgcolor'] );
            $('#modal_elementappearance__inp_zindex').val( options['zindex'] );
            $('#modal_elementappearance__inp_borderwidth').val('0');

            if(options['border'] > 0 ){
                $('#modal_elementappearance__chk_borderenable').prop('checked', true);
                $('.borderenable').removeAttr('disabled');
                $('#modal_elementappearance__inp_borderwidth').val( options['border'] );
                $('#modal_elementappearance__sel_color').val( options['bcolor'] );
            }
        });
});


// On close
$('#modal_elementappearance').on('hidden.bs.modal', function(e)
{
    $(this)
        .find("input,textarea,select")
        .val('')
        .end()
        .find("input[type=checkbox], input[type=radio]")
        .prop("checked", "")
        .end();

    $('.borderenable').attr("disabled", true);
    $('#modal_elementappearance__inp_borderwidth').val( 0 );

    $('#modal_elementappearance').val('') ;
    //elements_id = 0;
}) ;


// elements
$(document).ready(function() {

    $('#modal_elementappearance__chk_borderenable').click(function () {

        //check if checkbox is checked
        if ($(this).is(':checked')) {

            $('.borderenable').removeAttr('disabled'); //enable input

        } else {
            $('.borderenable').attr('disabled', true); //disable input
        }
    });
});


// functions