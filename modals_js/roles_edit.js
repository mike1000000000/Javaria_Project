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
 * File: roles_edit.js
 * Last Modified: 8/20/19, 9:31 PM
 */

// variables

// on load
$('#modal_rolesedit').on('show.bs.modal', function () {

    $('#modal_rolesedit__sel_selectrolepermissions').multiSelect({});

    var s = $('#modal_rolesedit').val();

        $.post("ajaxfunctions.php", {action: 'permissionsGetList'},
            function(data, status) {
                var index = 0;
                var response = JSON.parse(data);
                if(response.error !== 0){ errorHandler(response.value,response.error); return;}
                var result = JSON.parse(response.value);

                 for (var key in result) {

                      $('#modal_rolesedit__sel_selectrolepermissions').multiSelect('addOption',
                             { value: result[key].id, text: result[key].name, index: index++ }
                      );
                 }

                if(s) {

                    $('#modal_rolesedit__title').text(jlang_string['editrole_title']);
                    $('#modal_rolesedit__btn_accept').text(jlang_string['update']);

                    $.post("ajaxfunctions.php", {action: 'roleGetInfo', id: s},
                        function (data, status) {
                            var response = JSON.parse(data);
                            if(response.error !== 0){ errorHandler(response.value,response.error); return;}
                            var result = JSON.parse(response.value);

                            $('#modal_rolesedit__inp_name').val(result.name);
                            $('#modal_rolesedit__inp_note').val(result.notes);

                        });

                    $.post("ajaxfunctions.php", {action: 'roleGetPermissions', role_id: s},
                        function (data, status) {
                            var response = JSON.parse(data);
                            if(response.error !== 0){ errorHandler(response.value,response.error); return;}
                            var result = JSON.parse(response.value);

                            if (!(result.perms == null || result.perms === '' )) {
                                $('#modal_rolesedit__sel_selectrolepermissions').val(result.perms.split(","));
                                $('#modal_rolesedit__sel_selectrolepermissions').multiSelect('refresh');
                            }
                    });

                } else {
                    $('#modal_rolesedit__title').text(jlang_string['addrole_title']);
                    $('#modal_rolesedit__btn_accept').text(jlang_string['save']);
                }
            });
});

// on close
$('#modal_rolesedit').on('hidden.bs.modal', function (e) {
    clearMultiselect('modal_rolesedit__sel_selectrolepermissions');
});

// elements

// functions

function rolesupdate(){

    var tableVals = {};
    tableVals['id'] = $('#modal_rolesedit').val();


    $("input[type='text'][id^=modal_rolesedit_],textarea#modal_rolesedit__inp_note").each(function() {
        tableVals[$(this).attr('name')] = $(this).val();
    });

    var testit = $("#modal_rolesedit__sel_selectrolepermissions").val();

    if( testit === undefined || testit.length == 0  ) {
        tableVals['selectedpermissions'] = "-";
    }else {
        tableVals['selectedpermissions'] = $("#modal_rolesedit__sel_selectrolepermissions").val();
    }

    $.post("ajaxfunctions.php", {action:'roleAddEdit', tableVals} , function(data, status){ load_roles();  });
}