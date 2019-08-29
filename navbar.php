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
 * File: navbar.php
 * Last Modified: 8/24/19, 1:52 PM
 */
?>

<script>
    $(document).on("click", ".dashboard_links", function(e) {
        changetodb($(this).val());
    });
</script>


<nav class="navbar navbar-default">
  <div class="container">

    <div class="navbar-header">
        <!-- Hamburger button for smaller screens -->
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <img class="navbar-left"  src="pictures/datalightanalytics.png" style="width:50px;height: 50px; background-color: transparent;" alt="DLA Logo" />
        <a class="navbar-brand" href="#">DLA</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-navbar-collapse">

     <?php if(has_permission('dashboardmenu')) : ?>
          <ul class="nav navbar-nav">
            <li class="dropdown">
                <a id="nav__mnu_dashboard" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php mlang_str('NAV-DASHBOARD_MENU'); ?><span class="caret"></span></a>
              <ul class="dropdown-menu">

                  <?php
                  // Build out the dashboard menu items
                  $dashbs = new dashboard();
                  $dashbs = $dashbs->getuserdashboards();

                  foreach($dashbs as $dashb) {
                      if((int)$_SESSION['dashboard'] === (int)$dashb['id']  ) {
                          echo "<li class='dashboard_links selected' value='" . $dashb['id'] . "'><a> <span class=\"fa fa-check\" aria-hidden=\"true\"></span>   " . $dashb['name'] . "</a></li>";
                      }
                      else {
                          echo "<li class='dashboard_links' value='" . $dashb['id'] . "'><a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $dashb['name'] . "</a></li>";
                      }
                  }
                  ?>

                <?php if(has_permission('addeditdashboard')) : ?>
                    <li role="separator" class="divider"></li>
                    <li><a id="nav__lnk_addDashboard" href="#" data-toggle="modal" data-target="#modal_dashboard_addsingle"><?php mlang_str('NAV-DASHBOARD_ITEM_ADD'); ?>...</a></li>
                <?php endif; ?>
              </ul>
            </li>
          </ul>

        <?php endif; ?>

        <?php if(has_permission('blockmenu')) : ?>

            <ul class="nav navbar-nav">

                <li class="dropdown">
                    <a id="nav__mnu_block" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php mlang_str('NAV-BLOCKS_MENU'); ?><span class="caret"></span></a>
                    <ul class="dropdown-menu">

                        <?php if(has_permission('addeditchart')) : ?>
                            <li><a id="nav__lnk_addchart" href="#" data-toggle="modal" data-target="#modal_addchart"><?php mlang_str('NAV-BLOCKS_ITEM_CHART'); ?></a></li>
                        <?php endif; ?>

                        <?php if(has_permission('addedithtml')) : ?>
                            <li><a id="nav__lnk_addhtml" href="#" data-toggle="modal" data-target="#modal_htmledit"><?php mlang_str('NAV-BLOCKS_ITEM_HTML'); ?></a></li>
                        <?php endif; ?>

                    </ul>
                </li>
            </ul>

        <?php endif; ?>

      <ul class="nav navbar-nav navbar-right">

          <?php if(has_permission('adminmenu')) : ?>

          <li class="dropdown">
          <a id="nav__mnu_admin" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php mlang_str('NAV-ADMIN_MENU'); ?><span class="caret"></span></a>

              <ul class="dropdown-menu">

               <?php if(has_permission('adminusers')) : ?>
                    <li><a id="nav__lnk_adminusers" href="#" data-toggle="modal" data-target="#modal_users"><?php mlang_str('NAV-ADMIN_ITEM_USERS'); ?></a></li>
               <?php endif; ?>

              <?php if(has_permission('admindashboards')) : ?>
                  <li><a id="nav__lnk_adminassign" href="#" data-toggle="modal" data-target="#modal_dashboards"><?php mlang_str('NAV-ADMIN_ITEM_DASHBOARDS'); ?></a></li>
              <?php endif; ?>

              <li role="separator" class="divider"></li>

              <?php if(has_permission('adminthemes')) : ?>
                  <li><a id="nav__lnk_adminthemes" href="#" data-toggle="modal" data-target="#modal_themes"><?php mlang_str('NAV-ADMIN_ITEM_THEMES'); ?></a></li>
                  <li><a id="nav__lnk_admincolours" href="#" data-toggle="modal" data-target="#modal_colours"><?php mlang_str('NAV-ADMIN_ITEM_COLOURS'); ?></a></li>
              <?php endif; ?>

               <li role="separator" class="divider"></li>

              <?php if(has_permission('admingroups')) : ?>
                  <li><a id="nav__lnk_admingroups" href="#" data-toggle="modal" data-target="#modal_groups"><?php mlang_str('NAV-ADMIN_ITEM_GROUPS'); ?></a></li>
              <?php endif; ?>

              <li role="separator" class="divider"></li>

              <?php if(has_permission('adminroles')) : ?>
                  <li><a id="nav__lnk_adminroles" href="#" data-toggle="modal" data-target="#modal_roles"><?php mlang_str('NAV-ADMIN_ITEM_ROLES'); ?></a></li>
              <?php endif; ?>

              <li role="separator" class="divider"></li>

              <?php if(has_permission('admindataconnections')) : ?>
                   <li><a id="nav__lnk_addDataConnection" href="#" data-toggle="modal" data-target="#modal_dataconnections"><?php mlang_str('NAV-ADMIN_ITEM_DATACONECTIONS'); ?></a></li>
              <?php endif; ?>

              <li role="separator" class="divider"></li>
              <?php if(has_permission('adminloginas')) : ?>
                  <li><a id="nav__lnk_adminloginas" href="#" data-toggle="modal" data-target="#modal_loginas"><?php mlang_str('NAV-ADMIN_ITEM_LOGINAS'); ?></a></li>
              <?php endif; ?>

              <?php if(has_permission('adminpreferences')) : ?>
                  <li><a id="nav__lnk_adminpreferences" href="#" data-toggle="modal" data-target="#modal_preferences"><?php mlang_str('NAV-ADMIN_ITEM_PREFERENCES'); ?></a></li>
              <?php endif; ?>

          </ul>
        </li>
        
        <?php endif; ?>

          <li class="dropdown">
          <a id="nav__mnu_language" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php mlang_str('NAV-LANGUAGE_MENU'); ?><span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a id="nav__lnk_langenglish" href="<?php echo strtok($_SERVER["REQUEST_URI"],'?')."?lang=en" ;?>"><?php mlang_str('NAV-LANGUAGE_ITEM_EN'); ?></a></li>
            <li><a id="nav__lnk_langfrench" href="<?php echo strtok($_SERVER["REQUEST_URI"],'?')."?lang=fr" ;?>"><?php mlang_str('NAV-LANGUAGE_ITEM_FR'); ?></a></li>
          </ul>
        </li>
        
          <li class="dropdown">
          <a id="nav__mnu_usermenu" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $login_session;?><span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a id="nav__lnk_userprofile" href="#" data-toggle="modal" data-target="#modal_useredit" data-targetself="true" ><?php mlang_str('NAV-USER_ITEM_PROFILE'); ?></a></li>
            <li role="separator" class="divider"></li>
            <li><a id="nav__lnk_usersignout" href="#" data-toggle="modal" data-target="#modal_signout"><?php mlang_str('NAV-USER_ITEM_SIGNOUT'); ?></a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
