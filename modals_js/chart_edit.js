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
 * File: chart_edit.js
 * Last Modified: 8/20/19, 9:14 PM
 */

// Variables

const example_chart = "canvasID_ADDCHART";

// On load
$('#modal_addchart').on('show.bs.modal', function () {

    const elevalue = $('#modal_addchart').val();

    if (elevalue == "") {

        $('#modal_addchart__chk_legend').prop('checked',true);

        $('#modal_addchart__title').text(jlang_string['chartadd_title']);
        $('#modal_addchart__btn_accept').text(jlang_string['chartadd_btn_text']);

        //Build a default chart
        test_params();
    }
    else{

        $.post("ajaxfunctions.php", {action: 'chartGetInfo', ele_id: elevalue}, function (data, status) {

                $('#modal_addchart__title').text(jlang_string['chartedit_title']);
                $('#modal_addchart__btn_accept').text(jlang_string['chartedit_btn_text']);

            const response = JSON.parse(data);
            if(response.error !== 0){ errorHandler(response.value,response.error); return;}
                tempvarall = JSON.parse(response.value);

                $('#modal_addchart__hdn_dataset').val(tempvarall['datasets']);
                $('#modal_addchart__inp_name').val(tempvarall['name']);
                $('#modal_addchart__sel_charttype').val(tempvarall['charttype']);
                $('#modal_addchart__inp_xaxis').val(tempvarall['x_axis_name']);
                $('#modal_addchart__inp_yaxis').val(tempvarall['y_axis_name']);
                $('#modal_addchart__chk_showXaxis').prop('checked',+tempvarall['xshow']);
                $('#modal_addchart__chk_gridXaxis').prop('checked',+tempvarall['xgrid']);
                $('#modal_addchart__chk_tickXaxis').prop('checked',+tempvarall['xtick']);
                $('#modal_addchart__chk_stackedXaxis').prop('checked',+tempvarall['xstack']);
                $('#modal_addchart__chk_lineXaxis').prop('checked',+tempvarall['xline']);
                $('#modal_addchart__chk_labelXaxis').prop('checked',+tempvarall['xlabel']);
                $('#modal_addchart__chk_showYaxis').prop('checked',+tempvarall['yshow']);
                $('#modal_addchart__chk_gridYaxis').prop('checked',+tempvarall['ygrid']);
                $('#modal_addchart__chk_tickYaxis').prop('checked',+tempvarall['ytick']);
                $('#modal_addchart__chk_stackedYaxis').prop('checked',+tempvarall['ystack']);
                $('#modal_addchart__chk_lineYaxis').prop('checked',+tempvarall['yline']);
                $('#modal_addchart__chk_labelYaxis').prop('checked',+tempvarall['ylabel']);
                $('#modal_addchart__chk_legend').prop('checked',+tempvarall['legend']);

                add_json_to_datasets();
                test_params();
            });
    }
});

// elements
$(document).ready(function() {

    $("#modal_addchart__btn_accept").click( function() {
        if ($.trim($("#modal_addchart__inp_name").val()) === "") {
            alert('Please enter a name');
            return false;
        }

        const tableVals = {};

        $('[id^=modal_addchart__inp_],[id^=modal_addchart__sel_]').not('input[type="checkbox"]').each(function()
            {
                fname = (this.id).split(/[_ ]+/).pop();
                tableVals[fname] = $(this).val();
                $(this).val("");
            }
        );

        $('input:checkbox[id^="modal_addchart__chk_"]').each(function()
            {
                fname = (this.id).split(/[_ ]+/).pop();
                tableVals[fname] = +$(this).is(':checked');
                $(this).prop("checked",false);
            }
        );

        tableVals['datasets'] = $('#modal_addchart__hdn_dataset').val();
        singleVal = $('#modal_addchart').val();

        $.post("ajaxfunctions.php", {action:'chartAddEdit', tableVals: tableVals, modalval: singleVal }, function(data, status){
                location.reload();
        });
    });


    $('#modal_addchart__btn_addds').click( function() {
        const modal_ads = $('#modal_chartds');
        modal_ads.modal('show');
        return false;
    });

    $('#modal_addchart__btn_delds').click( function() {
        const dsval = $('#modal_addchart__sel_list_datasets').val();
        if (!(dsval == null || dsval === '' )){
            tempdsvalues = delValfromJSONstring($('#modal_addchart__hdn_dataset').val(),dsval);
            $('#modal_addchart__hdn_dataset').val(tempdsvalues);
            add_json_to_datasets();
        }
        return false;
    });

    $('#modal_addchart__btn_editds').click( function() {

        const dsval = $('#modal_addchart__sel_list_datasets').val();
        if (!(dsval == null || dsval === '' )){
            const modal_ads = $('#modal_chartds');
            $('#modal_chartds').val(dsval);
            modal_ads.modal('show');

        }
        return false;
    });

    $('#modal_addchart__sel_charttype').change(function(){
        test_params()
    });


    $('#modal_addchart__sel_list_datasets').change(function () {
        $('#modal_addchart__btn_editds').prop('disabled', $('#modal_addchart__sel_list_datasets').val() == null);
        $('#modal_addchart__btn_delds').prop('disabled', $('#modal_addchart__sel_list_datasets').val() == null);
    });


});


// functions

