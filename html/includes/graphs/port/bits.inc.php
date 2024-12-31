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

$ds_in  = "INOCTETS";
$ds_out = "OUTOCTETS";

$graph_max = 1;

include($config['html_dir'] . "/includes/graphs/generic_data.inc.php");

// Bits specific options
$graph_return['valid_options'][] = "style";
$graph_return['valid_options'][] = "scale";

// EOF