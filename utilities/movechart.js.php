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
 * File: movechart.js.php
 * Last Modified: 8/24/19, 1:49 PM
 */
?>

// This adds the ability to move the elements around on the canvas
$(document).ready(function() {

$(".modal-content").draggable({
    handle: ".modal-header",
    containment: '#content'
});


// If you doubleclick off the chart on the body area - disable and save
    $('body').not('div.zzz').on('dblclick', '#content', (function (e) {
        e.stopPropagation();
        $( '.zzz.moveselected' ).each(function(  ) {
            updateelementplace(this);
            updateelementsize(this);
            disableDragResize(this);
        });
    }));

// Double clicking on the charts to enable drag, resize
    $('#content').on('dblclick', 'div.zzz', (function (e) {
        e.stopPropagation();
        var selected = this;

        $( '.zzz.moveselected' ).each(function(  ) {
            updateelementplace(this);
            updateelementsize(this);
        });

        $( '.zzz' ).not(selected).each(function(  ) {
            disableDragResize(this);
        });

        $(selected).toggleClass('moveselected');

        if ( $(selected).hasClass( 'moveselected' ) ) {
            enableDragResize(selected);
        }
        else {
            disableDragResize(selected);
        }
    }));

    function disableDragResize(element){

        $(element).removeClass('moveselected');

        if($(element).attr('oldborder') != null) {

            $(element).css('border', '');

            if($(element).attr('oldborder') != 'n/a' ) {
                $(element).css('border', $(element).attr('oldborder'));
            }

            $(element).removeAttr('oldborder');
        }

        $(element).find(".ui-resizable-handle").hide();
        $(element).draggable('disable');
        $(element).resizable( 'disable');
    }

    function enableDragResize(element){

        if($(element).css('border') != null ) {
            $(element).attr('oldborder', $(element).css('border'));
        }
        else{
            $(element).attr('oldborder','n/a');
        }

        $(element).css('border', '');

        $(element).css("border", "5px dashed blue");


        $(element).draggable('enable');
        $(element).resizable( 'enable');
        $(element).find(".ui-resizable-handle").show();
    }

// This moves the charts around on the screen using cursor keys

    //Global scope to capture timeout object for kb use
    var timeout;
    $(document).on('keydown', function(e) {

        var selectedelement = $('.moveselected');
        if (selectedelement.length === 0) return;

        var kc = e.keyCode;
        if (kc === 37 || kc === 38 || kc === 39 || kc === 40 || e.altKey )   e.preventDefault();

        if (kc === 37) {
            e.altKey ? selectedelement.css("width", (selectedelement.outerWidth() - 2) + "px") : selectedelement.css("left", (selectedelement.position().left - 1) + "px");
        } else if (kc === 38) {
            e.altKey ? selectedelement.css("height", (selectedelement.outerHeight() - 2) + "px") : selectedelement.css("top", (selectedelement.position().top - 1) + "px");
        } else if (kc === 39) {
            e.altKey ? selectedelement.css("width", (selectedelement.outerWidth() + 2) + "px") : selectedelement.css("left", (selectedelement.position().left + 1) + "px");
        } else if (kc === 40) {
            e.altKey ? selectedelement.css("height", (selectedelement.outerHeight() + 2) + "px") : selectedelement.css("top", (selectedelement.position().top + 1) + "px");
        }

        if(timeout && e.altKey) {
            clearTimeout(timeout);
            timeout = null;
        }

        if(e.altKey) {
            timeout = setTimeout(function () {
                refreshchart($(selectedelement).attr('id'), selectedelement.outerHeight(), selectedelement.outerWidth());
                resizecontentheight();
            }, 500);
        }
    });
});


