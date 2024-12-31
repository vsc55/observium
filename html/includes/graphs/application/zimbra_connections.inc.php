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

$rrd_filename = get_rrd_path($device, 'app-zimbra-mailboxd.rrd');

$array = [
  'imapSslConn' => ['descr' => 'IMAP SSL', 'colour' => '6EB7FF'],
  'imapConn'    => ['descr' => 'IMAP', 'colour' => '0082FF'],
  'popSslConn'  => ['descr' => 'POP3 SSL', 'colour' => '8FCB73'],
  'popConn'     => ['descr' => 'POP3', 'colour' => '3AB419'],
  'lmtpConn'    => ['descr' => 'LMTP', 'colour' => 'CC7CCC'],
];

$nototal   = 1;
$colours   = "mixed";
$unix_text = "Connections";

$i = 0;

if (rrd_is_file($rrd_filename)) {
    foreach ($array as $ds => $variables) {
        $rrd_list[$i]['filename'] = $rrd_filename;
        $rrd_list[$i]['descr']    = $variables['descr'];
        $rrd_list[$i]['ds']       = $ds;
        $rrd_list[$i]['colour']   = ($variables['colour'] ? $variables['colour'] : $config['graph_colours'][$colours][$i]);
        $i++;
    }
} else {
    echo("file missing: $rrd_filename");
}

include($config['html_dir'] . "/includes/graphs/generic_multi_simplex_separated.inc.php");

// EOF
