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
$colours      = "mixed";
$nototal      = (($width < 224) ? 1 : 0);
$unit_text    = "Buffer";
$rrd_filename = get_rrd_path($device, "app-ntpdserver-" . $app['app_id'] . ".rrd");
$array        = [
  'buffer_recv' => ['descr' => 'Received'],
  'buffer_used' => ['descr' => 'Used'],
  'buffer_free' => ['descr' => 'Free']
];

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