//Default values for chart
function defaultvals(params = {}) {
    // Formatted JSON like this
    // {"type":"pie","data":{"labels":["ABC Company","DEF Inc.","HIJ Limited"],"datasets":[{"data":[20,5,15],"backgroundColor":["#FF0000","#00FF00","#0000FF"]}]},"options":{"responsive":false,"maintainAspectRatio":false,"legend":{"display":true},"scales":{"xAxes":[{"ticks":{"display":false,"autoSkip":false,"autoSkipPadding":1},"gridLines":{"display":false},"scaleLabel":{"display":false,"labelString":"X Name"}}],"yAxes":[{"ticks":{"display":false,"autoSkip":false,"autoSkipPadding":1},"gridLines":{"display":false},"scaleLabel":{"display":false,"labelString":"Y Amount"}}]}}}

    const data = "[20, 15, 25]";
    const labels = '["ABC Company", "DEF Inc.", "HIJ Limited"]';

    const charttype = params['charttype'] == null ? "pie" : params['charttype'];
    const xaxis_name = params['xaxis'] == null ? "X Name" : params['xaxis'];
    const xaxis_show = params['showXaxis'] == null ? "X Name" : params['showXaxis'];
    const yaxis_name = params['yaxis'] == null ? "Y Name" : params['yaxis'];
    const yaxis_show = params['showYaxis'] == null ? "X Name" : params['showYaxis'] ? 'true' : 'false';
    const xaxis_line = params['lineXaxis'] == null ? 'false' : params['lineXaxis'] ? 'true' : 'false';
    const yaxis_line = params['lineYaxis'] == null ? 'false' : params['lineYaxis'] ? 'true' : 'false';
    const xaxis_stacked = params['stackedXaxis'] == null ? 'false' : params['stackedXaxis'] ? 'true' : 'false';
    const yaxis_stacked = params['stackedYaxis'] == null ? 'false' : params['stackedYaxis'] ? 'true' : 'false';
    const x_gridlines = params['gridXaxis'] == null ? 'false' : params['gridXaxis'] ? 'true' : 'false';
    const y_gridlines = params['gridYaxis'] == null ? 'false' : params['gridYaxis'] ? 'true' : 'false';
    const x_scalelabel = params['labelXaxis'] == null ? 'false' : params['labelXaxis'] ? 'true' : 'false';
    const y_scalelabel = params['labelYaxis'] == null ? 'false' : params['labelYaxis'] ? 'true' : 'false';
    const legend = params['legend'] == null ? 'false' : params['legend'] ? 'true' : 'false';
    const x_displayticks = params['tickXaxis'] == null ? 'false' : params['tickXaxis'] ? 'true' : 'false';
    const y_displayticks = params['tickYaxis'] == null ? 'false' : params['tickYaxis'] ? 'true' : 'false';

    const def_configtemp = '{"type":"' + charttype + '", "data":{ "labels":' + labels + ', "datasets":[{"data":' + data + ',"backgroundColor":["#FF0000","#00FF00","#0000FF"]}]}, ' +
        '"options":{"responsive":false, "maintainAspectRatio":false, "legend":{"display":' + legend + '}, ' +
        '"scales":{' +
        '"xAxes":[{' +
        '"display":' + xaxis_show + ',' +
        '"ticks":{"display":' + x_displayticks + ', "autoSkip":false,"autoSkipPadding":1},' +
        '"gridLines":{"display":' + x_gridlines + ', "drawBorder":' + xaxis_line + '},' +
        '"scaleLabel":{"display":"' + x_scalelabel + '",' + '"labelString":"' + xaxis_name + '"},' +
        '"stacked":' + xaxis_stacked +
        '}],' +
        '"yAxes":[{' +
        '"display":' + yaxis_show + ',' +
        '"ticks":{"display":' + y_displayticks + ', "autoSkip":false,"autoSkipPadding":1},' +
        '"gridLines":{"display":' + y_gridlines + ', "drawBorder":' + yaxis_line + '},' +
        '"scaleLabel":{"display":"' + y_scalelabel + '",' + '"labelString":"' + yaxis_name + '"},' +
        '"stacked":' + yaxis_stacked +
        '}]}}}';

    const def_configtemp2 = JSON.parse(def_configtemp);

    return jQuery.extend(true, {}, def_configtemp2);
}


function test_params() {

    const tableVals = {};

    $('[id^=modal_addchart__inp_],[id^=modal_addchart__sel_]').not('input[type="checkbox"]').each(function()
        {
            fname = (this.id).split(/[_ ]+/).pop();
            tableVals[fname] = $(this).val();
        }
    );

    $('input:checkbox[id^="modal_addchart__chk_"]').each(function()
        {
            fname = (this.id).split(/[_ ]+/).pop();
            tableVals[fname] = +$(this).is(':checked');
        }
    );

    tableVals['datasets'] = $('#modal_addchart__hdn_dataset').val();

    if(tableVals['datasets'] === ""){
        buildchart(example_chart,defaultvals(tableVals));
        return;
    }


    $.post("ajaxfunctions.php", { action: 'chartDraw', charno: 0, tableVals: tableVals }, function (data, status) {
        const response = JSON.parse(data);
        if(response.error !== 0){ errorHandler(response.value,response.error); return;}
            data = JSON.parse(response.value);

        const resultdata = JSON.parse(data[1]);
        buildchart(example_chart,resultdata);
        });
}

function add_json_to_datasets() {

    const datasetoptions = JSON.parse($('#modal_addchart__hdn_dataset').val());

    $('#modal_addchart__sel_list_datasets').empty();

    $.each(datasetoptions, function (index, value) {
        $('#modal_addchart__sel_list_datasets').append($('<option>', {
            value: index,
            text: value['datasetname']
        }));
    });

    $('#modal_addchart__sel_list_datasets').trigger('change');
}
