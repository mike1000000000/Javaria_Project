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
 * File: dashboard_members.js
 * Last Modified: 8/20/19, 9:31 PM
 */

// variables

// on load
$('#modal_dashboardmembers').on('show.bs.modal', function () {

    $('#modal_dashboardmembers__sel_selectdashboardusers').multiSelect({});
    $('#modal_dashboardmembers__inp_name').val($(this).data('name'));

    var s = $(this).data('id') ;
    var d = $(this).data('admin') ;

    $('#modal_dashboardmembers__title').text(d ? jlang_string['dashboard_admins'] : jlang_string['dashboard_users']);

    if(s) {
        $.post("ajaxfunctions.php", {action: 'userGetAllPossibleMembers'},
            function (data, status) {

                var multiindex = 0;
                var response = JSON.parse(data);
                if(response.error !== 0){ errorHandler(response.value,response.error); return;}
                var result = JSON.parse(response.value);

                for (index = 0; index < result.length; ++index) {

                    $('#modal_dashboardmembers__sel_selectdashboardusers').multiSelect('addOption',
                        {value: result[index]['id'], text: result[index]['firstname'] + ' ' + result[index]['lastname'], index: multiindex++}
                    );
                }

                $.post("ajaxfunctions.php", {action: 'dashboardMembers', dashboard_id: s, admin: d},
                    function (data, status) {

                        var response = JSON.parse(data);
                        if(response.error !== 0){ errorHandler(response.value,response.error); return;}
                        var result = JSON.parse(response.value);

                        if (!(result.dmembers == null || result.dmembers === '')) {

                            $('#modal_dashboardmembers__sel_selectdashboardusers').val(result.dmembers.split(","));
                            $('#modal_dashboardmembers__sel_selectdashboardusers').multiSelect('refresh');
                        }
                    });
            });
    }
});

// on close
$('#modal_dashboardmembers').on('hidden.bs.modal', function (e) {
    clearMultiselect('modal_dashboardmembers__sel_selectdashboardusers');
});

// on close

// elements

// functions

function dashboardmembersupdate(){

    var tableVals = {};
    tableVals['id'] = $('#modal_dashboardmembers').data("id");
    tableVals['admin'] = $('#modal_dashboardmembers').data("admin");

    var testit = $("#modal_dashboardmembers__sel_selectdashboardusers").val();

    if( testit === undefined || testit.length === 0  ) {
        tableVals['selectedusers'] = "-";
    }else {
        tableVals['selectedusers'] = $("#modal_dashboardmembers__sel_selectdashboardusers").val();
    }

    $.post("ajaxfunctions.php", {action:'dashboardMembersUpdate', tableVals} , function(data, status){ load_dashboards(); });

}