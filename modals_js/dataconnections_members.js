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
 * File: dataconnections_members.js
 * Last Modified: 8/20/19, 9:31 PM
 */

// variables

// on load
$('#modal_dataconnectionsmembers').on('show.bs.modal', function(e) {

    $('#modal_dataconnectionsmembers__sel_selectdataconnectionusers').multiSelect({});
    $('#modal_dataconnectionsmembers__inp_name').val($(this).data('name'));


    var s = $(this).data('id') ;


    if(s) {
        $.post("ajaxfunctions.php", {action: 'userGetAllPossibleMembers'},
            function (data, status) {
                var multiindex = 0;
                var response = JSON.parse(data);
                if(response.error !== 0){ errorHandler(response.value,response.error); return;}
                var result = JSON.parse(response.value);

                for (index = 0; index < result.length; ++index) {

                    $('#modal_dataconnectionsmembers__sel_selectdataconnectionusers').multiSelect('addOption',
                        {value: result[index]['id'], text: result[index]['firstname'] + ' ' + result[index]['lastname'], index: multiindex++}
                    );
                }

                $.post("ajaxfunctions.php", {action: 'dataconnectionGetMembers', dataconnection_id: s},
                    function (data, status) {
                        var response = JSON.parse(data);
                        if(response.error !== 0){ errorHandler(response.value,response.error); return;}
                        var result = JSON.parse(response.value);

                        if (!(result.rmembers == null || result.rmembers === '')) {
                            $('#modal_dataconnectionsmembers__sel_selectdataconnectionusers').val(result.rmembers.split(","));
                            $('#modal_dataconnectionsmembers__sel_selectdataconnectionusers').multiSelect('refresh');
                        }
                    });
            });
    }
});
// on close

$('#modal_dataconnectionsmembers').on('hidden.bs.modal', function (e) {
    clearMultiselect('modal_dataconnectionsmembers__sel_selectdataconnectionusers');
});

// elements

// functions

function dataconnectionsmemberupdate(){

    var tableVals = {};
    tableVals['id'] = $('#modal_dataconnectionsmembers').data("id");

    var testit = $("#modal_dataconnectionsmembers__sel_selectdataconnectionusers").val();

    if( testit === undefined || testit.length == 0  ) {
        tableVals['selectedusers'] = "-";
    }else {
        tableVals['selectedusers'] = $("#modal_dataconnectionsmembers__sel_selectdataconnectionusers").val();
    }

    $.post("ajaxfunctions.php", {action:'dataconnectionMembersUpdate', tableVals} , function(data, status){  });
}