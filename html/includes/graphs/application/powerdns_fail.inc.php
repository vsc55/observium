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

$scale_min    = 0;
$colours      = "red";
$nototal      = (($width < 224) ? 1 : 0);
$unit_text    = "Packets/sec";
$rrd_filename = get_rrd_path($device, "app-powerdns-" . $app['app_id'] . ".rrd");
$array        = [
  'corruptPackets'  => ['descr' => 'Corrupt', 'colour' => 'FF8800FF'],
  'servfailPackets' => ['descr' => 'Failed', 'colour' => 'FF0000FF'],
  'q_timedout'      => ['descr' => 'Timedout', 'colour' => 'FFFF00FF'],
];

$i = 0;

if (rrd_is_file($rrd_filename)) {
    foreach ($array as $ds => $data) {
        $rrd_list[$i]['filename'] = $rrd_filename;
        $rrd_list[$i]['descr']    = $data['descr'];
        $rrd_list[$i]['ds']       = $ds;
        $rrd_list[$i]['colour']   = $data['colour'];
        $i++;
    }
} else {
    echo("file missing: $rrd_filename");
}

include($config['html_dir'] . "/includes/graphs/generic_multi_simplex_separated.inc.php");

// EOF
