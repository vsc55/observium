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

$rrd_filename = get_rrd_path($device, 'app-mongodb-' . $app["app_id"] . '.rrd');

$array = ['command_local'  => ['descr' => 'Commands local', 'colour' => '22FF22'],
          'command_replic' => ['descr' => 'Commands replica', 'colour' => '0022FF'],
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
$unit_text = "Replication";

include($config['html_dir'] . "/includes/graphs/generic_multi_simplex_separated.inc.php");

// EOF
