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
 * File: utilities.js
 * Last Modified: 8/20/19, 9:43 PM
 */

var jlang_string = [];


/**
 * Show Modal Function
 *
 * @param modalid - ID of the Modal to pull up
 * @object caller - Object that called this method
 * @param optionalval - Value to add to the assigned Modal
 * @param datavals - create Data vals in the assigned Modal - JSON
 * @param requireoptionval - Require the optional Value to be added
 * @param textvals - Pass text values to the assigned modal
 * @param ajaxcall - Ajax call to be called upon acceptance
 * @returns {boolean}
 */
function showModal(modalid, caller = null, optionalval = '', datavals = '', requireoptionval = false, textvals = '', ajaxcall = '') {

    if(requireoptionval && optionalval == '' ) {return false;}

    var new_modal = $('#' + modalid);

    if (caller != null) {
//        $('#' + caller.id).closest('.modal').css('filter', 'blur(1px)');
        var newZindex = parseInt($('#' + caller.id).closest('.modal').css('z-index')) + 1;
        new_modal.css('z-index',newZindex);
    }

    if(optionalval !== '') {new_modal.val(optionalval)};

    if(datavals !== '') {
         for (var key in datavals) {
             new_modal.data(key,datavals[key]);
         }
    };

    if(textvals !== '') {
        for (var key in textvals) {
            new_modal.find('#' + modalid + '__' + key).text( textvals[key]);
        }
    };

    if(ajaxcall !== '') {
        for (var key in ajaxcall) {

            var modal_element = '#' + modalid + '__' + key;
            var actioncall = ajaxcall[key][0];
            var actioncontents = ajaxcall[key][1];
            var actionval  = $('#' + modalid).val();

            new_modal.find(modal_element).on('click', function() { callAjax(actioncall, actionval, actioncontents); } );
        }
    }

    new_modal.modal('show');
    return false;
}

function callAjax(action, singleval, actioncontents = '' ){
    $.post("ajaxfunctions.php", {'action':action, 'singleVal': singleval } , function(data, status){
        if(actioncontents != ''){
            window[actioncontents]();
        }
    });
}


function loadJSfile(modalid){

    var url = "/modals_js/" + modalid + ".js";

    if(!isScriptAlreadyIncluded(url)) {
        $.get(url).done(function() {
            $.getScript(url, function(data){});
        });
    }
}

function isScriptAlreadyIncluded(src){
    var scripts = document.getElementsByTagName("script");
    for(var i = 0; i < scripts.length; i++)
        if(scripts[i].getAttribute('src') == src) return true;
    return false;
}

function buildchart(canvname, config_1,height=0,width=0)
{
    if(window['Pie_' + canvname]) {

        var parent = $('#' + canvname).parent();
        var canvas = $('#' + canvname);

        var saveHeight = height > 0 ? height * .90 : canvas.height() ;
        var saveWidth  = width  > 0 ? width  * .95 : canvas.width();

        window['Pie_' + canvname].destroy();
        canvas.remove();
        parent.append('<canvas id="' + canvname + '" style="display: block; height: ' + saveHeight + 'px; width: ' + saveWidth + 'px;" ></canvas>' );
    }

    // When chartjs creates the chart, it modifies the config - need to deep copy the config structure before using
    // to a new perishable variable.
    var new_config = jQuery.extend(true, {}, config_1);

    // console.log(new_config);
    var ctx = document.getElementById(canvname).getContext("2d");
    window['Pie_' + canvname] = new Chart(ctx, new_config);
    return config_1;
}


function parsejsonfromdataset(datasetJSON,datasetIndex)
{
    var datasetoptions = JSON.parse(datasetJSON)[datasetIndex];
    var datasetValues = {};

    datasetValues['datasetname'] = datasetoptions['datasetname'];
    datasetValues['datacon']     = datasetoptions['dataconnection'];
    datasetValues['tablename']   = datasetoptions['tablevalue'];
    datasetValues['allcols']     = datasetoptions['labelvalue'];
    datasetValues['sumcols']     = datasetoptions['sumvalue'];
    datasetValues['themeval']    = datasetoptions['theme'];
    datasetValues['sumtype']     = datasetoptions['aggregation'];

    return datasetValues;
}


function delValfromJSONstring(jsonString,index){

    // convert dsvalues to JSON and back again...
    tempds = JSON.parse(jsonString);
    delete tempds[index];
    tempds = tempds.filter(function(tempds){return tempds != null;});

    return  JSON.stringify(tempds);
}


function placeholderformat(source, params) {

    if(source === undefined){return;}

    if (typeof params == 'string') params = [params];

    $.each(params,function (i, n) {
        source = source.replace(new RegExp("\\{" + i + "\\}", "g"), n);
    });
    return source;
}

function hex2rgb(hex, opacity) {
    var h=hex.replace('#', '');
    h =  h.match(new RegExp('(.{'+h.length/3+'})', 'g'));

    for(var i=0; i<h.length; i++)
        h[i] = parseInt(h[i].length==1? h[i]+h[i]:h[i], 16);

    if (typeof opacity != 'undefined')  h.push(opacity);

    return 'rgba('+h.join(',')+')';
}


// Open document functions
$(document).ready(function() {
    $('[id^=modal_]').on('hidden.bs.modal', function (e) {
        $(this)
            .find('input,textarea,select')
            .val('')
            .not('.static')
            .empty()
            .end()
            .find('input[type=checkbox], input[type=radio]')
            .prop('checked', '')
            .end();

        $(this).val('');
        $(this).removeData();

        if(!$('[id^=modal_]').hasClass('in')) {
            $('.navbar').css('pointer-events','');
        }
    });

    $('[id^=modal_]').on('shown.bs.modal', function (e) {
        $('.navbar').css('pointer-events','none');
    });

    //separators in the menu are hidden by default - this shows them only if items are split up.
    $('ul > li:not(.divider) + li ~ li:not(.divider)').prev('li.divider').css('display', 'inherit');
});

function clearMultiselect(mTarget){
    var target =$('#' + mTarget);
    target.val('');
    target.multiSelect('removeAllOptions');
    target.multiSelect('destroy');
    target.multiSelect({});
}


// functions
function getgroups(mTarget, exclude=0) {

    var target =$('#' + mTarget);

    $.post("ajaxfunctions.php", { action: 'groupGetList' },
        function (data, status) {
            var response = JSON.parse(data);
            if(response.error !== 0){ errorHandler(response.value,response.error); return;}
            data = JSON.parse(response.value);

            target.empty();
            target.append($('<option>', { value: 0, text: jlang_string['all']}));
            target.val(0);

            for (var key in data) {

                if(data[key].id === exclude) continue;

                target.append($('<option>', {
                    value: data[key].id,
                    text: data[key].name
                }));
            }
        });
}


function errorHandler(info = '', error = 0){
    alert(info);

    // If session missing logout
    if(error === 2){
        $(location).attr('href', 'logout.php');
    }
}
