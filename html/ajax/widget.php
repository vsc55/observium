<?php
/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage ajax
 * @copyright  (C) Adam Armstrong
 *
 */

include_once("../../includes/observium.inc.php");

include($config['html_dir'] . "/includes/authenticate.inc.php");

if (!$_SESSION['authenticated']) {
    echo("unauthenticated");
    exit;
}

include_dir($config['html_dir'] . "/includes/widgets/");

$widget = dbFetchRow("SELECT * FROM `dash_widgets` WHERE `widget_id` = ?", [ $_POST['id'] ]);

$widget['height'] = is_numeric($_POST['height']) ? $_POST['height'] : 3;
$widget['width']  = is_numeric($_POST['width']) ? $_POST['width'] : 4;

print_dash_mod($widget);

function print_dash_mod($mod) {

    global $config, $cache;

    $mod['vars'] = safe_json_decode($mod['widget_config']);

    $width  = is_numeric($mod['width']) ? $mod['width'] : 1240;
    $height = is_numeric($mod['height']) ? $mod['height'] : 80;

    switch ($mod['widget_type']) {

        case "welcome":
            echo '<div class="box box-solid do-not-update" style="padding:10px; padding-left: 375px; background-image: url(images/hamster-login.png); background-position: left 10px top -100px; background-repeat: no-repeat;">';
            echo '<h3>Welcome to your new Observium dashboard!</h3>';
            if (isset($mod['vars']['converted'])) {
                echo 'This was autogenerated based on your previous front page. It can be modified to suit your requirements.<br />';
            } else {
                echo 'This is an autogenerated default dashboard. It can be modified to suit your requirements.<br />';
            }
            echo 'Please see the <strong><a href="' . OBSERVIUM_DOCS_URL . '/dashboard/" target="_blank">documentation</a></strong> for information about how to configure this dashboard. Including how to delete this widget!';
            echo '</div>';
            break;

        case "weathermap":

            echo '<div class="box box-solid do-not-update">';

            $wmap = dbFetchRow("SELECT * FROM `weathermaps` WHERE `wmap_name` = ?", [ $mod['vars']['mapname'] ]);

            echo '  <div class="hover-hide widget-title" style="z-index: 900; position: absolute; overflow: hidden;" class="widget-title"><h4 style="wwriting-mode: vertical-lr; ttext-orientation: mixed;" class="box-title">' .
                 escape_html($wmap['wmap_name']) . '</h4></div>' . PHP_EOL;

            echo '  <div class="box-content" style="overflow: hidden">';
            echo '<div style="height:100%; overflow:hidden; width: 110%;">';
            echo '<a href="' . generate_url([ 'page' => 'wmap', 'mapname' => $wmap['map_name'] ]) . '">';
            echo '<img src="/weathermap.php?mapname=' . escape_html($wmap['wmap_name']) . '&action=draw&unique=' . time() . '&width=' . $width . '&height=' . $height . '">';
            echo '</a>';

            echo '</div>';
            echo '  </div>';

            echo '</div>';
            break;

        case "map":
            echo '<div class="box box-solid do-not-update">';
            print_dash_map($mod, $width, $height);
            echo '</div>';

            break;

        case "port_percent":
            if ($_SESSION['userlevel'] < 5) {
                echo '<div class="box box-solid" style="width: 100%; height: 100%; float:none; display: block; padding: 10px;">';
                echo '<div class="alert statusbox alert-warning" style="border-left: 1px; width: 100%; height: 100%; margin-right: 10px; float:none; display: block;">';
                echo '<div style="margin: auto; line-height: 75px; text-align: center;">You have insufficient permissions to view this widget.</div>';
                echo '</div>';
                echo '</div>';
            } elseif ($height < 190) {
                echo '<div class="box box-solid alert alert-warning" style="width: 100%; height: 100%; float:none; display: block; padding: 10px;">';
                echo '<b>WARNING</b> This widget is not tall enough to display the requested content. Module <b>Port Percent</b> requires 190px or taller.';
                echo '</div>';
            } else {
                include($config['html_dir'] . "/includes/status-portpercent.inc.php");
            }
            break;

        case "alert_table":
            echo '<div class="box box-solid" style="overflow: hidden; height: auto; max-height: 100%">';
            echo '  <div class="box-header" style="cursor: hand;"><h3 class="box-title">Alert Status</h3></div>';
            echo '    <div class="box-content" style="height: ' . ($height - 40) . 'px; overflow: auto;">';
            //echo '    <div class="box-content" style="overflow: scroll; overflow-x:scroll;">';
            //echo '    <div class="box-content" style="overflow:auto;">';

            $short = !($width > 1000);

            print_alert_table([ 'status' => 'failed', 'pagination' => FALSE, 'short' => $short ]);
            echo '    </div>';
            echo '  </div>';
            echo '</div>';

            break;

        case "status_summary":
            echo '<div class="row">';
            if ($width > 1000) {
                $div_class = "col-md-6";
            } else {
                $div_class = "col-md-12";
            }

            if ($height < 210) {
                $hide_group_bar = 1;
            }

            include($config['html_dir'] . '/includes/cache-data.inc.php');
            include($config['html_dir'] . "/includes/status-summary.inc.php");
            echo '</div>';
            break;

        case "alert_boxes":
        case "old_status_boxes":
            //r($height);
            include($config['html_dir'] . '/includes/cache-data.inc.php');
            //$count = round(($width) / 165) * round(($height+10) / 90); // 1.5 wide
            $count = floor(($width + 10) / 198) * floor(($height + 10) / 96); // 1.5 wide
            echo '<div style="width: auto; height: 100%; overflow-x: visible; overflow-y: visible; margin-right: -25px;">';
            if ($mod['widget_type'] == 'alert_boxes') {
                print_status_boxes($mod, $count);
            } else {
                print_status_boxes($config['frontpage']['device_status'], $count);
            }
            echo '</div>';
            break;

        case "old_status_table":
            echo '<div class="box box-solid" style="overflow: hidden; height: auto; max-height: 100%">';
            echo '  <div class="box-header" style="cursor: hand;"><h3 class="box-title">Status Warnings and Notifications</h3></div>';
            echo '    <div class="box-content" style="height: ' . ($height - 40) . 'px; overflow: auto;">';

            include($config['html_dir'] . '/includes/cache-data.inc.php');
            echo generate_status_table($config['frontpage']['device_status']);
            echo generate_box_close();

            break;

        case "status_donuts":
            include($config['html_dir'] . "/includes/status-donuts.inc.php");
            break;

        case "syslog":
            echo '  <div class="box box-solid" style="overflow: hidden; height: auto; max-height: 100%">';
            echo '    <div class="box-header" style="cursor: hand;"><h3 class="box-title"><a href="/syslog/">Syslog</a></h3></div>';
            echo '    <div class="box-content" style="overflow: hidden; overflow-x:scroll;">';

            $short = !($width > 1000);

            $syslog_vars = array_merge($mod['vars'], [ 'short'    => $short, 'pagesize' => ($height - 36) / 26,
                                                       'priority' => $config['frontpage']['syslog']['priority'] ]);

            print_syslogs($syslog_vars);

            echo '  </div>';
            echo '</div>';
            break;

        case "syslog_alerts":
            echo '  <div class="box box-solid" style="overflow: hidden; height: auto; max-height: 100%">';
            echo '    <div class="box-header" style="cursor: hand;"><h3 class="box-title"><a href="/syslog_alerts/">Syslog Alerts</a></h3></div>';
            echo '    <div class="box-content" style="overflow: hidden; overflow-x:scroll;">';

            $short = !($width > 1000);

            $alertlog_vars = array_merge($mod['vars'], [ 'short' => $short, 'pagesize' => ($height - 36) / 26 ]);

            print_logalert_log($alertlog_vars);

            echo '  </div>';
            echo '</div>';
            break;

        case "alertlog":
            echo '  <div class="box box-solid" style="overflow: hidden; height: auto; max-height: 100%">';
            echo '    <div class="box-header" style="cursor: hand;"><h3 class="box-title"><a href="/alert_log/">Alert Log</a></h3></div>';
            echo '    <div class="box-content" style="overflow: hidden; overflow-x:scroll;">';

            $short = !($width > 1000);

            $alertlog_vars = array_merge($mod['vars'], [ 'short' => $short, 'pagesize' => ($height - 36) / 26 ]);

            print_alert_log($alertlog_vars);

            echo '  </div>';
            echo '</div>';
            break;

        case "eventlog":
            echo '  <div class="box box-solid" style="overflow: hidden; height: auto; max-height: 100%">';
            echo '    <div class="box-header" style="cursor: hand;"><h3 class="box-title"><a href="/eventlog/">Eventlog</a></h3></div>';
            echo '    <div class="box-content" style="overflow: hidden; overflow-x:scroll;">';

            $pagesize = floor(($height - 36) / 26);

            $short = !($width > 1000);

            $eventlog_vars = array_merge($mod['vars'], [ 'short'    => $short, 'pagesize' => $pagesize, 'pageno' => 1,
                                                         'severity' => $config['frontpage']['eventlog']['severity'] ]);

            print_events($eventlog_vars);

            echo '  </div>';
            echo '</div>';
            break;


        case "realtime":
            echo '  <div class="box box-solid do-not-update" style="overflow: hidden;">';
            $realtime_link = 'graph-realtime.php?type=bits&amp;id=430082&amp;interval=10';

            ?>
            <object data="<?php echo($realtime_link); ?>" type="image/svg+xml" width="<?php echo $width; ?>"
                    height="<?php echo $height; ?>">
                <param name="src"
                       value="graph.php?type=bits&amp;id=<?php echo($port['port_id'] . "&amp;interval=" . $vars['interval']); ?>"/>
                Your browser does not support SVG! You need to either use Firefox or Chrome, or download the Adobe SVG
                plugin.
            </object>
            <?php
            echo '</div>';
            break;

        default:

            $widget_path = $config['html_dir'] . '/includes/widgets/' . $mod['widget_type'] . '.inc.php';

            if (is_file($widget_path)) {
                include($widget_path);
            } else {
                echo '<div class="grid-stack-item-content box box-solid" style="overflow: hidden; justify-content: center; align-items: center;">';
                echo '  <div class="box-content" style="overflow: hidden;">';
                echo '    <h3 class="box-title">Unconfigured Module</h3>';
                echo '  </div>';
                echo '</div>';
                break;
            }
    }

    //echo '</div>';

}

function print_dash_map($mod, $width, $height)
{

    global $config;

    ?>
    <style type="text/css">
        #map<?php echo $mod['widget_id']; ?> label {
            width: auto;
            display: inline;
        }

        #map<?php echo $mod['widget_id']; ?> img {
            max-width: none;
        }

        #map<?php echo $mod['widget_id']; ?> {
            height: 100%;
            width: 100%;
        }
    </style>

    <?php

    echo '<div id="map' . $mod['widget_id'] . '"></div>';

    $vars = $mod['vars']; // set the $vars array to be used mostly by geojson

    include($config['html_dir'] . '/includes/map/leaflet.inc.php');

} // End show_map

// EOF
