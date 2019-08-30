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
 * File: singlequestion.js
 * Last Modified: 5/21/19, 8:01 PM
 */

// variables

// on load
$('#modal_singlequestion').on('show.bs.modal', function () {

});

// on close

$('#modal_singlequestion').on('hidden.bs.modal', function () {
    $('#modal_singlequestion__title').text('{}');
    $('#modal_singlequestion__spn_question').text('{}');
    $('#modal_singlequestion__btn_accept').text('{}');
    $('#modal_singlequestion__btn_decline').text('{}');
    $('#modal_singlequestion').val('');

    $('#modal_singlequestion__btn_accept').unbind();
});