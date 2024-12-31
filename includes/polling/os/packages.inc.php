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

// Detect Proxmox version is hard (by api package version)
// Better by unix-agent packages..

if (!isset($config['os'][$device['os']]['packages'])) {
    return;
}

unset($version); // reset kernel based version

$metatypes = ['features', 'type', 'version'];
foreach (poll_device_unix_packages($device, $metatypes) as $metatype => $value) {
    $$metatype = $value;
}

// EOF