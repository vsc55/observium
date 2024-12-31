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

// FIXME -- bit derpy, maybe replace this.

include_once($config['html_dir'] . "/includes/graphs/common.inc.php");

$scale_min = "0";
$scale_max = "100";

if ($width > 500) {
    $descr_len = 22;
} else {
    $descr_len = 12;
}
$descr_len += round(($width - 250) / 8);

if ($width > "1000") {
    $descr_len = 36;
} elseif ($width > "500") {
    $descr_len = 24;
} else {
    $descr_len = 12;
    $descr_len += round(($width - 200) / 8);
}


$iter    = 0;
$colours = 'mixed';

$rrd_options .= " COMMENT:'" . str_pad('Size      Used    %used', $descr_len + 31, ' ', STR_PAD_LEFT) . "\\l'";


foreach ($vars['id'] as $mempool_id) {

    $mempool = dbFetchRow("SELECT * FROM `mempools` WHERE `mempool_id` = ?", [$mempool_id]);
    $device  = device_by_id_cache($mempool['device_id']);
    if (!$config['graph_colours'][$colours][$iter]) {
        $iter = 0;
    }
    $colour = $config['graph_colours'][$colours][$iter];

    $descr = rrdtool_escape(rewrite_entity_name($mempool['mempool_descr'], 'mempool'), $descr_len);

    if (isset($mempool['mempool_table'])) {
        $rrd_filename = get_rrd_path($device, "mempool-" . strtolower($mempool['mempool_mib']) . "-" . $mempool['mempool_table'] . "-" . $mempool['mempool_index'] . ".rrd");
    } else {
        $rrd_filename = get_rrd_path($device, "mempool-" . strtolower($mempool['mempool_mib']) . "-" . $mempool['mempool_index'] . ".rrd");
    }

    if (rrd_is_file($rrd_filename)) {
        $rrd_filename_escape = rrdtool_escape($rrd_filename);

        $rrd_options .= " DEF:" . $mempool['mempool_id'] . "used=$rrd_filename_escape:used:AVERAGE";
        $rrd_options .= " DEF:" . $mempool['mempool_id'] . "free=$rrd_filename_escape:free:AVERAGE";
        $rrd_options .= " CDEF:" . $mempool['mempool_id'] . "size=" . $mempool['mempool_id'] . "used," . $mempool['mempool_id'] . "free,+";
        $rrd_options .= " CDEF:" . $mempool['mempool_id'] . "perc=" . $mempool['mempool_id'] . "used," . $mempool['mempool_id'] . "size,/,100,*";
        $rrd_options .= " AREA:" . $mempool['mempool_id'] . "perc#" . $colour . "10";
        $rrd_options .= " LINE1.25:" . $mempool['mempool_id'] . "perc#" . $colour . ":'$descr'";
        $rrd_options .= " GPRINT:" . $mempool['mempool_id'] . "size:LAST:%6.2lf%sB";
        $rrd_options .= " GPRINT:" . $mempool['mempool_id'] . "used:LAST:%6.2lf%sB";
        $rrd_options .= " GPRINT:" . $mempool['mempool_id'] . "perc:LAST:%5.2lf%%\\l";
        $iter++;
    }
}


// EOF

