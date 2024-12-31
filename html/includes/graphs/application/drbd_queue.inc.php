<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package        observium
 * @subpackage     graphs
 * @copyright  (C) Adam Armstrong
 *
 */

include_once($config['html_dir'] . "/includes/graphs/common.inc.php");

$rrd_filename = get_rrd_path($device, "app-drbd-" . $app['app_instance'] . ".rrd");

$array = [
  'lo' => 'Local I/O',
  'pe' => 'Pending',
  'ua' => 'UnAcked',
  'ap' => 'App Pending',
];

$i = 0;
if (rrd_is_file($rrd_filename)) {
    foreach ($array as $ds => $data) {
        $rrd_list[$i]['filename'] = $rrd_filename;
        if (is_array($data)) {
            $rrd_list[$i]['descr'] = $data['descr'];
        } else {
            $rrd_list[$i]['descr'] = $data;
        }
        $rrd_list[$i]['ds'] = $ds;
        $i++;
    }
} else {
    echo("file missing: $rrd_filename");
}

$colours   = "mixed";
$nototal   = 0;
$unit_text = "";

include($config['html_dir'] . "/includes/graphs/generic_multi_simplex_separated.inc.php");

// EOF