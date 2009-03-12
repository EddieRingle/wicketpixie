<?php
  /*
   Plugin Name: StatPress Reloaded
   Plugin URI: http://blog.matrixagents.org/wp-plugins/
   Description: Improved real time stats for your blog
   Version: 1.5.13
   Author: Manuel Grabowski
   Author URI: http://blog.matrixagents.org/
   */
  
  $_STATPRESS['version'] = '1.5.13';
  $_STATPRESS['feedtype'] = '';
  
  
  if ($_GET['statpress_action'] == 'exportnow')
  {
      iriStatPressExportNow();
  }
  
  function iri_add_pages()
  {
      // Create table if it doesn't exist
      global $wpdb;
      $table_name = $wpdb->prefix . 'statpress';
      if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
      {
          iri_StatPress_CreateTable();
      }
      
      // add submenu
      $mincap = get_option('statpress_mincap');
      if ($mincap == '')
      {
          $mincap = 'level_8';
      }


      add_menu_page('StatPress Reloaded', 'StatPress', $mincap, basename(__FILE__), 'iriStatPress');
      add_submenu_page(basename(__FILE__), __('Details', 'statpress'), __('Details', 'statpress'), $mincap, basename(__FILE__) . '&statpress_action=details', 'iriStatPressDetails');
      add_submenu_page(basename(__FILE__), __('Spy', 'statpress'), __('Spy', 'statpress'), $mincap, basename(__FILE__) . '&statpress_action=spy', 'iriStatPressSpy');
      add_submenu_page(basename(__FILE__), __('Search', 'statpress'), __('Search', 'statpress'), $mincap, basename(__FILE__) . '&statpress_action=search', 'iriStatPressSearch');
      add_submenu_page(basename(__FILE__), __('Export', 'statpress'), __('Export', 'statpress'), $mincap, basename(__FILE__) . '&statpress_action=export', 'iriStatPressExport');
      add_submenu_page(basename(__FILE__), __('Options', 'statpress'), __('Options', 'statpress'), $mincap, basename(__FILE__) . '&statpress_action=options', 'iriStatPressOptions');
      add_submenu_page(basename(__FILE__), __('User Agents', 'statpress'), __('User Agents', 'statpress'), $mincap, basename(__FILE__) . '&statpress_action=agents', 'iriStatPressAgents');
      add_submenu_page(basename(__FILE__), __('StatPressUpdate', 'statpress'), __('StatPressUpdate', 'statpress'), $mincap, basename(__FILE__) . '&statpress_action=up', 'iriStatPressUpdate');
      //add_submenu_page(basename(__FILE__), __('Support','statpress'), __('Support','statpress'), $mincap, 'http://matrixagents.org/phpBB/viewforum.php?f=3');
  }
  
  function permalinksEnabled()
  {
      global $wpdb;
      
      $result = $wpdb->get_row('SELECT `option_value` FROM `' . $wpdb->prefix . 'options` WHERE `option_name` = "permalink_structure"');
      if ($result->option_value != '')
      {
          return true;
      }
      else
      {
          return false;
      }
  }
  
  function my_substr($str, $x, $y = 0)
  {
  	if($y == 0)
  	{
  		$y = strlen($str) - $x;
  	}
 	if(function_exists('mb_substr'))
 	{
 		return mb_substr($str, $x, $y);
 	}
 	else
 	{
 		return substr($str, $x, $y);
 	}
  }
  
  
  function iriStatPress()
  {
      if ($_GET['statpress_action'] == 'export')
      {
          iriStatPressExport();
      }
      elseif ($_GET['statpress_action'] == 'up')
      {
          iriStatPressUpdate();
      }
      elseif ($_GET['statpress_action'] == 'spy')
      {
          iriStatPressSpy();
      }
      elseif ($_GET['statpress_action'] == 'search')
      {
          iriStatPressSearch();
      }
      elseif ($_GET['statpress_action'] == 'details')
      {
          iriStatPressDetails();
      }
      elseif ($_GET['statpress_action'] == 'options')
      {
          iriStatPressOptions();
      }
      elseif ($_GET['statpress_action'] == 'overview')
      {
          iriStatPressMain();
      }
      elseif ($_GET['statpress_action'] == 'agents')
      {
          iriStatPressAgents();
      }
      else
      {
          iriStatPressMain();
      }
  }
  
  function iriStatPressOptions()
  {
      if ($_POST['saveit'] == 'yes')
      {
          update_option('statpress_collectloggeduser', $_POST['statpress_collectloggeduser']);
          update_option('statpress_autodelete', $_POST['statpress_autodelete']);
          update_option('statpress_daysinoverviewgraph', $_POST['statpress_daysinoverviewgraph']);
          update_option('statpress_mincap', $_POST['statpress_mincap']);
          update_option('statpress_donotcollectspider', $_POST['statpress_donotcollectspider']);
          update_option('statpress_autodelete_spider', $_POST['statpress_autodelete_spider']);
          
          // update database too
          iri_StatPress_CreateTable();
          print "<br /><div class='updated'><p>" . __('Saved', 'statpress') . "!</p></div>";
      }
      else
      {
?>
  <div class='wrap'><h2><?php
          _e('Options', 'statpress');
?></h2>
  <form method=post><table width=100%>
<?php
          print "<tr><td><input type=checkbox name='statpress_collectloggeduser' value='checked' " . get_option('statpress_collectloggeduser') . "> " . __('Collect data about logged users, too.', 'statpress') . "</td></tr>";
          print "<tr><td><input type=checkbox name='statpress_donotcollectspider' value='checked' " . get_option('statpress_donotcollectspider') . "> " . __('Do not collect spiders visits', 'statpress') . "</td></tr>";
?>
  <tr><td><?php
          _e('Automatically delete visits older than', 'statpress');
?>
  <select name="statpress_autodelete">
  <option value="" <?php
          if (get_option('statpress_autodelete') == '')
              print "selected";
?>><?php
          _e('Never delete!', 'statpress');
?></option>
  <option value="1 month" <?php
          if (get_option('statpress_autodelete') == "1 month")
              print "selected";
?>>1 <?php
          _e('month', 'statpress');
?></option>
  <option value="3 months" <?php
          if (get_option('statpress_autodelete') == "3 months")
              print "selected";
?>>3 <?php
          _e('months', 'statpress');
?></option>
  <option value="6 months" <?php
          if (get_option('statpress_autodelete') == "6 months")
              print "selected";
?>>6 <?php
          _e('months', 'statpress');
?></option>
  <option value="1 year" <?php
          if (get_option('statpress_autodelete') == "1 year")
              print "selected";
?>>1 <?php
          _e('year', 'statpress');
?></option>
  </select></td></tr>
  
  <tr><td><?php _e('Automatically delete spider visits older than','statpress'); ?>
  <select name="statpress_autodelete_spider">
  <option value="" <?php if(get_option('statpress_autodelete_spider') =='' ) print "selected"; ?>><?php _e('Never delete!','statpress'); ?></option>
  <option value="1 day" <?php if(get_option('statpress_autodelete_spider') == "1 day") print "selected"; ?>>1 <?php _e('day','statpress'); ?></option>
  <option value="1 week" <?php if(get_option('statpress_autodelete_spider') == "1 week") print "selected"; ?>>1 <?php _e('week','statpress'); ?></option>
  <option value="1 month" <?php if(get_option('statpress_autodelete_spider') == "1 month") print "selected"; ?>>1 <?php _e('month','statpress'); ?></option>
  <option value="1 year" <?php if(get_option('statpress_autodelete_spider') == "1 year") print "selected"; ?>>1 <?php _e('year','statpress'); ?></option>
  </select></td></tr>

  <tr><td><?php
          _e('Days in Overview graph', 'statpress');
?>
  <select name="statpress_daysinoverviewgraph">
  <option value="7" <?php
          if (get_option('statpress_daysinoverviewgraph') == 7)
              print "selected";
?>>7</option>
  <option value="10" <?php
          if (get_option('statpress_daysinoverviewgraph') == 10)
              print "selected";
?>>10</option>
  <option value="20" <?php
          if (get_option('statpress_daysinoverviewgraph') == 20)
              print "selected";
?>>20</option>
  <option value="30" <?php
          if (get_option('statpress_daysinoverviewgraph') == 30)
              print "selected";
?>>30</option>
  <option value="50" <?php
          if (get_option('statpress_daysinoverviewgraph') == 50)
              print "selected";
?>>50</option>
  </select></td></tr>

  <tr><td><?php
          _e('Minimum capability to view stats', 'statpress');
?>
  <select name="statpress_mincap">
<?php
          iri_dropdown_caps(get_option('statpress_mincap'));
?>
  </select> 
  <a href="http://codex.wordpress.org/Roles_and_Capabilities" target="_blank"><?php
          _e("more info", 'statpress');
?></a>
  </td></tr>
  
  <tr><td><br><input type=submit value="<?php
          _e('Save options', 'statpress');
?>"></td></tr>
  </tr>
  </table>
  <input type=hidden name=saveit value=yes>
  <input type=hidden name=page value=statpress><input type=hidden name=statpress_action value=options>
  </form>
  </div>
<?php
          } // chiude saveit
      }
      
      
      function iri_dropdown_caps($default = false)
      {
          global $wp_roles;
          $role = get_role('administrator');
          foreach ($role->capabilities as $cap => $grant)
          {
              print "<option ";
              if ($default == $cap)
              {
                  print "selected ";
              }
              print ">$cap</option>";
          }
      }
      
      
      function iriStatPressExport()
      {
?>
  <div class='wrap'><h2><?php
          _e('Export stats to text file', 'statpress');
?> (csv)</h2>
  <form method=get><table>
  <tr><td><?php
          _e('From', 'statpress');
?></td><td><input type=text name=from> (YYYYMMDD)</td></tr>
  <tr><td><?php
          _e('To', 'statpress');
?></td><td><input type=text name=to> (YYYYMMDD)</td></tr>
  <tr><td><?php
          _e('Fields delimiter', 'statpress');
?></td><td><select name=del><option>,</option><option>;</option><option>|</option></select></tr>
  <tr><td></td><td><input type=submit value=<?php
          _e('Export', 'statpress');
?>></td></tr>
  <input type=hidden name=page value=statpress><input type=hidden name=statpress_action value=exportnow>
  </table></form>
  </div>
<?php
      }
      
      
      function iriStatPressExportNow()
      {
          global $wpdb;
          $table_name = $wpdb->prefix . "statpress";
          $filename = get_bloginfo('title') . "-statpress_" . $_GET['from'] . "-" . $_GET['to'] . ".csv";
          header('Content-Description: File Transfer');
          header("Content-Disposition: attachment; filename=$filename");
          header('Content-Type: text/plain charset=' . get_option('blog_charset'), true);
          $qry = $wpdb->get_results("SELECT * FROM $table_name WHERE date>='" . (date("Ymd", strtotime(my_substr($_GET['from'], 0, 8)))) . "' AND date<='" . (date("Ymd", strtotime(my_substr($_GET['to'], 0, 8)))) . "';");
          $del = my_substr($_GET['del'], 0, 1);
          print "date" . $del . "time" . $del . "ip" . $del . "urlrequested" . $del . "agent" . $del . "referrer" . $del . "search" . $del . "nation" . $del . "os" . $del . "browser" . $del . "searchengine" . $del . "spider" . $del . "feed\n";
          foreach ($qry as $rk)
          {
              print '"' . $rk->date . '"' . $del . '"' . $rk->time . '"' . $del . '"' . $rk->ip . '"' . $del . '"' . $rk->urlrequested . '"' . $del . '"' . $rk->agent . '"' . $del . '"' . $rk->referrer . '"' . $del . '"' . $rk->search . '"' . $del . '"' . $rk->nation . '"' . $del . '"' . $rk->os . '"' . $del . '"' . $rk->browser . '"' . $del . '"' . $rk->searchengine . '"' . $del . '"' . $rk->spider . '"' . $del . '"' . $rk->feed . '"' . "\n";
          }
          die();
      }
      
      function iriStatPressMain()
      {
          global $wpdb;
          $table_name = $wpdb->prefix . "statpress";
          
          // OVERVIEW table
          $unique_color = "#114477";
          $web_color = "#3377B6";
          $rss_color = "#f38f36";
          $spider_color = "#83b4d8";
          $lastmonth = iri_StatPress_lastmonth();
          $thismonth = gmdate('Ym', current_time('timestamp'));
          $yesterday = gmdate('Ymd', current_time('timestamp') - 86400);
          $today = gmdate('Ymd', current_time('timestamp'));
          $tlm[0] = my_substr($lastmonth, 0, 4);
          $tlm[1] = my_substr($lastmonth, 4, 2);
          
          print "<div class='wrap'><h2>" . __('Overview', 'statpress') . "</h2>";
          print "<table class='widefat'><thead><tr>
  <th scope='col'></th>
  <th scope='col'>" . __('Total', 'statpress') . "</th>
  <th scope='col'>" . __('Last month', 'statpress') . "<br /><font size=1>" . gmdate('M, Y', gmmktime(0, 0, 0, $tlm[1], 1, $tlm[0])) . "</font></th>
  <th scope='col'>" . __('This month', 'statpress') . "<br /><font size=1>" . gmdate('M, Y', current_time('timestamp')) . "</font></th>
  <th scope='col'>" . __('Target', 'statpress') . " " . __('This month', 'statpress') . "<br /><font size=1>" . gmdate('M, Y', current_time('timestamp')) . "</font></th>
  <th scope='col'>" . __('Yesterday', 'statpress') . "<br /><font size=1>" . gmdate('d M, Y', current_time('timestamp') - 86400) . "</font></th>
  <th scope='col'>" . __('Today', 'statpress') . "<br /><font size=1>" . gmdate('d M, Y', current_time('timestamp')) . "</font></th>
  </tr></thead>
  <tbody id='the-list'>";
          
          //###############################################################################################
          // VISITORS ROW
          print "<tr><td><div style='background:$unique_color;width:10px;height:10px;float:left;margin-top:4px;margin-right:5px;'></div>" . __('Visitors', 'statpress') . "</td>";
          
          //TOTAL
          $qry_total = $wpdb->get_row("
    SELECT count(DISTINCT ip) AS visitors
    FROM $table_name
    WHERE feed=''
    AND spider=''
  ");
          print "<td>" . $qry_total->visitors . "</td>\n";
          
          //LAST MONTH
          $qry_lmonth = $wpdb->get_row("
    SELECT count(DISTINCT ip) AS visitors
    FROM $table_name
    WHERE feed=''
    AND spider=''
    AND date LIKE '" . mysql_real_escape_string($lastmonth) . "%'
  ");
          print "<td>" . $qry_lmonth->visitors . "</td>\n";
          
          //THIS MONTH
          $qry_tmonth = $wpdb->get_row("
    SELECT count(DISTINCT ip) AS visitors
    FROM $table_name
    WHERE feed=''
    AND spider=''
    AND date LIKE '" . mysql_real_escape_string($thismonth) . "%'
  ");
          if ($qry_lmonth->visitors <> 0)
          {
              $pc = round(100 * ($qry_tmonth->visitors / $qry_lmonth->visitors) - 100, 1);
              if ($pc >= 0)
                  $pc = "+" . $pc;
              $qry_tmonth->change = "<code> (" . $pc . "%)</code>";
          }
          print "<td>" . $qry_tmonth->visitors . $qry_tmonth->change . "</td>\n";
          
          //TARGET
          
          $qry_tmonth->target = round($qry_tmonth->visitors / (time() - mktime(0,0,0,date('m'),date('1'),date('Y'))) * (86400 * date('t')));
          if ($qry_lmonth->visitors <> 0)
          {
              $pt = round(100 * ($qry_tmonth->target / $qry_lmonth->visitors) - 100, 1);
              if ($pt >= 0)
                  $pt = "+" . $pt;
              $qry_tmonth->added = "<code> (" . $pt . "%)</code>";
          }
          print "<td>" . $qry_tmonth->target . $qry_tmonth->added . "</td>\n";
          
          //YESTERDAY
          $qry_y = $wpdb->get_row("
    SELECT count(DISTINCT ip) AS visitors
    FROM $table_name
    WHERE feed=''
    AND spider=''
    AND date = '" . mysql_real_escape_string($yesterday) . "'
  ");
          print "<td>" . $qry_y->visitors . "</td>\n";
          
          //TODAY
          $qry_t = $wpdb->get_row("
    SELECT count(DISTINCT ip) AS visitors
    FROM $table_name
    WHERE feed=''
    AND spider=''
    AND date = '" . mysql_real_escape_string($today) . "'
  ");
          print "<td>" . $qry_t->visitors . "</td>\n";
          print "</tr>";
          
          //###############################################################################################
          // PAGEVIEWS ROW
          print "<tr><td><div style='background:$web_color;width:10px;height:10px;float:left;margin-top:4px;margin-right:5px;'></div>" . __('Pageviews', 'statpress') . "</td>";
          
          //TOTAL
          $qry_total = $wpdb->get_row("
    SELECT count(date) as pageview
    FROM $table_name
    WHERE feed=''
    AND spider=''
  ");
          print "<td>" . $qry_total->pageview . "</td>\n";
          
          //LAST MONTH
          $prec = 0;
          $qry_lmonth = $wpdb->get_row("
    SELECT count(date) as pageview
    FROM $table_name
    WHERE feed=''
    AND spider=''
    AND date LIKE '" . mysql_real_escape_string($lastmonth) . "%'
  ");
          print "<td>" . $qry_lmonth->pageview . "</td>\n";
          
          //THIS MONTH
          $qry_tmonth = $wpdb->get_row("
    SELECT count(date) as pageview
    FROM $table_name
    WHERE feed=''
    AND spider=''
    AND date LIKE '" . mysql_real_escape_string($thismonth) . "%'
  ");
          if ($qry_lmonth->pageview <> 0)
          {
              $pc = round(100 * ($qry_tmonth->pageview / $qry_lmonth->pageview) - 100, 1);
              if ($pc >= 0)
                  $pc = "+" . $pc;
              $qry_tmonth->change = "<code> (" . $pc . "%)</code>";
          }
          print "<td>" . $qry_tmonth->pageview . $qry_tmonth->change . "</td>\n";
          
          //TARGET
          $qry_tmonth->target = round($qry_tmonth->pageview / (time() - mktime(0,0,0,date('m'),date('1'),date('Y'))) * (86400 * date('t')));
          if ($qry_lmonth->pageview <> 0)
          {
              $pt = round(100 * ($qry_tmonth->target / $qry_lmonth->pageview) - 100, 1);
              if ($pt >= 0)
                  $pt = "+" . $pt;
              $qry_tmonth->added = "<code> (" . $pt . "%)</code>";
          }
          print "<td>" . $qry_tmonth->target . $qry_tmonth->added . "</td>\n";
          
          //YESTERDAY
          $qry_y = $wpdb->get_row("
    SELECT count(date) as pageview
    FROM $table_name
    WHERE feed=''
    AND spider=''
    AND date = '" . mysql_real_escape_string($yesterday) . "'
  ");
          print "<td>" . $qry_y->pageview . "</td>\n";
          
          //TODAY
          $qry_t = $wpdb->get_row("
    SELECT count(date) as pageview
    FROM $table_name
    WHERE feed=''
    AND spider=''
    AND date = '" . mysql_real_escape_string($today) . "'
  ");
          print "<td>" . $qry_t->pageview . "</td>\n";
          print "</tr>";
          //###############################################################################################
          // SPIDERS ROW
          print "<tr><td><div style='background:$spider_color;width:10px;height:10px;float:left;margin-top:4px;margin-right:5px;'></div>" . __('Spiders', 'statpress') . "</td>";
          //TOTAL
          $qry_total = $wpdb->get_row("
    SELECT count(date) as spiders
    FROM $table_name
    WHERE feed=''
    AND spider<>''
  ");
          print "<td>" . $qry_total->spiders . "</td>\n";
          //LAST MONTH
          $prec = 0;
          $qry_lmonth = $wpdb->get_row("
    SELECT count(date) as spiders
    FROM $table_name
    WHERE feed=''
    AND spider<>''
    AND date LIKE '" . mysql_real_escape_string($lastmonth) . "%'
  ");
          print "<td>" . $qry_lmonth->spiders . "</td>\n";
          
          //THIS MONTH
          $prec = $qry_lmonth->spiders;
          $qry_tmonth = $wpdb->get_row("
    SELECT count(date) as spiders
    FROM $table_name
    WHERE feed=''
    AND spider<>''
    AND date LIKE '" . mysql_real_escape_string($thismonth) . "%'
  ");
          if ($qry_lmonth->spiders <> 0)
          {
              $pc = round(100 * ($qry_tmonth->spiders / $qry_lmonth->spiders) - 100, 1);
              if ($pc >= 0)
                  $pc = "+" . $pc;
              $qry_tmonth->change = "<code> (" . $pc . "%)</code>";
          }
          print "<td>" . $qry_tmonth->spiders . $qry_tmonth->change . "</td>\n";
          
          //TARGET
          $qry_tmonth->target = round($qry_tmonth->spiders / (time() - mktime(0,0,0,date('m'),date('1'),date('Y'))) * (86400 * date('t')));
          if ($qry_lmonth->spiders <> 0)
          {
              $pt = round(100 * ($qry_tmonth->target / $qry_lmonth->spiders) - 100, 1);
              if ($pt >= 0)
                  $pt = "+" . $pt;
              $qry_tmonth->added = "<code> (" . $pt . "%)</code>";
          }
          print "<td>" . $qry_tmonth->target . $qry_tmonth->added . "</td>\n";
          
          //YESTERDAY
          $qry_y = $wpdb->get_row("
    SELECT count(date) as spiders
    FROM $table_name
    WHERE feed=''
    AND spider<>''
    AND date = '" . mysql_real_escape_string($yesterday) . "'
  ");
          print "<td>" . $qry_y->spiders . "</td>\n";
          
          //TODAY
          $qry_t = $wpdb->get_row("
    SELECT count(date) as spiders
    FROM $table_name
    WHERE feed=''
    AND spider<>''
    AND date = '" . mysql_real_escape_string($today) . "'
  ");
          print "<td>" . $qry_t->spiders . "</td>\n";
          print "</tr>";
          //###############################################################################################
          // FEEDS ROW
          print "<tr><td><div style='background:$rss_color;width:10px;height:10px;float:left;margin-top:4px;margin-right:5px;'></div>" . __('Feeds', 'statpress') . "</td>";
          //TOTAL
          $qry_total = $wpdb->get_row("
    SELECT count(date) as feeds
    FROM $table_name
    WHERE feed<>''
    AND spider=''
  ");
          print "<td>" . $qry_total->feeds . "</td>\n";
          
          //LAST MONTH
          $qry_lmonth = $wpdb->get_row("
    SELECT count(date) as feeds
    FROM $table_name
    WHERE feed<>''
    AND spider=''
    AND date LIKE '" . mysql_real_escape_string($lastmonth) . "%'
  ");
          print "<td>" . $qry_lmonth->feeds . "</td>\n";
          
          //THIS MONTH
          $qry_tmonth = $wpdb->get_row("
    SELECT count(date) as feeds
    FROM $table_name
    WHERE feed<>''
    AND spider=''
    AND date LIKE '" . mysql_real_escape_string($thismonth) . "%'
  ");
          if ($qry_lmonth->feeds <> 0)
          {
              $pc = round(100 * ($qry_tmonth->feeds / $qry_lmonth->feeds) - 100, 1);
              if ($pc >= 0)
                  $pc = "+" . $pc;
              $qry_tmonth->change = "<code> (" . $pc . "%)</code>";
          }
          print "<td>" . $qry_tmonth->feeds . $qry_tmonth->change . "</td>\n";
          
          //TARGET
          $qry_tmonth->target = round($qry_tmonth->feeds / (time() - mktime(0,0,0,date('m'),date('1'),date('Y'))) * (86400 * date('t')));
          if ($qry_lmonth->feeds <> 0)
          {
              $pt = round(100 * ($qry_tmonth->target / $qry_lmonth->feeds) - 100, 1);
              if ($pt >= 0)
                  $pt = "+" . $pt;
              $qry_tmonth->added = "<code> (" . $pt . "%)</code>";
          }
          print "<td>" . $qry_tmonth->target . $qry_tmonth->added . "</td>\n";
          
          $qry_y = $wpdb->get_row("
    SELECT count(date) as feeds
    FROM $table_name
    WHERE feed<>''
    AND spider=''
    AND date = '" . mysql_real_escape_string($yesterday) . "'
  ");
          print "<td>" . $qry_y->feeds . "</td>\n";
          
          $qry_t = $wpdb->get_row("
    SELECT count(date) as feeds
    FROM $table_name
    WHERE feed<>''
    AND spider=''
    AND date = '" . mysql_real_escape_string($today) . "'
  ");
          print "<td>" . $qry_t->feeds . "</td>\n";
          
          print "</tr></table><br />\n\n";
          
          //###############################################################################################
          //###############################################################################################
          // THE GRAPHS
          
          // last "N" days graph  NEW
          $gdays = get_option('statpress_daysinoverviewgraph');
          if ($gdays == 0)
          {
              $gdays = 20;
          }
          //  $start_of_week = get_settings('start_of_week');
          $start_of_week = get_option('start_of_week');
          print '<table width="100%" border="0"><tr>';
          $qry = $wpdb->get_row("
    SELECT count(date) as pageview, date
    FROM $table_name
    GROUP BY date HAVING date >= '" . gmdate('Ymd', current_time('timestamp') - 86400 * $gdays) . "'
    ORDER BY pageview DESC
    LIMIT 1
  ");
          $maxxday = $qry->pageview;
          if ($maxxday == 0)
          {
              $maxxday = 1;
          }
          // Y
          $gd = (90 / $gdays) . '%';
          for ($gg = $gdays - 1; $gg >= 0; $gg--)
          {
              //TOTAL VISITORS
              $qry_visitors = $wpdb->get_row("
      SELECT count(DISTINCT ip) AS total
      FROM $table_name
      WHERE feed=''
      AND spider=''
      AND date = '" . gmdate('Ymd', current_time('timestamp') - 86400 * $gg) . "'
    ");
              $px_visitors = round($qry_visitors->total * 100 / $maxxday);
              
              //TOTAL PAGEVIEWS (we do not delete the uniques, this is falsing the info.. uniques are not different visitors!)
              $qry_pageviews = $wpdb->get_row("
      SELECT count(date) as total
      FROM $table_name
      WHERE feed=''
      AND spider=''
      AND date = '" . gmdate('Ymd', current_time('timestamp') - 86400 * $gg) . "'
    ");
              $px_pageviews = round($qry_pageviews->total * 100 / $maxxday);
              
              //TOTAL SPIDERS
              $qry_spiders = $wpdb->get_row("
      SELECT count(ip) AS total
      FROM $table_name
      WHERE feed=''
      AND spider<>''
      AND date = '" . gmdate('Ymd', current_time('timestamp') - 86400 * $gg) . "'
    ");
              $px_spiders = round($qry_spiders->total * 100 / $maxxday);
              
              //TOTAL FEEDS
              $qry_feeds = $wpdb->get_row("
      SELECT count(ip) AS total
      FROM $table_name
      WHERE feed<>''
      AND spider=''
      AND date = '" . gmdate('Ymd', current_time('timestamp') - 86400 * $gg) . "'
    ");
              $px_feeds = round($qry_feeds->total * 100 / $maxxday);
              
              $px_white = 100 - $px_feeds - $px_spiders - $px_pageviews - $px_visitors;
              
              print '<td width="' . $gd . '" valign="bottom"';
              if ($start_of_week == gmdate('w', current_time('timestamp') - 86400 * $gg))
              {
                  print ' style="border-left:2px dotted gray;"';
              }
              // week-cut
              print "><div style='float:left;height: 100%;width:100%;font-family:Helvetica;font-size:7pt;text-align:center;border-right:1px solid white;color:black;'>
    <div style='background:#ffffff;width:100%;height:" . $px_white . "px;'></div>
    <div style='background:$unique_color;width:100%;height:" . $px_visitors . "px;' title='" . $qry_visitors->total . " " . __('visitors', 'statpress')."'></div>
    <div style='background:$web_color;width:100%;height:" . $px_pageviews . "px;' title='" . $qry_pageviews->total . " " . __('pageviews', 'statpress')."'></div>
    <div style='background:$spider_color;width:100%;height:" . $px_spiders . "px;' title='" . $qry_spiders->total . " " . __('spiders', 'statpress')."'></div>
    <div style='background:$rss_color;width:100%;height:" . $px_feeds . "px;' title='" . $qry_feeds->total . " " . __('feeds', 'statpress')."'></div>
    <div style='background:gray;width:100%;height:1px;'></div>
    <br />" . gmdate('d', current_time('timestamp') - 86400 * $gg) . ' ' . gmdate('M', current_time('timestamp') - 86400 * $gg) . "</div></td>\n";
          }
          print '</tr></table>';
          
          print '</div>';
          // END OF OVERVIEW
          //###################################################################################################
          
          
          
          
          $querylimit = "LIMIT 20";
          
          // Tabella Last hits
          print "<div class='wrap'><h2>" . __('Last hits', 'statpress') . "</h2><table class='widefat'><thead><tr><th scope='col'>" . __('Date', 'statpress') . "</th><th scope='col'>" . __('Time', 'statpress') . "</th><th scope='col'>" . __('IP', 'statpress') . "</th><th scope='col'>" . __('Threat', 'statpress') . "</th><th scope='col'>" . __('Domain', 'statpress') . "</th><th scope='col'>" . __('Page', 'statpress') . "</th><th scope='col'>" . __('OS', 'statpress') . "</th><th scope='col'>" . __('Browser', 'statpress') . "</th><th scope='col'>" . __('Feed', 'statpress') . "</th></tr></thead>";
          print "<tbody id='the-list'>";
          
          $fivesdrafts = $wpdb->get_results("SELECT * FROM $table_name WHERE (os<>'' OR feed<>'') order by id DESC $querylimit");
          foreach ($fivesdrafts as $fivesdraft)
          {
              print "<tr>";
              print "<td>" . irihdate($fivesdraft->date) . "</td>";
              print "<td>" . $fivesdraft->time . "</td>";
              print "<td>" . $fivesdraft->ip . "</td>";
              print "<td>" . $fivesdraft->threat_score;
              if ($fivesdraft->threat_score > 0)
              {
                  print "/";
                  if ($fivesdraft->threat_type == 0)
                      print "Sp"; // Spider
                  else
                  {
                      if (($fivesdraft->threat_type & 1) == 1)
                          print "S"; // Suspicious
                      if (($fivesdraft->threat_type & 2) == 2)
                          print "H"; // Harvester
                      if (($fivesdraft->threat_type & 4) == 4)
                          print "C"; // Comment spammer
                  }
              }
              print "<td>" . $fivesdraft->nation . "</td>";
              print "<td>" . iri_StatPress_Abbrevia(iri_StatPress_Decode($fivesdraft->urlrequested), 30) . "</td>";
              print "<td>" . $fivesdraft->os . "</td>";
              print "<td>" . $fivesdraft->browser . "</td>";
              print "<td>" . $fivesdraft->feed . "</td>";
              print "</tr>";
          }
          print "</table></div>";
          
          
          // Last Search terms
          print "<div class='wrap'><h2>" . __('Last search terms', 'statpress') . "</h2><table class='widefat'><thead><tr><th scope='col'>" . __('Date', 'statpress') . "</th><th scope='col'>" . __('Time', 'statpress') . "</th><th scope='col'>" . __('Terms', 'statpress') . "</th><th scope='col'>" . __('Engine', 'statpress') . "</th><th scope='col'>" . __('Result', 'statpress') . "</th></tr></thead>";
          print "<tbody id='the-list'>";
          $qry = $wpdb->get_results("SELECT date,time,referrer,urlrequested,search,searchengine FROM $table_name WHERE search<>'' ORDER BY id DESC $querylimit");
          foreach ($qry as $rk)
          {
              print "<tr><td>" . irihdate($rk->date) . "</td><td>" . $rk->time . "</td><td><a href='" . $rk->referrer . "'>" . $rk->search . "</a></td><td>" . $rk->searchengine . "</td><td><a href='" . irigetblogurl() . ((strpos($rk->urlrequested, 'index.php') === FALSE) ? $rk->urlrequested : '') . "'>" . __('page viewed', 'statpress') . "</a></td></tr>\n";
          }
          print "</table></div>";
          
          // Referrer
          print "<div class='wrap'><h2>" . __('Last referrers', 'statpress') . "</h2><table class='widefat'><thead><tr><th scope='col'>" . __('Date', 'statpress') . "</th><th scope='col'>" . __('Time', 'statpress') . "</th><th scope='col'>" . __('URL', 'statpress') . "</th><th scope='col'>" . __('Result', 'statpress') . "</th></tr></thead>";
          print "<tbody id='the-list'>";
          $qry = $wpdb->get_results("SELECT date,time,referrer,urlrequested FROM $table_name WHERE ((referrer NOT LIKE '" . get_option('home') . "%') AND (referrer <>'') AND (searchengine='')) ORDER BY id DESC $querylimit");
          foreach ($qry as $rk)
          {
              print "<tr><td>" . irihdate($rk->date) . "</td><td>" . $rk->time . "</td><td><a href='" . $rk->referrer . "'>" . iri_StatPress_Abbrevia($rk->referrer, 80) . "</a></td><td><a href='" . irigetblogurl() . ((strpos($rk->urlrequested, 'index.php') === FALSE) ? $rk->urlrequested : '') . "'>" . __('page viewed', 'statpress') . "</a></td></tr>\n";
          }
          print "</table></div>";
          
          // Last Agents
          print "<div class='wrap'><h2>" . __('Last agents', 'statpress') . "</h2><table class='widefat'><thead><tr><th scope='col'>" . __('Date', 'statpress') . "</th><th scope='col'>" . __('Time', 'statpress') . "</th><th scope='col'>" . __('Agent', 'statpress') . "</th><th scope='col'>" . __('What', 'statpress') . "</th></tr></thead>";
          print "<tbody id='the-list'>";
          $qry = $wpdb->get_results("SELECT date,time,agent,os,browser,spider FROM $table_name WHERE (agent <>'') ORDER BY id DESC $querylimit");
          foreach ($qry as $rk)
          {
              print "<tr><td>" . irihdate($rk->date) . "</td><td>" . $rk->time . "</td><td>" . $rk->agent . "</td><td> " . $rk->os . " " . $rk->browser . " " . $rk->spider . "</td></tr>\n";
          }
          print "</table></div>";
          
          // Last pages
          print "<div class='wrap'><h2>" . __('Last pages', 'statpress') . "</h2><table class='widefat'><thead><tr><th scope='col'>" . __('Date', 'statpress') . "</th><th scope='col'>" . __('Time', 'statpress') . "</th><th scope='col'>" . __('Page', 'statpress') . "</th><th scope='col'>" . __('What', 'statpress') . "</th></tr></thead>";
          print "<tbody id='the-list'>";
          $qry = $wpdb->get_results("SELECT date,time,urlrequested,os,browser,spider FROM $table_name WHERE (spider='' AND feed='') ORDER BY id DESC $querylimit");
          foreach ($qry as $rk)
          {
              print "<tr><td>" . irihdate($rk->date) . "</td><td>" . $rk->time . "</td><td>" . iri_StatPress_Abbrevia(iri_StatPress_Decode($rk->urlrequested), 60) . "</td><td> " . $rk->os . " " . $rk->browser . " " . $rk->spider . "</td></tr>\n";
          }
          print "</table></div>";
          
          // Last Spiders
          print "<div class='wrap'><h2>" . __('Last spiders', 'statpress') . "</h2>";
          print "<table class='widefat'><thead><tr>";
          print "<th scope='col'>" . __('Date', 'statpress') . "</th>";
          print "<th scope='col'>" . __('Time', 'statpress') . "</th>";
          print "<th scope='col'>" . __('Spider', 'statpress') . "</th>";
          print "<th scope='col'>" . __('Page', 'statpress') . "</th>";
          print "<th scope='col'>" . __('Agent', 'statpress') . "</th>";
          print "</tr></thead><tbody id='the-list'>";
          $qry = $wpdb->get_results("SELECT date,time,agent,spider,urlrequested,agent FROM $table_name WHERE (spider<>'') ORDER BY id DESC $querylimit");
          foreach ($qry as $rk)
          {
              print "<tr><td>" . irihdate($rk->date) . "</td>";
              print "<td>" . $rk->time . "</td>";
              print "<td>" . $rk->spider . "</td>";
              print "<td>" . iri_StatPress_Abbrevia(iri_StatPress_Decode($rk->urlrequested), 30) . "</td>";
              print "<td> " . $rk->agent . "</td></tr>\n";
          }
          print "</table></div>";
          
          
          print "<br />";
          print "&nbsp;<i>" . __('StatPress table size', 'statpress') . ": <b>" . iritablesize($wpdb->prefix . "statpress") . "</b></i><br />";
          print "&nbsp;<i>" . __('StatPress current time', 'statpress') . ": <b>" . current_time('mysql') . "</b></i><br />";
          print "&nbsp;<i>" . __('RSS2 url', 'statpress') . ": <b>" . get_bloginfo('rss2_url') . ' (' . iri_StatPress_extractfeedreq(get_bloginfo('rss2_url')) . ")</b></i><br />";
          print "&nbsp;<i>" . __('ATOM url', 'statpress') . ": <b>" . get_bloginfo('atom_url') . ' (' . iri_StatPress_extractfeedreq(get_bloginfo('atom_url')) . ")</b></i><br />";
          print "&nbsp;<i>" . __('RSS url', 'statpress') . ": <b>" . get_bloginfo('rss_url') . ' (' . iri_StatPress_extractfeedreq(get_bloginfo('rss_url')) . ")</b></i><br />";
          print "&nbsp;<i>" . __('COMMENT RSS2 url', 'statpress') . ": <b>" . get_bloginfo('comments_rss2_url') . ' (' . iri_StatPress_extractfeedreq(get_bloginfo('comments_rss2_url')) . ")</b></i><br />";
          print "&nbsp;<i>" . __('COMMENT ATOM url', 'statpress') . ": <b>" . get_bloginfo('comments_atom_url') . ' (' . iri_StatPress_extractfeedreq(get_bloginfo('comments_atom_url')) . ")</b></i><br />";
      }
      
      function iriStatPressDetails()
      {
          global $wpdb;
          $table_name = $wpdb->prefix . "statpress";
          
          $querylimit = "LIMIT 10";
          
          // Top days
          iriValueTable("date", __('Top days', 'statpress'), 5);
          
          // O.S.
          iriValueTable("os", __('O.S.', 'statpress'), 0, "", "", "AND feed='' AND spider='' AND os<>''");
          
          // Browser
          iriValueTable("browser", __('Browser', 'statpress'), 0, "", "", "AND feed='' AND spider='' AND browser<>''");
          
          // Feeds
          iriValueTable("feed", __('Feeds', 'statpress'), 5, "", "", "AND feed<>''");
          
          // SE
          iriValueTable("searchengine", __('Search engines', 'statpress'), 10, "", "", "AND searchengine<>''");
          
          // Search terms
          iriValueTable("search", __('Top search terms', 'statpress'), 20, "", "", "AND search<>''");
          
          // Top referrer
          iriValueTable("referrer", __('Top referrer', 'statpress'), 10, "", "", "AND referrer<>'' AND referrer NOT LIKE '%" . get_bloginfo('url') . "%'");
          
          // Countries
          iriValueTable("nation", __('Countries (domains)', 'statpress'), 10, "", "", "AND nation<>'' AND spider=''");
          
          // Spider
          iriValueTable("spider", __('Spiders', 'statpress'), 10, "", "", "AND spider<>''");
          
          // Top Pages
          iriValueTable("urlrequested", __('Top pages', 'statpress'), 5, "", "urlrequested", "AND feed='' and spider=''");
          
          
          // Top Days - Unique visitors
          iriValueTable("date", __('Top Days - Unique visitors', 'statpress'), 5, "distinct", "ip", "AND feed='' and spider=''");
          /* Maddler 04112007: required patching iriValueTable */
          
          // Top Days - Pageviews
          iriValueTable("date", __('Top Days - Pageviews', 'statpress'), 5, "", "urlrequested", "AND feed='' and spider=''");
          /* Maddler 04112007: required patching iriValueTable */
          
          // Top IPs - Pageviews
          iriValueTable("ip", __('Top IPs - Pageviews', 'statpress'), 5, "", "urlrequested", "AND feed='' and spider=''");
          /* Maddler 04112007: required patching iriValueTable */
      }
      
      
      function iriStatPressSpy()
      {
          global $wpdb;
          $table_name = $wpdb->prefix . "statpress";
          
          // Spy
          $today = gmdate('Ymd', current_time('timestamp'));
          $yesterday = gmdate('Ymd', current_time('timestamp') - 86400);
          print "<div class='wrap'><h2>" . __('Spy', 'statpress') . "</h2>";
          $sql = "SELECT ip,nation,os,browser,agent FROM $table_name WHERE (spider='' AND feed='') AND (date BETWEEN '$yesterday' AND '$today') GROUP BY ip ORDER BY id DESC LIMIT 20";
          $qry = $wpdb->get_results($sql);
?>
<script>
function ttogle(thediv){
if (document.getElementById(thediv).style.display=="inline") {
document.getElementById(thediv).style.display="none"
} else {document.getElementById(thediv).style.display="inline"}
}
</script>
<div align="center">
<table id="mainspytab" name="mainspytab" width="99%" border="0" cellspacing="0" cellpadding="4">
<?php
          foreach ($qry as $rk)
          {
              print "<tr><td colspan='2' bgcolor='#dedede'><div align='left'>";
              print "<IMG SRC='http://api.hostip.info/flag.php?ip=" . $rk->ip . "' border=0 width=18 height=12>";
              print " <strong><span><font size='2' color='#7b7b7b'>" . $rk->ip . "</font></span></strong> ";
              print "<span style='color:#006dca;cursor:pointer;border-bottom:1px dotted #AFD5F9;font-size:8pt;' onClick=ttogle('" . $rk->ip . "');>" . __('more info', 'statpress') . "</span></div>";
              print "<div id='" . $rk->ip . "' name='" . $rk->ip . "'>" . $rk->os . ", " . $rk->browser;
              //    print "<br><iframe style='overflow:hide;border:0px;width:100%;height:15px;font-family:helvetica;paddng:0;' scrolling='no' marginwidth=0 marginheight=0 src=http://showip.fakap.net/txt/".$rk->ip."></iframe>";
              print "<br><iframe style='overflow:hide;border:0px;width:100%;height:40px;font-family:helvetica;paddng:0;' scrolling='no' marginwidth=0 marginheight=0 src=http://api.hostip.info/get_html.php?ip=" . $rk->ip . "></iframe>";
              if ($rk->nation)
              {
                  print "<br><small>" . gethostbyaddr($rk->ip) . "</small>";
              }
              print "<br><small>" . $rk->agent . "</small>";
              print "</div>";
              print "<script>document.getElementById('" . $rk->ip . "').style.display='none';</script>";
              print "</td></tr>";
              $qry2 = $wpdb->get_results("SELECT * FROM $table_name WHERE ip='" . $rk->ip . "' AND (date BETWEEN '$yesterday' AND '$today') order by id LIMIT 10");
              foreach ($qry2 as $details)
              {
                  print "<tr>";
                  print "<td valign='top' width='151'><div><font size='1' color='#3B3B3B'><strong>" . irihdate($details->date) . " " . $details->time . "</strong></font></div></td>";
                  print "<td><div><a href='" . irigetblogurl() . ((strpos($details->urlrequested, 'index.php') === FALSE) ? $details->urlrequested : '') . "' target='_blank'>" . iri_StatPress_Decode($details->urlrequested) . "</a>";
                  if ($details->searchengine != '')
                  {
                      print "<br><small>" . __('arrived from', 'statpress') . " <b>" . $details->searchengine . "</b> " . __('searching', 'statpress') . " <a href='" . $details->referrer . "' target=_blank>" . $details->search . "</a></small>";
                  }
                  elseif ($details->referrer != '' && strpos($details->referrer, get_option('home')) === false)
                  {
                      print "<br><small>" . __('arrived from', 'statpress') . " <a href='" . $details->referrer . "' target=_blank>" . $details->referrer . "</a></small>";
                  }
                  print "</div></td>";
                  print "</tr>\n";
              }
          }
?>
</table>
</div>
<?php
      }
      
      
      function iriStatPressSearch($what = '')
      {
          global $wpdb;
          $table_name = $wpdb->prefix . "statpress";
          
          $f['urlrequested'] = __('URL Requested', 'statpress');
          $f['agent'] = __('Agent', 'statpress');
          $f['referrer'] = __('Referrer', 'statpress');
          $f['search'] = __('Search terms', 'statpress');
          $f['searchengine'] = __('Search engine', 'statpress');
          $f['os'] = __('Operative system', 'statpress');
          $f['browser'] = __('Browser', 'statpress');
          $f['spider'] = __('Spider', 'statpress');
          $f['ip'] = __('IP', 'statpress');
?>
  <div class='wrap'><h2><?php
          _e('Search', 'statpress');
?></h2>
  <form method=get><table>
  <?php
          for ($i = 1; $i <= 3; $i++)
          {
              print "<tr>";
              print "<td>" . __('Field', 'statpress') . " <select name=where$i><option value=''></option>";
              foreach (array_keys($f) as $k)
              {
                  print "<option value='$k'";
                  if ($_GET["where$i"] == $k)
                  {
                      print " SELECTED ";
                  }
                  print ">" . $f[$k] . "</option>";
              }
              print "</select></td>";
              print "<td><input type=checkbox name=groupby$i value='checked' " . $_GET["groupby$i"] . "> " . __('Group by', 'statpress') . "</td>";
              print "<td><input type=checkbox name=sortby$i value='checked' " . $_GET["sortby$i"] . "> " . __('Sort by', 'statpress') . "</td>";
              print "<td>, " . __('if contains', 'statpress') . " <input type=text name=what$i value='" . $_GET["what$i"] . "'></td>";
              print "</tr>";
          }
?>
  </table>
  <br>
  <table>
  <tr>
    <td>
      <table>
        <tr><td><input type=checkbox name=oderbycount value=checked <?php
          print $_GET['oderbycount']
?>> <?php
          _e('sort by count if grouped', 'statpress');
?></td></tr>
        <tr><td><input type=checkbox name=spider value=checked <?php
          print $_GET['spider']
?>> <?php
          _e('include spiders/crawlers/bot', 'statpress');
?></td></tr>
        <tr><td><input type=checkbox name=feed value=checked <?php
          print $_GET['feed']
?>> <?php
          _e('include feed', 'statpress');
?></td></tr>
<tr><td><input type=checkbox name=distinct value=checked <?php
          print $_GET['distinct']
?>> <?php
          _e('SELECT DISTINCT', 'statpress');
?></td></tr>
      </table>
    </td>
    <td width=15> </td>
    <td>
      <table>
        <tr>
          <td><?php
          _e('Limit results to', 'statpress');
?>
            <select name=limitquery><?php
          if ($_GET['limitquery'] > 0)
          {
              print "<option>" . $_GET['limitquery'] . "</option>";
          }
?><option>1</option><option>5</option><option>10</option><option>20</option><option>50</option><option>100</option><option>250</option><option>500</option></select>
          </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
          <td align=right><input type=submit value=<?php
          _e('Search', 'statpress');
?> name=searchsubmit></td>
        </tr>
      </table>
    </td>
  </tr>    
  </table>  
  <input type=hidden name=page value='statpress-reloaded/statpress.php'><input type=hidden name=statpress_action value=search>
  </form><br>
<?php
          if (isset($_GET['searchsubmit']))
          {
              // query builder
              $qry = "";
              // FIELDS
              $fields = "";
              for ($i = 1; $i <= 3; $i++)
              {
                  if ($_GET["where$i"] != '')
                  {
                      $fields .= $_GET["where$i"] . ",";
                  }
              }
              $fields = rtrim($fields, ",");
              // WHERE
              $where = "WHERE 1=1";
              if ($_GET['spider'] != 'checked')
              {
                  $where .= " AND spider=''";
              }
              if ($_GET['feed'] != 'checked')
              {
                  $where .= " AND feed=''";
              }
              for ($i = 1; $i <= 3; $i++)
              {
                  if (($_GET["what$i"] != '') && ($_GET["where$i"] != ''))
                  {
                      $where .= " AND " . $_GET["where$i"] . " LIKE '%" . mysql_real_escape_string($_GET["what$i"]) . "%'";
                  }
              }
              // ORDER BY
              $orderby = "";
              for ($i = 1; $i <= 3; $i++)
              {
                  if (($_GET["sortby$i"] == 'checked') && ($_GET["where$i"] != ''))
                  {
                      $orderby .= $_GET["where$i"] . ',';
                  }
              }
              
              // GROUP BY
              $groupby = "";
              for ($i = 1; $i <= 3; $i++)
              {
                  if (($_GET["groupby$i"] == 'checked') && ($_GET["where$i"] != ''))
                  {
                      $groupby .= $_GET["where$i"] . ',';
                  }
              }
              if ($groupby != '')
              {
                  $groupby = "GROUP BY " . rtrim($groupby, ',');
                  $fields .= ",count(*) as totale";
                  if ($_GET['oderbycount'] == 'checked')
                  {
                      $orderby = "totale DESC," . $orderby;
                  }
              }
              
              if ($orderby != '')
              {
                  $orderby = "ORDER BY " . rtrim($orderby, ',');
              }
              
              
              $limit = "LIMIT " . $_GET['limitquery'];
              
              if ($_GET['distinct'] == 'checked')
{
   $fields = " DISTINCT " . $fields;
}
              
              // Results
              print "<h2>" . __('Results', 'statpress') . "</h2>";
              $sql = "SELECT $fields FROM $table_name $where $groupby $orderby $limit;";
              //  print "$sql<br>";
              print "<table class='widefat'><thead><tr>";
              for ($i = 1; $i <= 3; $i++)
              {
                  if ($_GET["where$i"] != '')
                  {
                      print "<th scope='col'>" . ucfirst($_GET["where$i"]) . "</th>";
                  }
              }
              if ($groupby != '')
              {
                  print "<th scope='col'>" . __('Count', 'statpress') . "</th>";
              }
              print "</tr></thead><tbody id='the-list'>";
              $qry = $wpdb->get_results($sql, ARRAY_N);
              foreach ($qry as $rk)
              {
                  print "<tr>";
                  for ($i = 1; $i <= 3; $i++)
                  {
                      print "<td>";
                      if ($_GET["where$i"] == 'urlrequested')
                      {
                          print iri_StatPress_Decode($rk[$i - 1]);
                      }
                      else
                      {
                          print $rk[$i - 1];
                      }
                      print "</td>";
                  }
                  print "</tr>";
              }
              print "</table>";
              print "<br /><br /><font size=1 color=gray>sql: $sql</font></div>";
          }
      }
      
      function iri_StatPress_Abbrevia($s, $c)
      {
          $res = "";
          if (strlen($s) > $c)
          {
              $res = "...";
          }
          return my_substr($s, 0, $c) . $res;
      }
      
      function iri_StatPress_Where($ip)
      {
          $url = "http://api.hostip.info/get_html.php?ip=$ip";
          $res = file_get_contents($url);
          if ($res === false)
          {
              return(array('', ''));
          }
          $res = str_replace("Country: ", "", $res);
          $res = str_replace("\nCity: ", ", ", $res);
          $nation = preg_split('/\(|\)/', $res);
          print "( $ip $res )";
          return(array($res, $nation[1]));
      }
      
      
      function iri_StatPress_Decode($out_url)
      {
      	if(!permalinksEnabled())
      	{
	          if ($out_url == '')
	          {
	              $out_url = __('Page', 'statpress') . ": Home";
	          }
	          if (my_substr($out_url, 0, 4) == "cat=")
	          {
	              $out_url = __('Category', 'statpress') . ": " . get_cat_name(my_substr($out_url, 4));
	          }
	          if (my_substr($out_url, 0, 2) == "m=")
	          {
	              $out_url = __('Calendar', 'statpress') . ": " . my_substr($out_url, 6, 2) . "/" . my_substr($out_url, 2, 4);
	          }
	          if (my_substr($out_url, 0, 2) == "s=")
	          {
	              $out_url = __('Search', 'statpress') . ": " . my_substr($out_url, 2);
	          }
	          if (my_substr($out_url, 0, 2) == "p=")
	          {
	              $post_id_7 = get_post(my_substr($out_url, 2), ARRAY_A);
	              $out_url = $post_id_7['post_title'];
	          }
	          if (my_substr($out_url, 0, 8) == "page_id=")
	          {
	              $post_id_7 = get_page(my_substr($out_url, 8), ARRAY_A);
	              $out_url = __('Page', 'statpress') . ": " . $post_id_7['post_title'];
	          }
	        }
	        else
	        {
	        	if ($out_url == '')
	          {
	              $out_url = __('Page', 'statpress') . ": Home";
	          }
	          else if (my_substr($out_url, 0, 9) == "category/")
	          {
	              $out_url = __('Category', 'statpress') . ": " . get_cat_name(my_substr($out_url, 9));
	          }
	          else if (my_substr($out_url, 0, 8) == "//") // not working yet
	          {
	              //$out_url = __('Calendar', 'statpress') . ": " . my_substr($out_url, 4, 0) . "/" . my_substr($out_url, 6, 7);
	          }
	          else if (my_substr($out_url, 0, 2) == "s=")
	          {
	              $out_url = __('Search', 'statpress') . ": " . my_substr($out_url, 2);
	          }
	          else if (my_substr($out_url, 0, 2) == "p=") // not working yet 
	          {
	              $post_id_7 = get_post(my_substr($out_url, 2), ARRAY_A);
	              $out_url = $post_id_7['post_title'];
	          }
	          else if (my_substr($out_url, 0, 8) == "page_id=") // not working yet
	          {
	              $post_id_7 = get_page(my_substr($out_url, 8), ARRAY_A);
	              $out_url = __('Page', 'statpress') . ": " . $post_id_7['post_title'];
	          }
	        }
          return $out_url;
      }
      
      
      function iri_StatPress_URL()
      {
          $urlRequested = (isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '');
          if ($urlRequested == "")
          {
              // SEO problem!
              $urlRequested = (isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : '');
          }
          if (my_substr($urlRequested, 0, 2) == '/?')
          {
              $urlRequested = my_substr($urlRequested, 2);
          }
          if ($urlRequested == '/')
          {
              $urlRequested = '';
          }
          return $urlRequested;
      }
      
      function irigetblogurl()
      {
      	$prsurl = parse_url(get_bloginfo('url'));
      	return $prsurl['scheme'] . '://' . $prsurl['host'] . ((!permalinksEnabled()) ? $prsurl['path'] . '/?' : '');
      }
      
      // Converte da data us to default format di Wordpress
      function irihdate($dt = "00000000")
      {
          return mysql2date(get_option('date_format'), my_substr($dt, 0, 4) . "-" . my_substr($dt, 4, 2) . "-" . my_substr($dt, 6, 2));
      }
      
      
      function iritablesize($table)
      {
          global $wpdb;
          $res = $wpdb->get_results("SHOW TABLE STATUS LIKE '$table'");
          foreach ($res as $fstatus)
          {
              $data_lenght = $fstatus->Data_length;
              $data_rows = $fstatus->Rows;
          }
          return number_format(($data_lenght / 1024 / 1024), 2, ",", " ") . " MB ($data_rows records)";
      }
      
      
      function irirgbhex($red, $green, $blue)
      {
          $red = 0x10000 * max(0, min(255, $red + 0));
          $green = 0x100 * max(0, min(255, $green + 0));
          $blue = max(0, min(255, $blue + 0));
          // convert the combined value to hex and zero-fill to 6 digits
          return "#" . str_pad(strtoupper(dechex($red + $green + $blue)), 6, "0", STR_PAD_LEFT);
      }
      
      
      function iriValueTable($fld, $fldtitle, $limit = 0, $param = "", $queryfld = "", $exclude = "")
      {
          /* Maddler 04112007: param addedd */
          global $wpdb;
          $table_name = $wpdb->prefix . "statpress";
          
          if ($queryfld == '')
          {
              $queryfld = $fld;
          }
          print "<div class='wrap'><h2>$fldtitle</h2><table style='width:100%;padding:0px;margin:0px;' cellpadding=0 cellspacing=0><thead><tr><th style='width:400px;background-color:white;'></th><th style='width:150px;background-color:white;'><u>" . __('Visits', 'statpress') . "</u></th><th style='background-color:white;'></th></tr></thead>";
          print "<tbody id='the-list'>";
          $rks = $wpdb->get_var("SELECT count($param $queryfld) as rks FROM $table_name WHERE 1=1 $exclude;");
          if ($rks > 0)
          {
              $sql = "SELECT count($param $queryfld) as pageview, $fld FROM $table_name WHERE 1=1 $exclude  GROUP BY $fld ORDER BY pageview DESC";
              if ($limit > 0)
              {
                  $sql = $sql . " LIMIT $limit";
              }
              $qry = $wpdb->get_results($sql);
              $tdwidth = 450;
              $red = 131;
              $green = 180;
              $blue = 216;
              $deltacolor = round(250 / count($qry), 0);
              //      $chl="";
              //      $chd="t:";
              foreach ($qry as $rk)
              {
                  $pc = round(($rk->pageview * 100 / $rks), 1);
                  if ($fld == 'date')
                  {
                      $rk->$fld = irihdate($rk->$fld);
                  }
                  if ($fld == 'urlrequested')
                  {
                      $rk->$fld = iri_StatPress_Decode($rk->$fld);
                  }
                  //      $chl.=urlencode(my_substr($rk->$fld,0,50))."|";
                  //      $chd.=($tdwidth*$pc/100)."|";
                  print "<tr><td style='width:400px;overflow: hidden; white-space: nowrap; text-overflow: ellipsis;'>" . my_substr($rk->$fld, 0, 50);
                  if (strlen("$rk->fld") >= 50)
                  {
                      print "...";
                  }
                  // <td style='text-align:right'>$pc%</td>";
                  print "</td><td style='text-align:center;'>" . $rk->pageview . "</td>";
                  print "<td><div style='text-align:right;padding:2px;font-family:helvetica;font-size:7pt;font-weight:bold;height:16px;width:" . number_format(($tdwidth * $pc / 100), 1, '.', '') . "px;background:" . irirgbhex($red, $green, $blue) . ";border-top:1px solid " . irirgbhex($red + 20, $green + 20, $blue) . ";border-right:1px solid " . irirgbhex($red + 30, $green + 30, $blue) . ";border-bottom:1px solid " . irirgbhex($red - 20, $green - 20, $blue) . ";'>$pc%</div>";
                  print "</td></tr>\n";
                  $red = $red + $deltacolor;
                  $blue = $blue - ($deltacolor / 2);
              }
          }
          print "</table>\n";
          //  $chl=my_substr($chl,0,strlen($chl)-1);
          //  $chd=my_substr($chd,0,strlen($chd)-1);
          //  print "<img src=http://chart.apis.google.com/chart?cht=p3&chd=".($chd)."&chs=400x200&chl=".($chl)."&chco=1B75DF,92BF23>\n";
          print "</div>\n";
      }
      
      
      
      function iriDomain($ip)
      {
          $host = gethostbyaddr($ip);
          if (ereg('^([0-9]{1,3}\.){3}[0-9]{1,3}$', $host))
          {
              return "";
          }
          else
          {
              return my_substr(strrchr($host, "."), 1);
          }
      }
      
      function iriGetQueryPairs($url)
      {
          $parsed_url = parse_url($url);
          $tab = parse_url($url);
          $host = $tab['host'];
          if (key_exists("query", $tab))
          {
              $query = $tab["query"];
              $query = str_replace("&amp;", "&", $query);
              $query = urldecode($query);
              $query = str_replace("?", "&", $query);
              return explode("&", $query);
          }
          else
          {
              return null;
          }
      }
      
      
      function iriGetOS($arg)
      {
          $arg = str_replace(" ", "", $arg);
          $lines = file(dirname(plugin_basename(__FILE__)) . '/def/os.dat');
          foreach ($lines as $line_num => $os)
          {
              list($nome_os, $id_os) = explode("|", $os);
              if (strpos($arg, $id_os) === false)
                  continue;
              // riconosciuto
              return $nome_os;
          }
          return '';
      }
      
      
      function iriGetBrowser($arg)
      {
          $arg = str_replace(" ", "", $arg);
          $lines = file(dirname(plugin_basename(__FILE__)) . '/def/browser.dat');
          foreach ($lines as $line_num => $browser)
          {
              list($nome, $id) = explode("|", $browser);
              if (strpos($arg, $id) === false)
                  continue;
              // riconosciuto
              return $nome;
          }
          return '';
      }
      
	  function iriCheckBanIP($arg)
      {
          if (file_exists(dirname(plugin_basename(__FILE__)) . '-custom/banips.dat'))
              $lines = file(dirname(plugin_basename(__FILE__)) . '-custom/banips.dat');
          else
              $lines = file(dirname(plugin_basename(__FILE__)) . '/def/banips.dat');
         
        if ($lines !== false)
        {
            foreach ($lines as $banip)
              {
               if (@preg_match('/^' . rtrim($banip, "\r\n") . '$/', $arg)){
                   return true;
               }
                  // riconosciuto, da scartare
              }
          }
          return false;
      }
      
      function iriGetSE($referrer = null)
      {
          $key = null;
          $lines = file(dirname(plugin_basename(__FILE__)) . '/def/searchengines.dat');
          foreach ($lines as $line_num => $se)
          {
              list($nome, $url, $key) = explode("|", $se);
              if (strpos($referrer, $url) === false)
                  continue;
              // trovato se
              $variables = iriGetQueryPairs($referrer);
              $i = count($variables);
              while ($i--)
              {
                  $tab = explode("=", $variables[$i]);
                  if ($tab[0] == $key)
                  {
                      return($nome . "|" . urldecode($tab[1]));
                  }
              }
          }
          return null;
      }
      
      function iriGetSpider($agent = null)
      {
          $agent = str_replace(" ", "", $agent);
          $key = null;
          $lines = file(dirname(plugin_basename(__FILE__)) . '/def/spider.dat');
          if (file_exists(dirname(plugin_basename(__FILE__)) . '-custom/spider.dat'))
              $lines = array_merge($lines, file(dirname(plugin_basename(__FILE__)) . '-custom/spider.dat'));
          foreach ($lines as $line_num => $spider)
          {
              list($nome, $key) = explode("|", $spider);
              if (strpos($agent, $key) === false)
                  continue;
              // trovato
              return $nome;
          }
          return null;
      }
      
      
      function iri_StatPress_lastmonth()
      {
          $ta = getdate(current_time('timestamp'));
          
          $year = $ta['year'];
          $month = $ta['mon'];
          
          // go back 1 month;
          $month = $month - 1;
          
          if ($month === 0)
          {
          	// if this month is Jan
            // go back a year
            $year  = $year - 1;
          	$month = 12;
          }
          
          // return in format 'YYYYMM'
          return sprintf($year . '%02d', $month);
      }
      
      
      function iri_StatPress_CreateTable()
      {
          global $wpdb;
          global $wp_db_version;
          $table_name = $wpdb->prefix . "statpress";
          $sql_createtable = "CREATE TABLE " . $table_name . " (
  id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
  date TINYTEXT,
  time TINYTEXT,
  ip TINYTEXT,
  urlrequested TEXT,
  agent TEXT,
  referrer TEXT,
  search TEXT,
  nation TINYTEXT,
  os TINYTEXT,
  browser TINYTEXT,
  searchengine TINYTEXT,
  spider TINYTEXT,
  feed TINYTEXT,
  user TINYTEXT,
  timestamp TINYTEXT,
  threat_score SMALLINT,
  threat_type SMALLINT,
  UNIQUE KEY id (id)
  );";
          if ($wp_db_version >= 5540)
              $page = 'wp-admin/includes/upgrade.php';
          else
              $page = 'wp-admin/upgrade-functions.php';
          require_once(ABSPATH . $page);
          dbDelta($sql_createtable);
      }
      
function iri_StatPress_is_feed($url) {
   if (stristr($url,get_bloginfo('comments_atom_url')) != FALSE) { return 'COMMENT ATOM'; }
   elseif (stristr($url,get_bloginfo('comments_rss2_url')) != FALSE) { return 'COMMENT RSS'; }
   elseif (stristr($url,get_bloginfo('rdf_url')) != FALSE) { return 'RDF'; }
   elseif (stristr($url,get_bloginfo('atom_url')) != FALSE) { return 'ATOM'; }
   elseif (stristr($url,get_bloginfo('rss_url')) != FALSE) { return 'RSS'; }
   elseif (stristr($url,get_bloginfo('rss2_url')) != FALSE) { return 'RSS2'; }
   elseif (stristr($url,'wp-feed.php') != FALSE) { return 'RSS2'; }
   elseif (stristr($url,'/feed') != FALSE) { return 'RSS2'; }
   return '';
}



function iriStatPressAgents()
      {
          global $wpdb;
          $table_name = $wpdb->prefix . "statpress";
          $query = "SELECT date, MAX(time), ip, COUNT(*) as count, agent";
          $query .= " FROM " . $table_name;
          $query .= " WHERE spider = '' AND browser = ''";
          $query .= " GROUP BY date, ip, agent";
          $query .= " ORDER BY date DESC";
          $result = $wpdb->get_results($query);

          print "<div class='wrap'><h2>" . __('Unknown User Agents', 'statpress') . "</h2>";
          print "<table class='widefat'><thead><tr>";
          print "<th scope='col'>" . __('Date', 'statpress') . "</th>";
          print "<th scope='col'>" . __('Last Time', 'statpress') . "</th>";
          print "<th scope='col'>" . __('IP', 'statpress') . "</th>";
          print "<th scope='col'>" . __('Count', 'statpress') . "</th>";
          print "<th scope='col'>" . __('User Agent', 'statpress') . "</th>";
          print "</tr></thead><tbody id='the-list'>";

          foreach ($result as $line)
          {   
            $col = 0;
            print '<tr>';
            foreach ($line as $col_value)
{
    $col++;
    if ($col == 1)
        print '<td>' . irihdate($col_value) . '</td>';
    else if ($col == 3)
        print "<td><a href='http://www.projecthoneypot.org/ip_" . $col_value . "' target='_blank'>" . $col_value . "</a></td>";
    else
        print '<td>' . $col_value . '</td>';
}
            print '</tr>';
          }
          print '</table></div>';
      }


function iri_StatPress_extractfeedreq($url)
{
		if(!strpos($url, '?') === FALSE)
		{
        list($null, $q) = explode("?", $url);
    		list($res, $null) = explode("&", $q);
    }
    else
    {
    	$prsurl = parse_url($url);
    	$res = $prsurl['path'] . $$prsurl['query'];
    }
    
    return $res;
}
      
      function iriStatAppend()
      {
          global $wpdb;
          $table_name = $wpdb->prefix . "statpress";
          global $userdata;
          global $_STATPRESS;
          get_currentuserinfo();
          $feed = '';
          
          // Time
          $timestamp = current_time('timestamp');
          $vdate = gmdate("Ymd", $timestamp);
          $vtime = gmdate("H:i:s", $timestamp);
          
          // IP
          $ipAddress = $_SERVER['REMOTE_ADDR'];
          if (iriCheckBanIP($ipAddress) === true)
          {
              return '';
          }
          
          // Determine Threats if http:bl installed
          $threat_score = 0;
          $threat_type = 0;
          $httpbl_key = get_option("httpbl_key");
          if ($httpbl_key !== false)
          {
              $result = explode( ".", gethostbyname( $httpbl_key . "." .
                  implode ( ".", array_reverse( explode( ".",
                  $ipAddress ) ) ) .
                  ".dnsbl.httpbl.org" ) );
              // If the response is positive
              if ($result[0] == 127)
              {
                  $threat_score = $result[2];
                  $threat_type = $result[3];
              }
          }
          
          // URL (requested)
          $urlRequested = iri_StatPress_URL();
          if (eregi(".ico$", $urlRequested))
          {
              return '';
          }
          if (eregi("favicon.ico", $urlRequested))
          {
              return '';
          }
          if (eregi(".css$", $urlRequested))
          {
              return '';
          }
          if (eregi(".js$", $urlRequested))
          {
              return '';
          }
          if (stristr($urlRequested, "/wp-content/plugins") != false)
          {
              return '';
          }
          if (stristr($urlRequested, "/wp-content/themes") != false)
          {
              return '';
          }
          
          $referrer = (isset($_SERVER['HTTP_REFERER']) ? htmlentities($_SERVER['HTTP_REFERER']) : '');
          $userAgent = (isset($_SERVER['HTTP_USER_AGENT']) ? htmlentities($_SERVER['HTTP_USER_AGENT']) : '');
          $spider = iriGetSpider($userAgent);
          
          if (($spider != '') and (get_option('statpress_donotcollectspider') == 'checked'))
          {
              return '';
          }
          
          if ($spider != '')
          {
              $os = '';
              $browser = '';
          }
          else
          {
              // Trap feeds
              $prsurl = parse_url(get_bloginfo('url'));
              $feed = iri_StatPress_is_feed($prsurl['scheme'] . '://' . $prsurl['host'] . $_SERVER['REQUEST_URI']);
              // Get OS and browser
              $os = iriGetOS($userAgent);
              $browser = iriGetBrowser($userAgent);
              list($searchengine, $search_phrase) = explode("|", iriGetSE($referrer));
          }
          // Auto-delete visits if...
          if (get_option('statpress_autodelete_spider') != '') 
          {
              $t = gmdate("Ymd", strtotime('-' . get_option('statpress_autodelete_spider')));
              $results = $wpdb->query("DELETE FROM " . $table_name . " WHERE date < '" . $t . "' AND spider <> ''");
          }
          if (get_option('statpress_autodelete') != '')
          {
              $t = gmdate("Ymd", strtotime('-' . get_option('statpress_autodelete')));
              $results = $wpdb->query("DELETE FROM " . $table_name . " WHERE date < '" . $t . "'");
          }
          if ((!is_user_logged_in()) or (get_option('statpress_collectloggeduser') == 'checked'))
          {
              if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
              {
                  iri_StatPress_CreateTable();
              }
              
              $insert = "INSERT INTO " . $table_name . " (date, time, ip, urlrequested, agent, referrer, search,nation,os,browser,searchengine,spider,feed,user,threat_score,threat_type,timestamp) " . "VALUES ('$vdate','$vtime','$ipAddress','" . mysql_real_escape_string($urlRequested) . "','" . mysql_real_escape_string(strip_tags($userAgent)) . "','" . mysql_real_escape_string($referrer) . "','" . mysql_real_escape_string(strip_tags($search_phrase)) . "','" . iriDomain($ipAddress) . "','" . mysql_real_escape_string($os) . "','" . mysql_real_escape_string($browser) . "','$searchengine','$spider','$feed','$userdata->user_login',$threat_score,$threat_type,'$timestamp')";
              $results = $wpdb->query($insert);
          }
      }
      
      
      function iriStatPressUpdate()
      {
          global $wpdb;
          $table_name = $wpdb->prefix . "statpress";
          
          $wpdb->show_errors();
          // update table
          print "" . __('Updating table struct', 'statpress') . " $table_name... ";
          iri_StatPress_CreateTable();
          print "" . __('done', 'statpress') . "<br>";
          
          // Update Feed
          print "" . __('Updating Feeds', 'statpress') . "... ";
          $wpdb->query("UPDATE $table_name SET feed='';");
          
          // standard blog info urls
          $s = iri_StatPress_extractfeedreq(get_bloginfo('comments_atom_url'));
          if ($s != '')
          {
              $wpdb->query("UPDATE $table_name SET feed='COMMENT ATOM' WHERE INSTR(urlrequested,'$s')>0 AND feed='';");
          }
          $s = iri_StatPress_extractfeedreq(get_bloginfo('comments_rss2_url'));
          if ($s != '')
          {
              $wpdb->query("UPDATE $table_name SET feed='COMMENT RSS' WHERE INSTR(urlrequested,'$s')>0 AND feed='';");
          }
          $s = iri_StatPress_extractfeedreq(get_bloginfo('atom_url'));
          if ($s != '')
          {
              $wpdb->query("UPDATE $table_name SET feed='ATOM' WHERE INSTR(urlrequested,'$s')>0 AND feed='';");
          }
          $s = iri_StatPress_extractfeedreq(get_bloginfo('rdf_url'));
          if ($s != '')
          {
              $wpdb->query("UPDATE $table_name SET feed='RDF'  WHERE INSTR(urlrequested,'$s')>0 AND feed='';");
          }
          $s = iri_StatPress_extractfeedreq(get_bloginfo('rss_url'));
          if ($s != '')
          {
              $wpdb->query("UPDATE $table_name SET feed='RSS'  WHERE INSTR(urlrequested,'$s')>0 AND feed='';");
          }
          $s = iri_StatPress_extractfeedreq(get_bloginfo('rss2_url'));
          if ($s != '')
          {
              $wpdb->query("UPDATE $table_name SET feed='RSS2' WHERE INSTR(urlrequested,'$s')>0 AND feed='';");
          }
          
          // not standard
          $wpdb->query("UPDATE $table_name SET feed='RSS2' WHERE urlrequested LIKE '%/feed%' AND feed='';");
          $wpdb->query("UPDATE $table_name SET feed='RSS2' WHERE urlrequested LIKE '%wp-feed.php%' AND feed='';");
         
          
          print "" . __('done', 'statpress') . "<br>";
          
          // Update OS
          print "" . __('Updating OS', 'statpress') . "... ";
          $wpdb->query("UPDATE $table_name SET os = '';");
          $lines = file(ABSPATH . 'wp-content/themes/'.get_template().'/plugins/' . dirname(plugin_basename(__FILE__)) . '/def/os.dat');
          foreach ($lines as $line_num => $os)
          {
              list($nome_os, $id_os) = explode("|", $os);
              $qry = "UPDATE $table_name SET os = '$nome_os' WHERE os='' AND replace(agent,' ','') LIKE '%" . $id_os . "%';";
              $wpdb->query($qry);
          }
          print "" . __('done', 'statpress') . "<br>";
          
          // Update Browser
          print "". __('Updating Browsers', 'statpress') ."... ";
          $wpdb->query("UPDATE $table_name SET browser = '';");
          $lines = file(ABSPATH . 'wp-content/themes/'.get_template().'/plugins/' . dirname(plugin_basename(__FILE__)) . '/def/browser.dat');
          foreach ($lines as $line_num => $browser)
          {
              list($nome, $id) = explode("|", $browser);
              $qry = "UPDATE $table_name SET browser = '$nome' WHERE browser='' AND replace(agent,' ','') LIKE '%" . $id . "%';";
              $wpdb->query($qry);
          }
          print "" . __('done', 'statpress') . "<br>";
          
          print "" . __('Updating Spiders', 'statpress') . "... ";
          $wpdb->query("UPDATE $table_name SET spider = '';");
          $lines = file(ABSPATH . 'wp-content/themes/'.get_template().'/plugins/' . dirname(plugin_basename(__FILE__)) . '/def/spider.dat');
          if (file_exists(ABSPATH . 'wp-content/themes/'.get_template().'/plugins/' . dirname(plugin_basename(__FILE__)) . '-custom/spider.dat'))
              $lines = array_merge($lines, file(ABSPATH . 'wp-content/themes/'.get_template().'/plugins/' . dirname(plugin_basename(__FILE__)) . '-custom/spider.dat'));
          foreach ($lines as $line_num => $spider)
          {
              list($nome, $id) = explode("|", $spider);
              $qry = "UPDATE $table_name SET spider = '$nome',os='',browser='' WHERE spider='' AND replace(agent,' ','') LIKE '%" . $id . "%';";
              $wpdb->query($qry);
          }
          print "" . __('done', 'statpress') . "<br>";
          
          // Update feed to ''
          print "" . __('Updating Feeds', 'statpress') . "... ";
          $wpdb->query("UPDATE $table_name SET feed = '' WHERE isnull(feed);");
          print "" . __('done', 'statpress') . "<br>";
          
          // Update Search engine
          print "" . __('Updating Search engines', 'statpress') . "... ";
          print "<br>";
          $wpdb->query("UPDATE $table_name SET searchengine = '', search='';");
          print "..." . __('null-ed', 'statpress') . "!<br>";
          $qry = $wpdb->get_results("SELECT id, referrer FROM $table_name WHERE referrer !=''");
          print "..." . __('select-ed', 'statpress') . "!<br>";
          foreach ($qry as $rk)
          {
              list($searchengine, $search_phrase) = explode("|", iriGetSE($rk->referrer));
              if ($searchengine <> '')
              {
                  $q = "UPDATE $table_name SET searchengine = '$searchengine', search='" . addslashes($search_phrase) . "' WHERE id=" . $rk->id;
                  $wpdb->query($q);
              }
          }
          print "" . __('done', 'statpress') . "<br>";
          
          $wpdb->hide_errors();
          
          print "<br>&nbsp;<h1>" . __('Updated', 'statpress') . "!</h1>";
      }
      
      function StatPress_Widget($w = '')
      {
      }
      
      function StatPress_Print($body = '')
      {
          print iri_StatPress_Vars($body);
      }
      
      
      function iri_StatPress_Vars($body)
      {
          global $wpdb;
          $table_name = $wpdb->prefix . "statpress";
          
          if (strpos(strtolower($body), "%visits%") !== false)
          {
              $qry = $wpdb->get_results("SELECT count(DISTINCT(ip)) as pageview FROM $table_name WHERE date = '" . gmdate("Ymd", current_time('timestamp')) . "' and spider='' and feed='';");
              $body = str_replace("%visits%", $qry[0]->pageview, $body);
          }
          if (strpos(strtolower($body), "%totalvisits%") !== false)
          {
              $qry = $wpdb->get_results("SELECT count(DISTINCT(ip)) as pageview FROM $table_name WHERE spider='' and feed='';");
              $body = str_replace("%totalvisits%", $qry[0]->pageview, $body);
          }
          if (strpos(strtolower($body), "%thistotalvisits%") !== false)
          {
              $qry = $wpdb->get_results("SELECT count(DISTINCT(ip)) as pageview FROM $table_name WHERE spider='' and feed='' AND urlrequested='" . mysql_real_escape_string(iri_StatPress_URL()) . "';");
              $body = str_replace("%thistotalvisits%", $qry[0]->pageview, $body);
          }
          if (strpos(strtolower($body), "%since%") !== false)
          {
              $qry = $wpdb->get_results("SELECT date FROM $table_name ORDER BY date LIMIT 1;");
              $body = str_replace("%since%", irihdate($qry[0]->date), $body);
          }
          if (strpos(strtolower($body), "%os%") !== false)
          {
              $userAgent = (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '');
              $os = iriGetOS($userAgent);
              $body = str_replace("%os%", $os, $body);
          }
          if (strpos(strtolower($body), "%browser%") !== false)
          {
              $browser = iriGetBrowser($userAgent);
              $body = str_replace("%browser%", $browser, $body);
          }
          if (strpos(strtolower($body), "%ip%") !== false)
          {
              $ipAddress = $_SERVER['REMOTE_ADDR'];
              $body = str_replace("%ip%", $ipAddress, $body);
          }
          if (strpos(strtolower($body), "%visitorsonline%") !== false)
          {
              $to_time = current_time('timestamp');
              $from_time = strtotime('-4 minutes', $to_time);
              $qry = $wpdb->get_results("SELECT count(DISTINCT(ip)) as visitors FROM $table_name WHERE spider='' and feed='' AND timestamp BETWEEN $from_time AND $to_time;");
              $body = str_replace("%visitorsonline%", $qry[0]->visitors, $body);
          }
          if (strpos(strtolower($body), "%usersonline%") !== false)
          {
              $to_time = current_time('timestamp');
              $from_time = strtotime('-4 minutes', $to_time);
              $qry = $wpdb->get_results("SELECT count(DISTINCT(ip)) as users FROM $table_name WHERE spider='' and feed='' AND user<>'' AND timestamp BETWEEN $from_time AND $to_time;");
              $body = str_replace("%usersonline%", $qry[0]->users, $body);
          }
          if (strpos(strtolower($body), "%toppost%") !== false)
          {
              $qry = $wpdb->get_results("SELECT urlrequested,count(*) as totale FROM $table_name WHERE spider='' AND feed='' AND urlrequested LIKE '%p=%' GROUP BY urlrequested ORDER BY totale DESC LIMIT 1;");
              $body = str_replace("%toppost%", iri_StatPress_Decode($qry[0]->urlrequested), $body);
          }
          if (strpos(strtolower($body), "%topbrowser%") !== false)
          {
              $qry = $wpdb->get_results("SELECT browser,count(*) as totale FROM $table_name WHERE spider='' AND feed='' GROUP BY browser ORDER BY totale DESC LIMIT 1;");
              $body = str_replace("%topbrowser%", iri_StatPress_Decode($qry[0]->browser), $body);
          }
          if (strpos(strtolower($body), "%topos%") !== false)
          {
              $qry = $wpdb->get_results("SELECT os,count(*) as totale FROM $table_name WHERE spider='' AND feed='' GROUP BY os ORDER BY totale DESC LIMIT 1;");
              $body = str_replace("%topos%", iri_StatPress_Decode($qry[0]->os), $body);
          }
          if(strpos(strtolower($body),"%pagestoday%") !== false)
          {
      				$qry = $wpdb->get_results("SELECT count(ip) as pageview FROM $table_name WHERE date = '".gmdate("Ymd",current_time('timestamp'))."' and spider='' and feed='';");
      				$body = str_replace("%pagestoday%", $qry[0]->pageview, $body);
   				}
   				
   				if(strpos(strtolower($body),"%thistotalpages%") !== FALSE)
   				{
      				$qry = $wpdb->get_results("SELECT count(ip) as pageview FROM $table_name WHERE spider='' and feed='';");
      				$body = str_replace("%thistotalpages%", $qry[0]->pageview, $body);
      		}
      		
      		if (strpos(strtolower($body), "%latesthits%") !== false)
			{
				$qry = $wpdb->get_results("SELECT search FROM $table_name WHERE search <> '' ORDER BY id DESC LIMIT 10");
				$body = str_replace("%latesthits%", $qry[0]->search, $body);
				for ($counter = 0; $counter < 10; $counter += 1)
				{
					$body .= "<br>". $qry[$counter]->search;
				}
			}
			
			if (strpos(strtolower($body), "%pagesyesterday%") !== false)
			{
				$yesterday = gmdate('Ymd', current_time('timestamp') - 86400);
				$qry = $wpdb->get_row("SELECT count(DISTINCT ip) AS visitsyesterday FROM $table_name WHERE feed='' AND spider='' AND date = '" . mysql_real_escape_string($yesterday) . "'");
				$body = str_replace("%pagesyesterday%", iri_StatPress_Decode($qry[0]->visitsyesterday), $body);
			}
          
			
          return $body;
      }
      
      
      function iri_StatPress_TopPosts($limit = 5, $showcounts = 'checked')
      {
          global $wpdb;
          $res = "\n<ul>\n";
          $table_name = $wpdb->prefix . "statpress";
          $qry = $wpdb->get_results("SELECT urlrequested,count(*) as totale FROM $table_name WHERE spider='' AND feed='' GROUP BY urlrequested ORDER BY totale DESC LIMIT $limit;");
          foreach ($qry as $rk)
          {
              $res .= "<li><a href='" . irigetblogurl() . ((strpos($rk->urlrequested, 'index.php') === FALSE) ? $rk->urlrequested : '') . "'>" . iri_StatPress_Decode($rk->urlrequested) . "</a></li>\n";
              if (strtolower($showcounts) == 'checked')
              {
                  $res .= " (" . $rk->totale . ")";
              }
          }
          return "$res</ul>\n";
      }
      
      
      function widget_statpress_init($args)
      {
          if (!function_exists('register_sidebar_widget') || !function_exists('register_widget_control'))
              return;
          // Multifunctional StatPress pluging
          function widget_statpress_control()
          {
              $options = get_option('widget_statpress');
              if (!is_array($options))
                  $options = array('title' => 'StatPress', 'body' => 'Visits today: %visits%');
              if ($_POST['statpress-submit'])
              {
                  $options['title'] = strip_tags(stripslashes($_POST['statpress-title']));
                  $options['body'] = stripslashes($_POST['statpress-body']);
                  update_option('widget_statpress', $options);
              }
              $title = htmlspecialchars($options['title'], ENT_QUOTES);
              $body = htmlspecialchars($options['body'], ENT_QUOTES);
              // the form
              echo '<p style="text-align:right;"><label for="statpress-title">' . __('Title:') . ' <input style="width: 250px;" id="statpress-title" name="statpress-title" type="text" value="' . $title . '" /></label></p>';
              echo '<p style="text-align:right;"><label for="statpress-body"><div>' . __('Body:', 'widgets') . '</div><textarea style="width: 288px;height:100px;" id="statpress-body" name="statpress-body" type="textarea">' . $body . '</textarea></label></p>';
              echo '<input type="hidden" id="statpress-submit" name="statpress-submit" value="1" /><div style="font-size:7pt;">%totalvisits% %visits% %thistotalvisits% %os% %browser% %ip% %since% %visitorsonline% %usersonline% %toppost% %topbrowser% %topos%</div>';
          }
          function widget_statpress($args)
          {
              extract($args);
              $options = get_option('widget_statpress');
              $title = $options['title'];
              $body = $options['body'];
              echo $before_widget;
              print($before_title . $title . $after_title);
              print iri_StatPress_Vars($body);
              echo $after_widget;
          }
          register_sidebar_widget('StatPress', 'widget_statpress');
          register_widget_control(array('StatPress', 'widgets'), 'widget_statpress_control', 300, 210);
          
          // Top posts
          function widget_statpresstopposts_control()
          {
              $options = get_option('widget_statpresstopposts');
              if (!is_array($options))
              {
                  $options = array('title' => 'StatPress TopPosts', 'howmany' => '5', 'showcounts' => 'checked');
              }
              if ($_POST['statpresstopposts-submit'])
              {
                  $options['title'] = strip_tags(stripslashes($_POST['statpresstopposts-title']));
                  $options['howmany'] = stripslashes($_POST['statpresstopposts-howmany']);
                  $options['showcounts'] = stripslashes($_POST['statpresstopposts-showcounts']);
                  if ($options['showcounts'] == "1")
                  {
                      $options['showcounts'] = 'checked';
                  }
                  update_option('widget_statpresstopposts', $options);
              }
              $title = htmlspecialchars($options['title'], ENT_QUOTES);
              $howmany = htmlspecialchars($options['howmany'], ENT_QUOTES);
              $showcounts = htmlspecialchars($options['showcounts'], ENT_QUOTES);
              // the form
              echo '<p style="text-align:right;"><label for="statpresstopposts-title">' . __('Title', 'statpress') . ' <input style="width: 250px;" id="statpress-title" name="statpresstopposts-title" type="text" value="' . $title . '" /></label></p>';
              echo '<p style="text-align:right;"><label for="statpresstopposts-howmany">' . __('Limit results to', 'statpress') . ' <input style="width: 100px;" id="statpresstopposts-howmany" name="statpresstopposts-howmany" type="text" value="' . $howmany . '" /></label></p>';
              echo '<p style="text-align:right;"><label for="statpresstopposts-showcounts">' . __('Visits', 'statpress') . ' <input id="statpresstopposts-showcounts" name="statpresstopposts-showcounts" type=checkbox value="checked" ' . $showcounts . ' /></label></p>';
              echo '<input type="hidden" id="statpress-submitTopPosts" name="statpresstopposts-submit" value="1" />';
          }
          function widget_statpresstopposts($args)
          {
              extract($args);
              $options = get_option('widget_statpresstopposts');
              $title = htmlspecialchars($options['title'], ENT_QUOTES);
              $howmany = htmlspecialchars($options['howmany'], ENT_QUOTES);
              $showcounts = htmlspecialchars($options['showcounts'], ENT_QUOTES);
              echo $before_widget;
              print($before_title . $title . $after_title);
              print iri_StatPress_TopPosts($howmany, $showcounts);
              echo $after_widget;
          }
          register_sidebar_widget('StatPress TopPosts', 'widget_statpresstopposts');
          register_widget_control(array('StatPress TopPosts', 'widgets'), 'widget_statpresstopposts_control', 300, 110);
      }
      
      
		// a custom function for loading localization
		function statpress_load_textdomain() {
		//check whether necessary core function exists
		if ( function_exists('load_plugin_textdomain') ) {
		//load the plugin textdomain
		load_plugin_textdomain('statpress', 'wp-content/themes/'.get_template().'/plugins/' . dirname(plugin_basename(__FILE__)) . '/locale');
		}
		}
		// call the custom function on the init hook
		add_action('init', 'statpress_load_textdomain');
      
      add_action('admin_menu', 'iri_add_pages');
      add_action('plugins_loaded', 'widget_statpress_init');
      //add_action('wp_head', 'iriStatAppend');
      add_action('send_headers', 'iriStatAppend');
      
      register_activation_hook(basename(__FILE__), 'iri_StatPress_CreateTable');
?>
