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

?>
    <div style="text-align: center; height: 100%;">
        <object data="/networkmap.php?device=<?php echo($device['device_id']); ?>&amp;format=svg" type="image/svg+xml"
                style="width: 100%; height:100%"></object>
    </div>

<?php
register_html_title("Map");

// EOF
