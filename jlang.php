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
 * File: jlang.php
 * Last Modified: 8/20/19, 9:31 PM
 */

$jlang_str['tooltip_admins'] = mlang_str('TOOLTIP_ADMINS',true);
$jlang_str['tooltip_users'] = mlang_str('TOOLTIP_USERS',true);
$jlang_str['tooltip_settings'] = mlang_str('TOOLTIP_SETTINGS',true);
$jlang_str['tooltip_delete'] = mlang_str('TOOLTIP_DELETE',true);

$jlang_str['context_defaultdashboard'] = mlang_str('CONTEXT_DEFAULTDASHBOARD',true);
$jlang_str['context_editdashboard'] = mlang_str('CONTEXT_EDITDASHBOARD',true);
$jlang_str['context_resetdashboard'] = mlang_str('CONTEXT_RESETDASHBOARD',true);

$jlang_str['context_element_edit'] = mlang_str('CONTEXT_ELEMENT_EDIT',true);
$jlang_str['context_element_appearance'] = mlang_str('CONTEXT_ELEMENT_APPEARANCE',true);
$jlang_str['context_element_chart'] = mlang_str('CONTEXT_ELEMENT_CHART',true);
$jlang_str['context_element_html'] = mlang_str('CONTEXT_ELEMENT_HTML',true);
$jlang_str['context_element_order'] = mlang_str('CONTEXT_ELEMENT_ORDER',true);
$jlang_str['context_element_forward'] = mlang_str('CONTEXT_ELEMENT_FORWARD',true);
$jlang_str['context_element_backward'] = mlang_str('CONTEXT_ELEMENT_BACKWARD',true);

$jlang_str['ok'] = mlang_str('OK',true);
$jlang_str['cancel'] = mlang_str('CANCEL',true);
$jlang_str['delete'] = mlang_str('DELETE',true);
$jlang_str['update'] = mlang_str('UPDATE',true);
$jlang_str['save'] = mlang_str('SAVE',true);
$jlang_str['all'] = mlang_str('ALL',true);
?>
  <script >
        // Pass PHP values
        var current_db = <?php  echo isset($_SESSION['dashboard']) ? $_SESSION['dashboard'] : 0; ?>;

        <?php
            include('utilities/contextmenus.js.php'); // Add context menus
            include('utilities/movechart.js.php');    // Add ability to move elements

            $js_array = json_encode($jlang_str);
            echo "jlang_string = ". $js_array . ";\n";
        ?>

        $(document).ready(function() {
            <?php echo $document_ready . PHP_EOL;
                foreach ($java_scripts as $javascript) {
                    echo $javascript;
                }
            ?>

            if(current_db != 0){
                build_dashboard(current_db);
            }else{
                document.getElementById("middle-block").innerHTML = "";
                resizecontentheight();
            }
        });
   </script>
