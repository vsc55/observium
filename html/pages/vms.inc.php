<?php

/**
 * Observium Network Management and Monitoring System
 * Copyright (C) 2006-2015, Adam Armstrong - http://www.observium.org
 *
 * @package        observium
 * @subpackage     webui
 * @author         Adam Armstrong <adama@observium.org>
 * @copyright  (C) Adam Armstrong
 *
 */

// Possibly add navbar to filter on type? (Proxmox, VMWare, ...)

register_html_title('Virtual Machines');

print_vm_table($vars);

// EOF
