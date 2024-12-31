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

/*
  DS:registraraccregs:COUNTER:600:0:125000000000 \
  DS:registrardefexpire:GAUGE:600:0:125000000000 \
  DS:registrardefexpirer:GAUGE:600:0:125000000000 \
  DS:registrarmaxcontact:GAUGE:600:0:125000000000 \
  DS:registrarmaxexpires:GAUGE:600:0:125000000000 \
  DS:registrarrejregs:COUNTER:600:0:125000000000 \
*/

include_once($config['html_dir'] . "/includes/graphs/common.inc.php");

$rrd_filename = get_rrd_path($device, "app-kamailio-" . $app['app_id'] . ".rrd");

$array = ['registraraccregs'    => ['descr' => 'Accepted Registrations'],
          'registrarmaxcontact' => ['descr' => 'Max Contacts'],
          'registrarrejregs'    => ['descr' => 'Rejected Registrations'],
];

$i = 0;
if (rrd_is_file($rrd_filename)) {
    foreach ($array as $ds => $data) {
        $rrd_list[$i]['filename'] = $rrd_filename;
        $rrd_list[$i]['descr']    = $data['descr'];
        $rrd_list[$i]['ds']       = $ds;
        $i++;
    }
} else {
    echo("file missing: $rrd_filename");
}

$colours = "mixed";

include($config['html_dir'] . "/includes/graphs/generic_multi_line.inc.php");

// EOF