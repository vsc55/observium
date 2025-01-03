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

$dmi = $agent_data['dmi'];
unset($agent_data['dmi']);

foreach (explode("\n", $dmi) as $line) {
    [$field, $contents] = explode("=", $line, 2);
    $agent_data['dmi'][$field] = trim($contents);
}

unset($dmi);

?>