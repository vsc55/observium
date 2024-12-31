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

$colours        = "mixed";
$nototal        = (($width < 224) ? 1 : 0);
$unit_text      = "Milliseconds";
$ntpdserver_rrd = get_rrd_path($device, "app-ntpd-server-" . $app['app_id'] . ".rrd");
$ntpdclient_rrd = get_rrd_path($device, "app-ntpd-client-" . $app['app_id'] . ".rrd");
$array          = [
  'offset'    => ['descr' => 'Offset'],
  'jitter'    => ['descr' => 'Jitter'],
  'noise'     => ['descr' => 'Noise'],
  'stability' => ['descr' => 'Stability']
];

if (rrd_is_file($ntpdclient_rrd)) {
    $rrd_filename = $ntpdclient_rrd;
}

if (rrd_is_file($ntpdserver_rrd)) {
    $rrd_filename = $ntpdserver_rrd;
}

if (rrd_is_file($rrd_filename)) {
    $i = 0;

    foreach ($array as $ds => $data) {
        $rrd_list[$i]['filename'] = $rrd_filename;
        $rrd_list[$i]['descr']    = $data['descr'];
        $rrd_list[$i]['ds']       = $ds;
        $rrd_list[$i]['colour']   = $config['graph_colours'][$colours][$i];
        $i++;
    }
} else {
    echo("file missing: $rrd_filename");
}

include($config['html_dir'] . "/includes/graphs/generic_multi_line.inc.php");

// EOF