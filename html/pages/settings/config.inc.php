<?php
/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package        observium
 * @subpackage     web
 * @copyright  (C) Adam Armstrong
 *
 */

if ($_SESSION['userlevel'] < 10) {
    print_error_permission();
    return;
}

print_warning('This is a full dump of your Observium configuration. To adjust it, please use the <a href="/settings/">configuration editor</a> and/or modify your <strong>config.php</strong> file.');
print_vars($config);

// EOF
