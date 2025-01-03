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

$mib = 'HH3C-ENTITY-EXT-MIB';

if (!is_array($cache_storage[$mib])) {
    $cache_storage[$mib] = snmpwalk_cache_oid($device, 'hh3cEntityExtMemUsage', [], $mib);
    $cache_storage[$mib] = snmpwalk_cache_oid($device, 'hh3cEntityExtMemSize', $cache_storage[$mib], $mib);
} else {
    print_debug("Cached!");
}

$index         = $mempool['mempool_index'];
$cache_mempool = $cache_storage[$mib][$index];

$mempool['total'] = snmp_dewrap32bit($cache_mempool['hh3cEntityExtMemSize']);
$mempool['perc']  = $cache_mempool['hh3cEntityExtMemUsage'];

// EOF
