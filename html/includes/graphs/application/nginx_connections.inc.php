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

$scale_min = 0;

include_once($config['html_dir'] . "/includes/graphs/common.inc.php");

$rrd_filename = get_rrd_path($device, "app-nginx-" . $app['app_id'] . ".rrd");

$array = ['Reading' => ['descr' => 'Reading', 'colour' => '750F7DFF'],
          'Writing' => ['descr' => 'Writing', 'colour' => '00FF00FF'],
          'Waiting' => ['descr' => 'Waiting', 'colour' => '4444FFFF'],
          'Active'  => ['descr' => 'Starting', 'colour' => '157419FF'],
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

$colours   = "mixed";
$nototal   = 1;
$unit_text = "Workers";

include($config['html_dir'] . "/includes/graphs/generic_multi_simplex_separated.inc.php");

// EOF
