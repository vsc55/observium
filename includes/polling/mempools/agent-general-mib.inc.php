<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package        observium
 * @subpackage     poller
 * @copyright  (C) Adam Armstrong
 *
 */

$mib = 'AGENT-GENERAL-MIB';

$oids = ['agentDRAMutilizationTotalDRAM', 'agentDRAMutilizationUsedDRAM'];

if (!is_array($cache_storage[$mib])) {
    foreach ($oids as $oid) {
        $cache_mempool = snmpwalk_cache_oid($device, $oid, $cache_mempool, $mib);
    }
    $cache_storage[$mib] = $cache_mempool;
} else {
    print_debug("Cached!");
    $cache_mempool = $cache_storage[$mib];
}

$index            = $mempool['mempool_index'];
$mempool['used']  = $cache_mempool[$index]['agentDRAMutilizationUsedDRAM'];
$mempool['total'] = $cache_mempool[$index]['agentDRAMutilizationTotalDRAM'];

// EOF