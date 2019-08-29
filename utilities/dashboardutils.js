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
 * File: dashboardutils.js
 * Last Modified: 8/20/19, 9:31 PM
 */

const DEFAULTZINDEXFORDIV = 9000;

function changetodb(dbno){
    $.post("ajaxfunctions.php", {action: 'dashboardSetSessionDB',db: dbno }, function (data){
        location.reload(); });
}

function build_dashboard(dashboard =current_db) {
    current_db = dashboard;
    getdashboardinfo();

    document.getElementById("middle-block").innerHTML = "";
    resizecontentheight();

    $.post("ajaxfunctions.php", { action: 'elementGetDashboardElements', dashboard: dashboard }, function (data, status) {

        var response = JSON.parse(data);
        if(response.error !== 0){ errorHandler(response.value,response.error); return;}

        try {
            data = JSON.parse(response.value);
        }
        catch{
            return;
        }
            $.each(data, function (index, value) {
                buildElement(value);
            });
        });
}

function getdashboardinfo() {

    $.post("ajaxfunctions.php", { action: 'dashboardGetCurrentOptions', db_no: current_db }, function (data, status) {

        var response = JSON.parse(data);
        if(response.error !== 0){ errorHandler(response.value,response.error); return;}

        try{
            data = JSON.parse(response.value);
        }
        catch{
            return;
        }
        if(data === null || data === '') return;

        if(data.hasOwnProperty('bgcolor')){
            var newcolor = data['bgcolor'].indexOf('rbg') !== -1 ? data['bgcolor'] : '#' + data['bgcolor'];
            $('#content').css('background-color', newcolor);
        }
    });
}


function buildElement(datavalue) {

    if(datavalue == null || datavalue == "") return;

    var element_no = datavalue['id'];

    var divtoptions = $.parseJSON(datavalue['options']);
    var divname = "rcdiv_" + element_no;
    var canvname = "canvasID_" + element_no;

    divObj =  build_div(datavalue, element_no);

    document.getElementById("middle-block").appendChild(divObj);

    var width = $('#' + divname).width();
    var height = $('#' + divname).height();

    resizecontentheight();

    switch(datavalue['element_type']) {
        case 'chartjs':

            divObj.classList.add('chartjs');

            $.when(getChartInfo(element_no)).done(function(data){
                var response = JSON.parse(data);
                if(response.error !== 0){ errorHandler(response.value,response.error); return;}

                data = JSON.parse(response.value);

                workingHeight = height * .90;
                workingWidth = width * .95;

                topdiv = document.createElement('div');

                if(data[0] != '' && divtoptions['showtitle']  !== 'false'  ){
                    fontcolour =  divtoptions['titlecolor'] ? 'style="color: ' +  (divtoptions['titlecolor'].indexOf('rgb') !== -1 ? divtoptions['titlecolor'] : '#' + divtoptions['titlecolor']) + '"' : '';
                    topdiv.innerHTML = '<div id="rcdivtitle' + element_no + '" style="height:10px;" ' + fontcolour + '>' + data[0] + '</div>';
                }

                var canvObj = document.createElement("canvas");
                canvObj.setAttribute("id", canvname);
                canvObj.setAttribute("width", workingWidth + "px");
                canvObj.setAttribute("height", workingHeight + "px");

                fragment = document.createDocumentFragment();
                fragment.appendChild(topdiv);
                fragment.appendChild(canvObj);

                document.getElementById(divname).appendChild(fragment);

                var resultdata = JSON.parse(data[1]);
                buildchart(canvname, resultdata);
            });

            break;

        case 'html':

            divObj.classList.add('html');

            $.when(getHtmlInfo(element_no)).done(function(data){
                var response = JSON.parse(data);
                if(response.error !== 0){ errorHandler(response.value,response.error); return;}

                data = JSON.parse(response.value);

                var size = document.getElementById(divname);

                var workingHeight = size.clientHeight || '380';
                var workingWidth = size.offsetWidth || '400';

                topdiv = document.createElement('div');
                middlediv = document.createElement('div');


                if(data['name'] != '' && divtoptions['showtitle']  !== 'false'  ){
                    fontcolour =  divtoptions['titlecolor'] ? 'style="color: ' +  (divtoptions['titlecolor'].indexOf('rgb') !== -1 ? divtoptions['titlecolor'] : '#' + divtoptions['titlecolor']) + '"' : '';
                    topdiv.innerHTML = '<div id="rcdivtitle' + element_no + '" style="height:10px;" ' + fontcolour + '>' + data['name'] + '</div>';
                    workingHeight = workingHeight - 20;
                }

                middlediv.innerHTML = data['htmlcode'];

                workingHeight = workingHeight - 20;

                middlediv.setAttribute("width", workingWidth + "px");

                middlediv.style.height = workingHeight + "px";
                middlediv.style.display = "inline-block";

                fragment = document.createDocumentFragment();
                fragment.appendChild(topdiv);
                fragment.appendChild(middlediv);

                document.getElementById(divname).appendChild(fragment);
            });

            break;
      }
}


