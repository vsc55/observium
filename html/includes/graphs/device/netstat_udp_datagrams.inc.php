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

$rrd_filename = get_rrd_path($device, "netstats-udp.rrd");

$ds_in  = "udpInDatagrams";
$ds_out = "udpOutDatagrams";

$colour_area_in  = $config['colours']['graphs']['pkts']['in_area'];
$colour_line_in  = $config['colours']['graphs']['pkts']['in_line'];
$colour_area_out = $config['colours']['graphs']['pkts']['out_area'];
$colour_line_out = $config['colours']['graphs']['pkts']['out_line'];

$colour_area_in_max  = $config['colours']['graphs']['pkts']['in_max'];
$colour_area_out_max = $config['colours']['graphs']['pkts']['out_max'];

$graph_max = 1;
$unit_text = "Segments/s";

include($config['html_dir'] . "/includes/graphs/generic_duplex.inc.php");

?>
