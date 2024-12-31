<?php
/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage graphs
 * @copyright  (C) Adam Armstrong
 *
 */

if (!is_array($vars['id'])) {
    $vars['id'] = [$vars['id']];
}

$auth = TRUE;

foreach ($vars['id'] as $status_id) {
    if (!$auth && !is_status_permitted('status', $status_id)) {
        $auth = FALSE;
    }
}

$title = "Multi status :: ";

// EOF