function getChartInfo(elementid){
    return $.post("ajaxfunctions.php", {action: 'chartDraw',charno: elementid},function (data, status) { });
}

function getHtmlInfo(elementid){
    return $.post("ajaxfunctions.php", {action: 'htmlGetInfo', element_id: elementid  },function (data, status) { });
}

function build_div(divoptions, element_no ) {

    divoptions = $.parseJSON(divoptions['options']);

    var vpheight = $('#content').height();
    var vpwidth = $('#content').width();

    // Create Div Containers for elements
    var divObj = document.createElement("div");

    divObj.setAttribute("class", "zzz");
    divObj.setAttribute("align", "center");
    divObj.id = "rcdiv_" + element_no;

    var bcolor;

    if(divoptions['bcolor'] !== '' && divoptions['bcolor'] != null){
        bcolor = divoptions['bcolor'].indexOf('rgb') !== -1 ? divoptions['bcolor'] : '#' + divoptions['bcolor'];
    }else{
        bcolor = '#E4FFBF';
    }


    var bwidth = divoptions['border'] != null ? divoptions['border'] : 15 ;

    divObj.style.cssText = "border: " + bwidth + "px solid " + bcolor + " !important; ";

    var divHeight = parseInt(divoptions['height']) !== 0 ? divoptions['height'] : 300;
    divObj.style.cssText +=  "height: " + divHeight + "px;";

    var divWidth = parseInt(divoptions['width']) !== 0 ? divoptions['width'] : 300;
    divObj.style.cssText +=  "width: " + divWidth + "px;";

    var divBarc = divoptions['barc'];
    divObj.style.cssText += "border-radius: " + divBarc + "px;";

    if(divoptions['bgcolor'] !== '' && divoptions['bgcolor'] != null){
        divBgcolor = divoptions['bgcolor'].indexOf('rgb') !== -1 ? divoptions['bgcolor'] : '#' + divoptions['bgcolor'];
    }else{
        divBgcolor = '#FFFFFF';
    }

    divObj.style.cssText += "background-color: " + divBgcolor ;


    var divZindex;

    divZindex = divoptions['z_index'] !== '' &&  divoptions['z_index'] != null ? divoptions['z_index'] : DEFAULTZINDEXFORDIV;

    divObj.style.cssText += "z-index: " + divZindex + ';';

    divObj.style.cssText += "position: absolute;";

    var top;
    var left;

    if(parseInt(divoptions['divposx'])  !== 0 || parseInt(divoptions['divposy'] !== 0)){

        top = divoptions['divposx'];
        left =  divoptions['divposy'];

    }
    else{

        if( $('.zzz').length > 0 )  {

            var lastzzz_left  = $('.zzz').last().position().left + $('.zzz').last().outerWidth();
            var lastzzz_top  = $('.zzz').last().position().top ;

            if( lastzzz_left + divWidth < vpwidth     ){
                left = lastzzz_left + 3;
                top = lastzzz_top ;
            }
            else{
                left = $('.zzz').first().position().left;
                top  = $('.zzz').last().position().top + $('.zzz').last().outerHeight() + 3;

            }
        }
        else {

            var elem = $("#content");

            var pos = elem.offset();
            top = pos.top + 3;
            left = pos.left;
        }
    }

    divObj.style.cssText += "top:" + top + "px; ";
    divObj.style.cssText += "left: " + left + "px; ";

    alerttext = 'Element:' +  divObj.id + '\n';
    alerttext += 'Top: ' + top + '\n';
    alerttext += 'Left: ' + left + '\n';
    alerttext += 'Height: ' + divHeight + '\n';
    alerttext += 'Width: ' + divWidth + '\n';
    alerttext += 'Window Height: ' + vpheight + '\n';
    alerttext += 'Window Width: ' + vpwidth + '\n';
    alerttext += ( 'divHeight: '+ divHeight + ' + divTop: ' + top + " = " + ( parseInt(divHeight,10) + parseInt(top,10) ) );

    divObj.style.cssText += "margin: 0px 0px 5px 5px;";
    divObj.style.cssText += "padding: 0px 0px 5px 0px;";

    return divObj;
}

