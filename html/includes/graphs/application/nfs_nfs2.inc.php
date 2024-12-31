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

$rrd_filename = get_rrd_path($device, "app-nfs-" . $app['app_id'] . ".rrd");

$array = [
  "null", "getattr", "setattr", "root", "lookup", "readlink",
  "read", "wrcache", "write", "create", "remove", "rename",
  "link", "symlink", "mkdir", "rmdir", "readdir", "fsstat"
];

$i = 0;

if (rrd_is_file($rrd_filename)) {
    foreach ($array as $name) {
        $rrd_list[$i]['filename'] = $rrd_filename;
        $rrd_list[$i]['descr']    = $name;
        $rrd_list[$i]['ds']       = 'proc2' . $name;
        $i++;
    }
} else {
    echo("file missing: $rrd_filename");
}

$colours   = "mixed";
$nototal   = 0;
$unit_text = "Rows";

include($config['html_dir'] . "/includes/graphs/generic_multi_simplex_separated.inc.php");

// EOF