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
 * File: chart_edit_datasets.js
 * Last Modified: 8/20/19, 9:31 PM
 */

// Variables

// On load //
    $('#modal_filters').on('show.bs.modal', function (e) {

        var s = $('#modal_filters').val();
        $('#modal_filters__sel_tablefield').html($('#modal_chartds__sel_labelvalue').html());

        if(s !== '') {
            var dsval = $('#modal_chartds__sel_list_filters').val();
            parse_json_from_textbox_for_filters(dsval);
        }
    });

    $('#modal_filters').on('shown.bs.modal', function (e) {
        // If no available labels - close this modal
        if( parseInt($('#modal_chartds__sel_labelvalue > option').length) === 0) {
            $('#modal_filters').modal('hide');
        }
    });

// elements
$(document).ready(function() {
    $('#modal_filters__sel_filterequator').change(function () {
        if( $(this).val() == "EMPTY" ){
            $('#modal_filters__inp_filtervalue').val("");
            $('#modal_filters__inp_filtervalue').prop('disabled', true);
        }else{
            $('#modal_filters__inp_filtervalue').prop('disabled', false);
        }
    });
});

// functions
    function parse_json_from_textbox_for_filters(datasetIndex) {
        var datasetoptions = JSON.parse($('#modal_chartds__hdn_filters').val())[datasetIndex];
        $('#modal_filters__inp_filtervalue').val(datasetoptions['filtervalue']);
        $('#modal_filters__sel_tablefield').val(datasetoptions['tablefield']);
        $('#modal_filters__sel_filterequator').val(datasetoptions['filterequator']);
    }

    function fillChartAddDsFilterDropdown(){

        if ($.trim($("#modal_filters__sel_filterequator").val()) === "") {
            alert('Please enter select an equator');
            return false;
        }
        parse_filter_json();
        add_json_to_filters();
    }

    function parse_filter_json() {

        var values =  $('#modal_chartds__hdn_filters').val();

        var index = 0;
        var obj;

        if (values == null || values === '') {
            obj = [];
        }
        else {
            obj = JSON.parse(values);
            index = Object.keys(obj).length;
        }

        var dset = {};

        $("[id^='modal_filters__inp_'],[id^='modal_filters__sel_']").each(function () {
            fname = (this.id).split(/[_ ]+/).pop();
            dset[fname] = $(this).val();
        });

        var editIndex = $('#modal_filters').val();

        if (editIndex !== "") {
            obj[editIndex] = dset;
        }
        else {
            obj[index] = dset;
        }
        filtervalues = JSON.stringify(obj);
        $('#modal_chartds__hdn_filters').val(filtervalues);
    }