function refreshchart(divinfo, height = 0, width = 0){

    if(!$('#' + divinfo).is('.chartjs')) {return;}

    var pos = divinfo.lastIndexOf("_");
    var element_no = divinfo.slice(pos +1);

    var canvname = "canvasID_" + element_no;
    var divnametitle = "rcdivtitle_" + element_no;


    $.post("ajaxfunctions.php", { action: 'chartDraw', charno: element_no  },
        function (data, status) {
            var chartstatus = JSON.parse(data);
            if(chartstatus.error !== 0){ errorHandler(chartstatus.value); return;}

            data = JSON.parse(chartstatus.value);

            var resultdata = JSON.parse(data[1]);
            $('#' + divnametitle).text(data[0]);
            buildchart(canvname, resultdata, height, width);

        });
}


$("body").on("DOMNodeInserted", ".zzz", makeDraggable);
function makeDraggable() {
    $(this).resizable({
        maxHeight:  <?php  echo CHART_CONTAINER_MAX_HEIGHT; ?>,
        maxWidth:   <?php  echo CHART_CONTAINER_MAX_WIDTH; ?>,
        minHeight:  <?php  echo CHART_CONTAINER_MIN_HEIGHT; ?>,
        minWidth:   <?php  echo CHART_CONTAINER_MIN_WIDTH; ?>,
        //containment: "#middle-block",
        animate: false,
        helper: "ui-resizable-helper",
        resize: function (event, ui) {
        },
        start: function (event, ui) {
        },
        stop: function (event, ui) {

            var width = $(event.target).innerWidth();
            var height = $(event.target).innerHeight();
            refreshchart(this.id, height, width);
        }
    });

    $(this).draggable({
        drag: function () {
        <?php  if(!!isset($_GET["xy"])) : ?>
            var offset = $(this).offset();
            var xPos = offset.left;
            var yPos = offset.top;
            $('#element_name').text(this.id);
            $('#posX').text('x: ' + xPos);
            $('#posY').text('y: ' + yPos);
        <?php endif; ?>
        },
        revert: function(event, ui){
            return false;
        },
        containment: ".content",
        stop: function (event, ui) {
            resizecontentheight();

        }
    });


    if (!$(this).hasClass('moveselected')) {
        $(this).draggable('disable');
        $(this).resizable('disable');
        $(this).find(".ui-resizable-handle").hide();
    }
}
function resizecontentheight(){

    var totalheight = $(window).height() - 77;
    $( '.zzz' ).each(function(  ) {
        var thisheight = +$(this).position().top;
        var thisouterheight = +$(this).outerHeight();

        totalheight = Math.max( thisheight + thisouterheight , totalheight );
    });

    var  contentheight = +$('#content').height();

    if( totalheight  !=  contentheight ) {
        $('#content').height( Math.round(totalheight) + 'px' );  }
}

function updateelementplace(element){

    var elementid = element.id.substr(element.id.indexOf("_") +1, element.id.length - element.id.indexOf("_") );

    var tableVals = {};
    tableVals['top'] = Math.round($(element).position().top );
    tableVals['left'] = Math.round($(element).position().left);

    $.post("ajaxfunctions.php", { action: 'elementUpdateValues', modalval: elementid, tableVals: tableVals }, function (data, status) {      });

}


function updateelementsize(elementname){
    var element = $(elementname);
    var width = $(element).outerWidth();
    var height = $(element).outerHeight();

    if (width <= <?php  echo CHART_CONTAINER_MAX_WIDTH;?> && height <= <?php  echo CHART_CONTAINER_MAX_HEIGHT; ?>) {

        refreshchart(element.attr('id'), height, width);

        if (Math.round(height) > 0 && Math.round(width) > 0) {

            var elementid = element.attr('id').substr(element.attr('id').indexOf("_") + 1, element.attr('id').length - element.attr('id').indexOf("_"));

            var tableVals = {};
            tableVals['width'] = Math.round(width);
            tableVals['height'] = Math.round(height);

            $.post("ajaxfunctions.php", { action: 'elementUpdateValues', modalval: elementid, tableVals: tableVals }, function (data, status) {      });
        }
    }
}
