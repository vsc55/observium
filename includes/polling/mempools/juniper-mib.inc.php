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

$mib = 'JUNIPER-MIB';

$oids = ['jnxOperatingBuffer', 'jnxOperatingDRAMSize', 'jnxOperatingMemory'];

if (!is_array($cache_storage[$mib])) {
    foreach ($oids as $oid) {
        $cache_mempool = snmpwalk_cache_oid($device, $oid, $cache_mempool, $mib);
    }
    $cache_storage[$mib] = $cache_mempool;
} else {
    print_debug("Cached!");
    $cache_mempool = $cache_storage[$mib];
}

if ($mempool['mempool_multiplier'] == 1) {
    $mempool['total'] = $cache_mempool[$index]['jnxOperatingDRAMSize'];
} else {
    $mempool['total'] = $cache_mempool[$index]['jnxOperatingMemory'];
}

$mempool['perc'] = $cache_mempool[$index]['jnxOperatingBuffer'];

// EOF