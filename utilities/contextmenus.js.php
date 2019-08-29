<?php
/**
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
 * File: contextmenus.js.php
 * Last Modified: 8/24/19, 1:48 PM
 */
?>

<?php if(has_permission('contextshow')) : ?>

$(function() {
    $.contextMenu({
        selector: '#content',
        zIndex:10001,
        callback: function(key, options) {

            switch(key) {

                case 'set_db':
                    $.post("ajaxfunctions.php", {
                            action: 'dashboardUpdateDefault', db_no:  current_db },

                        function (data, status) { });
                    break;

                <?php if(has_permission('addeditdashboard')) : ?>
                case 'edit_db':
                    showModal('modal_dashboard_addsingle', $(this),current_db, '', true );
                    break;
                case 'reset':
                    $.post("ajaxfunctions.php", {
                            action: 'dashboardReset', db_no:  current_db },

                        function (data, status) {
                            build_dashboard(current_db);
                        });
                    break;
                <?php endif; ?>

                default:
            }
        },
        items: {
            'set_db': {name: jlang_string['context_defaultdashboard'], icon: 'fa-map-pin'},
            <?php if(has_permission('addeditdashboard')) : ?>
            'edit_db': {name: jlang_string['context_editdashboard'], icon: 'edit'},
            'reset': {name: jlang_string['context_resetdashboard'], icon: 'delete'}
            <?php endif; ?>
        }
    })
});
<?php endif; ?>


<?php if(has_permission('contextshow') && (
        has_permission('elementdelete') ||
        has_permission('addeditdashboard') ||
        has_permission('addeditchart') ||
        has_permission('addedithtml') ||
        has_permission('elementupdate') )
        ) :  ?>

$(function() {
    $.contextMenu({
        selector: '.zzz',
        zIndex:10000,
      autoHide: true,
        callback: function(key, options) {

            switch(key){
    <?php if(has_permission('elementdelete')) : ?>
                case 'delete':

                    var deleteid= $(this).attr('id').substr($(this).attr('id').indexOf("_") + 1, $(this).attr('id').length - $(this).attr('id').indexOf("_"));
                    var textfields = {'title' : jlang_string['chartdelete_title'],
                        'spn_question' : jlang_string['chartdelete_text'],
                        'btn_accept' : jlang_string['delete'],
                        'btn_decline' : jlang_string['cancel']};
                    var ajaxfunction = {'btn_accept':['elementDelete', 'build_dashboard']};

                    showModal('modal_singlequestion',this, deleteid, '' ,true, textfields, ajaxfunction, 'build_dashboard');

                    break;
    <?php endif; ?>

    <?php if(has_permission('addeditdashboard')) : ?>
                case 'appearance':

                    var appearanceid= $(this).attr('id').substr($(this).attr('id').indexOf("_") + 1, $(this).attr('id').length - $(this).attr('id').indexOf("_"));
                    var modal_ca = $('#modal_elementappearance');

                    modal_ca.val(appearanceid);
                    modal_ca.modal('show');

                    break;
    <?php endif; ?>

    <?php if(has_permission('addeditchart')) : ?>
                case 'chart':

                    var editid = $(this).attr('id').substr($(this).attr('id').indexOf("_") + 1, $(this).attr('id').length - $(this).attr('id').indexOf("_"));
                    var modal_ea = $('#modal_addchart');

                    modal_ea.val(editid);
                    modal_ea.modal('show');

                    break;
    <?php endif; ?>

    <?php if(has_permission('addedithtml')) : ?>
                case 'html':

                    var editid = $(this).attr('id').substr($(this).attr('id').indexOf("_") + 1, $(this).attr('id').length - $(this).attr('id').indexOf("_"));
                    var modal_ea = $('#modal_htmledit');

                    modal_ea.val(editid);
                    modal_ea.modal('show');

                    break;
    <?php endif; ?>

    <?php if(has_permission('elementupdate')) : ?>
                case 'z-index-inc':
                case 'z-index-dec':

                    var updateid= $(this).attr('id').substr($(this).attr('id').indexOf("_") + 1, $(this).attr('id').length - $(this).attr('id').indexOf("_"));
                    var zIndex = $(this).css('z-index');

                    if(key ==='z-index-inc' )zIndex++; else zIndex--;

                    $(this).css('z-index', zIndex);

                    var tableVals = {};
                    tableVals['zindex'] = zIndex;

                    $.post("ajaxfunctions.php", { action: 'elementUpdateValues', modalval: updateid, tableVals: tableVals }, function (data, status) {      });

                    break;
    <?php endif; ?>
            }
        },

    <?php if(has_permission('addeditdashboard') ||  has_permission('addeditchart') || has_permission('addedithtml')   ) : ?>
        items: {

            "fold1a": {

                "name": jlang_string['context_element_edit'],
                "items": {
    <?php if(has_permission('addeditdashboard')) : ?>-->
                    'appearance': {name: jlang_string['context_element_appearance'], icon: 'fa-cogs'},
    <?php endif; ?>
    <?php if(has_permission('addeditchart')) : ?>
                    'chart': {name: jlang_string['context_element_chart'],
                              icon: 'fa-bar-chart',
                           disabled: function(key,opt){
                                return !opt.$trigger.hasClass('chartjs');
                        }},

    <?php endif; ?>
    <?php if(has_permission('addedithtml')) : ?>
                    'html': {name: jlang_string['context_element_html'],
                            icon: 'fa-code',
                            disabled: function(key,opt){
                                return !opt.$trigger.hasClass('html');
                        }
            }
    <?php endif; ?>
                }, icon: 'edit'
        },
    <?php endif; ?>

    <?php if(has_permission('elementupdate')) : ?>
            "fold1b": {
                "name": jlang_string['context_element_order'],
                "items": {
                    'z-index-inc': {name: jlang_string['context_element_forward'], icon: 'fa-arrow-up'  },
                    'z-index-dec': {name: jlang_string['context_element_backward'], icon: 'fa-arrow-down' }

                }, icon: 'fa-sort'  },
    <?php endif; ?>

    <?php if(has_permission('elementdelete')) : ?>
            'delete': {name: jlang_string['delete'], icon: 'delete'}
    <?php endif; ?>

        }
    })
});
<?php endif; ?>