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
    $('#modal_chartds').on('show.bs.modal', function (e) {


        var s = $('#modal_chartds').val();

        if(s === '') {
            getDataConnections('modal_chartds__sel_dataconnection');
            $('#modal_chartds__sel_aggregation').val('SUM');
        }
        else  {
            var dsval = $('#modal_addchart__sel_list_datasets').val();
            parse_json_from_textbox(dsval);

        }

        getThemes('modal_chartds__sel_theme');
    });


$('#modal_chartds').on('shown.bs.modal', function (e) {
    // If no available dataconnections - close this modal
    if( parseInt($('#modal_chartds__sel_dataconnection > option').length) === 1) {
        $('#modal_chartds').modal('hide');
    }
});

// elements
$(document).ready(function() {
    $('#modal_chartds__sel_dataconnection').change(function () {
        $('#modal_chartds__sel_labelvalue, #modal_chartds__sel_sumvalue,#modal_chartds__sel_tablevalue').empty();
        if ($('#modal_chartds__sel_dataconnection').val() == 0) { return; }
        getTables('modal_chartds__sel_tablevalue',$("#modal_chartds__sel_dataconnection").val() )
    });

    $('#modal_chartds__sel_tablevalue').change(function () {
        getAllColumns('modal_chartds__sel_labelvalue', $("#modal_chartds__sel_dataconnection").val() , $("#modal_chartds__sel_tablevalue").val());
        getSumColumns('modal_chartds__sel_sumvalue', $("#modal_chartds__sel_dataconnection").val() , $("#modal_chartds__sel_tablevalue").val());
    });
});


// functions
    function parse_json_from_textbox(datasetIndex) {

        var datasetoptions = JSON.parse($('#modal_addchart__hdn_dataset').val())[datasetIndex];
        var datasetname = datasetoptions['datasetname'];

        $('#modal_chartds__inp_datasetname').val(datasetname);

        var datacon = datasetoptions['dataconnection'];
        var tablename = datasetoptions['tablevalue'];
        var allcols = datasetoptions['labelvalue'];
        var sumcols = datasetoptions['sumvalue'];
        var themeval = datasetoptions['theme'];
        var sumtype = datasetoptions['aggregation'];

        getDataConnections('modal_chartds__sel_dataconnection', datacon);
        getTables('modal_chartds__sel_tablevalue',datacon,tablename);
        getAllColumns('modal_chartds__sel_labelvalue',datacon,tablename,allcols );
        getSumColumns('modal_chartds__sel_sumvalue',datacon,tablename,sumcols);
        getThemes('modal_chartds__sel_theme',themeval);
        setAggregatemethod('modal_chartds__sel_aggregation',sumtype);
    }

    function getDataConnections(mTarget, pickVal = '') {
        //Load dataconnectors into dropdown
        $.post("ajaxfunctions.php", {action: 'dataconnectionGetList'},
            function (data, status) {

                $('#' + mTarget).empty();
                $('#' + mTarget).append($('<option>', {value: 0, text: 'Select dataconnection...'}));

                var response = JSON.parse(data);
                if(response.error !== 0){ errorHandler(response.value,response.error); return;}
                data = JSON.parse(response.value);

                $.each(data, function (index, value) {
                    $('#' + mTarget).append($('<option>', {
                        value: value['id'],
                        text: value['name']
                    }));

                    if (pickVal !== '') {
                        $('#' + mTarget).val(pickVal);
                    }
                });
            });
    }

    function fillChartAddDsDropdown(){
        parse_json();
        add_json_to_datasets();
    }

    function parse_json() {

        var values =  $('#modal_addchart__hdn_dataset').val();

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

        $("[id^='modal_chartds__inp_'],[id^='modal_chartds__sel_']").each(function () {
            fname = (this.id).split(/[_ ]+/).pop();
            dset[fname] = $(this).val();
        });

        var editIndex = $('#modal_chartds').val();

        if (editIndex !== "") {
            obj[editIndex] = dset;
        }
        else {
            obj[index] = dset;
        }
        dsvalues = JSON.stringify(obj);
        $('#modal_addchart__hdn_dataset').val(dsvalues);
    }

    function getTables(mTarget, datacon, pickVal = '') {

        $.post("ajaxfunctions.php", {action:'dataconnectionGetTableList', id:'1',dc: datacon},
            function(data, status){

                $('#' + mTarget).empty();

                var response = JSON.parse(data);
                if(response.error !== 0){ errorHandler(response.value,response.error); return;}

                $.each($.parseJSON(response.value), function (index, value) {
                    $('#' + mTarget).append($('<option>', {
                        value: value['tables'],
                        text : value['tables']
                    }));
                });

                if ( pickVal !== ''  ){
                    $('#' + mTarget).val(pickVal);
                }
            });
    }

    function getThemes(mTarget, theme = '') {$.post("ajaxfunctions.php", {action: 'themesGetList'}, function (data, status) {
                var response = JSON.parse(data);
                if(response.error !== 0){ errorHandler(response.value,response.error); return;}
                data = JSON.parse(response.value);

                for (var key in data) {
                     var optionExists = ($('#' + mTarget + ' option[value=' + data[key].id + ']').length > 0);

                    if (!optionExists) {
                        $('#' + mTarget).append($('<option>', {
                            value: data[key].id,
                            text: data[key].theme_name
                        }));
                    }
                }
                if ( theme !== ''  ) $('#' + mTarget).val(theme);
            });
    }

    function getAllColumns(mTarget, datacon, tablename, allcols = '') {

        if(mTarget === '' || datacon   === '' || tablename === '') return;

        $.post("ajaxfunctions.php", {action:'dataconnectionGetTableColumnList',  tablename: tablename, dc: datacon }, function(data, status){

                $('#' + mTarget).empty();

                var response = JSON.parse(data);
                if(response.error !== 0){ errorHandler(response.value,response.error); return;}

                $.each($.parseJSON(response.value), function (index, value) {
                    $('#' + mTarget).append($('<option>', {
                        value: value['column_name'],
                        text : value['column_name']
                    }));
                });
                if ( allcols !== ''  ) $('#' + mTarget).val(allcols);
            });
    }

    function getSumColumns(mTarget, datacon, tablename, sumcols = '') {

        if(mTarget === '' || datacon   === '' || tablename === '') return;

        $.post("ajaxfunctions.php", {action:'dataconnectionGetTableColumnList',  tablename: tablename, dc: datacon }, function(data, status){

                $('#' + mTarget).empty();

                var response = JSON.parse(data);
                if(response.error !== 0){ errorHandler(response.value,response.error); return;}

                $.each($.parseJSON(response.value), function (index, value) {
                    if( ['double','int','decimal'].indexOf( value['data_type']) > -1 ) {
                        $('#' + mTarget).append($('<option>', {
                            value: value['column_name'],
                            text : value['column_name']
                        }));
                    }
                });
                if ( sumcols !== ''  ) $('#' + mTarget).val(sumcols);
            });
    }

    function setAggregatemethod(mTarget, pickVal = ''){
        if ( pickVal !== '') $('#' + mTarget).val(pickVal);
    }
