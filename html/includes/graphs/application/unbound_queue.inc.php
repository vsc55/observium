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
$nototal      = 1;
$unit_text    = "Queries";
$rrd_filename = get_rrd_path($device, "app-unbound-" . $app['app_id'] . "-total.rrd");

$array = [
  'reqListAvg'         => ['descr' => 'Average size', 'colour' => '00FF00FF'],
  'reqListMax'         => ['descr' => 'Max size', 'colour' => '0000FFFF'],
  'reqListOverwritten' => ['descr' => 'Replaced', 'colour' => 'FF0000FF'],
  'reqListExceeded'    => ['descr' => 'Dropped', 'colour' => '00FFFFFF'],
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

include($config['html_dir'] . "/includes/graphs/generic_multi_line.inc.php");

// EOF
