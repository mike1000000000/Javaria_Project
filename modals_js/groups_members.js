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
 * File: groups_members.js
 * Last Modified: 8/20/19, 9:31 PM
 */

// variables

// on load
$('#modal_groupsmembers').on('show.bs.modal', function () {

    $('#modal_groupsmembers__sel_selectgroupusers').multiSelect({});
    $('#modal_groupsmembers__inp_name').val($(this).data('name'));

    var s = $(this).data('id') ;
    var d = $(this).data('admin') ;

    $('#modal_groupsmembers__title').text(d ? jlang_string['group_admins'] : jlang_string['group_users']);

    if(s) {
        $.post("ajaxfunctions.php", {action: 'userGetAllPossibleMembers'},
            function (data, status) {
                var multiindex = 0;
                var response = JSON.parse(data);
                if(response.error !== 0){ errorHandler(response.value,response.error); return;}
                var result = JSON.parse(response.value);

                for (index = 0; index < result.length; ++index) {
                    $('#modal_groupsmembers__sel_selectgroupusers').multiSelect('addOption',
                        {value: result[index]['id'], text: result[index]['firstname'] + ' ' + result[index]['lastname'], index: multiindex++}
                    );
                }

                $.post("ajaxfunctions.php", {action: 'groupMembers', group_id: s, admin: d},
                    function (data, status) {

                        var response = JSON.parse(data);
                        if(response.error !== 0){ errorHandler(response.value,response.error); return;}
                        var result = JSON.parse(response.value);

                        if (!(result.gmembers == null || result.gmembers === '')) {
                            $('#modal_groupsmembers__sel_selectgroupusers').val(result.gmembers.split(","));
                            $('#modal_groupsmembers__sel_selectgroupusers').multiSelect('refresh');
                        }
                    });
            });
    }
});

// on close
$('#modal_groupsmembers').on('hidden.bs.modal', function (e) {
    clearMultiselect('modal_groupsmembers__sel_selectgroupusers');
});

// on close

// elements

// functions

function groupsmembersupdate(){

    var tableVals = {};
    tableVals['id'] = $('#modal_groupsmembers').data("id");
    tableVals['admin'] = $('#modal_groupsmembers').data("admin");

    var testit = $("#modal_groupsmembers__sel_selectgroupusers").val();

    if( testit === undefined || testit.length == 0  ) {
        tableVals['selectedusers'] = "-";
    }else {
        tableVals['selectedusers'] = $("#modal_groupsmembers__sel_selectgroupusers").val();
    }

    $.post("ajaxfunctions.php", {action:'groupMembersUpdate', tableVals} , function(data, status){ load_groups();  });

}