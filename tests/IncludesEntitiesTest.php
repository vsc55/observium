<?php

//define('OBS_DEBUG', 2);

include(__DIR__ . '/../includes/defaults.inc.php');
//include(dirname(__FILE__) . '/../config.php'); // Do not include user editable config here
include(__DIR__ . "/../includes/polyfill.inc.php");
include(__DIR__ . "/../includes/autoloader.inc.php");
include(__DIR__ . "/../includes/debugging.inc.php");
require_once(__DIR__ ."/../includes/constants.inc.php");
include(__DIR__ . '/../includes/common.inc.php');
include(__DIR__ . '/../includes/definitions.inc.php');
include(__DIR__ . '/data/test_definitions.inc.php'); // Fake definitions for testing
include(__DIR__ . '/../includes/functions.inc.php');

class IncludesEntitiesTest extends \PHPUnit\Framework\TestCase {

    /**
    * @dataProvider providerEntityDescrDefinition
    * @group descr
    */
    public function testEntityDescrDefinition($type, $result, $definition, $descr_entry, $count = 1) {
        $this->assertSame($result, entity_descr_definition($type, $definition, $descr_entry, $count));
    }


    public function providerEntityDescrDefinition() {
        $result = array();

        // Mempool
        $type = 'mempool';
        $definition = array();
        $array      = array('i' => '22', 'index' => '33');

        // Defaults from entity definition
        $result[] = array($type, 'Memory',          $definition, $array);
        $result[] = array($type, 'Memory Pool 33',  $definition, $array, 2);

        // Descr from oid_descr, but it empty
        $definition['oid_descr'] = 'OidName';
        $result[] = array($type, 'Memory',          $definition, $array);
        // Descr from descr
        $definition['descr'] = 'Name from Descr';
        $result[] = array($type, 'Name from Descr', $definition, $array);
        $result[] = array($type, 'Name from Descr 33', $definition, $array, 2);
        // Descr from oid_descr
        $array['OidName'] = 'Name from Oid';
        $result[] = array($type, 'Name from Oid',   $definition, $array);
        $result[] = array($type, 'Name from Oid',   $definition, $array, 2);
        // Now descr use tags
        $definition['descr'] = 'Name from Descr with Tags (%i%) {%index%} [%oid_descr%]';
        $result[] = array($type, 'Name from Descr with Tags (22) {33} [Name from Oid]', $definition, $array);
        $definition['descr'] = 'Name from Descr with Tags (%OidName%)';
        $result[] = array($type, 'Name from Descr with Tags (Name from Oid)', $definition, $array);
        // Tag multiple times
        $definition['descr'] = 'Name from Descr with multiple Tags {%oid_descr%} [%oid_descr%]';
        $result[] = array($type, 'Name from Descr with multiple Tags {Name from Oid} [Name from Oid]', $definition, $array);
        // Multipart indexes
        $definition['descr'] = 'Name from Descr with Tags {%index0%}';
        $result[] = array($type, 'Name from Descr with Tags {33}', $definition, $array);
        $array['index'] = '11.22.33.44.55';
        $definition['descr'] = 'Name from Descr with Multipart Index {%index1%} {%index3%} {%index2%} [%index%]';
        $result[] = array($type, 'Name from Descr with Multipart Index {22} {44} {33} [11.22.33.44.55]', $definition, $array);

        // Sensors
        $type = 'sensor';
        $definition = array();
        $array      = array('i' => '22', 'index' => '33');

        $definition['oid_descr'] = 'jnxOperatingDescr';
        $definition['descr_transform'] = ['action' => 'entity_name'];

        $array['jnxOperatingDescr'] = "PIC: 4x 10GE(LAN) SFP+     @ 0/0/*";
        $result[] = array($type, 'PIC: 4x 10GE(LAN) SFP+ @ 0/0/*',   $definition, $array);

        return $result;
    }

    /**
     * @dataProvider providerGetDeviceSnmpArgv
     * @group device
     */
    public function testGetDeviceSnmpArgv($argv, $result, $options = []) {
        $this->assertSame($result, get_device_snmp_argv($argv, $snmp_options));
        if ($snmp_options || $options) {
            // Common snmp v3 or context
            $this->assertSame($options, $snmp_options);
        }
    }

    public function providerGetDeviceSnmpArgv() {
        $array = [];

        // SNMP v1
        // hostname.test community v1
        $array[] = [ [ 'hostname.test', 'community', 'v1' ],
                     [ 'hostname' => 'hostname.test', 'snmp_community' => [ 'community' ], 'snmp_version' => 'v1', 'snmp_transport' => 'udp', 'snmp_port' => 161 ] ];
        // hostname.test community v1 tcp context
        $array[] = [ [ 'hostname.test', 'community', 'v1', 'tcp', 'context' ],
                     [ 'hostname' => 'hostname.test', 'snmp_community' => [ 'community' ], 'snmp_version' => 'v1', 'snmp_transport' => 'tcp', 'snmp_port' => 161 ],
                     [ 'snmp_context' => 'context' ] ];

        // SNMP v2c (default)
        // hostname.test community
        $array[] = [ [ 'hostname.test', 'community', ],
                     [ 'hostname' => 'hostname.test', 'snmp_community' => [ 'community' ], 'snmp_version' => 'v2c', 'snmp_transport' => 'udp', 'snmp_port' => 161 ] ];
        // hostname.test community v2c
        $array[] = [ [ 'hostname.test', 'community', 'v2c' ],
                     [ 'hostname' => 'hostname.test', 'snmp_community' => [ 'community' ], 'snmp_version' => 'v2c', 'snmp_transport' => 'udp', 'snmp_port' => 161 ] ];
        // hostname.test community v2c tcp context
        $array[] = [ [ 'hostname.test', 'community', 'v2c', 'tcp', 'context' ],
                     [ 'hostname' => 'hostname.test', 'snmp_community' => [ 'community' ], 'snmp_version' => 'v2c', 'snmp_transport' => 'tcp', 'snmp_port' => 161 ],
                     [ 'snmp_context' => 'context' ] ];

        // SNMP v3
        // hostname.test nanp v3 username
        $snmp_v3_auth = [ [ 'authlevel' => 'noAuthNoPriv', 'authname' => 'username' ] ];
        $array[] = [ [ 'hostname.test', 'nanp', 'v3', 'username' ],
                     [ 'hostname' => 'hostname.test', 'snmp_v3_auth' => $snmp_v3_auth, 'snmp_version' => 'v3', 'snmp_transport' => 'udp', 'snmp_port' => 161 ] ];
        // hostname.test nanp v3 tcp context
        $snmp_v3_auth = [ [ 'authlevel' => 'noAuthNoPriv', 'authname' => 'observium' ] ];
        $array[] = [ [ 'hostname.test', 'nanp', 'v3', 'tcp', 'context' ],
                     [ 'hostname' => 'hostname.test', 'snmp_v3_auth' => $snmp_v3_auth, 'snmp_version' => 'v3', 'snmp_transport' => 'tcp', 'snmp_port' => 161 ],
                     [ 'snmp_context' => 'context' ] ];

        // hostname.test anp v3 username password sha
        $snmp_v3_auth = [ [ 'authlevel' => 'authNoPriv', 'authname' => 'username', 'authpass' => 'password', 'authalgo' => 'sha' ] ];
        $array[] = [ [ 'hostname.test', 'anp', 'v3', 'username', 'password', 'sha' ],
                     [ 'hostname' => 'hostname.test', 'snmp_v3_auth' => $snmp_v3_auth, 'snmp_version' => 'v3', 'snmp_transport' => 'udp', 'snmp_port' => 161 ] ];
        // hostname.test anp v3 username password tcp context
        $snmp_v3_auth = [ [ 'authlevel' => 'authNoPriv', 'authname' => 'username', 'authpass' => 'password', 'authalgo' => 'MD5' ] ];
        $array[] = [ [ 'hostname.test', 'anp', 'v3', 'username', 'password', 'tcp', 'context' ],
                     [ 'hostname' => 'hostname.test', 'snmp_v3_auth' => $snmp_v3_auth, 'snmp_version' => 'v3', 'snmp_transport' => 'tcp', 'snmp_port' => 161 ],
                     [ 'snmp_context' => 'context' ] ];

        // hostname.test ap v3 username password encpass sha des
        $snmp_v3_auth = [ [ 'authlevel' => 'authPriv', 'authname' => 'username', 'authpass' => 'password', 'authalgo' => 'sha', 'cryptopass' => 'encpass', 'cryptoalgo' => 'des' ] ];
        $array[] = [ [ 'hostname.test', 'ap', 'v3', 'username', 'password', 'encpass', 'sha', 'des' ],
                     [ 'hostname' => 'hostname.test', 'snmp_v3_auth' => $snmp_v3_auth, 'snmp_version' => 'v3', 'snmp_transport' => 'udp', 'snmp_port' => 161 ] ];
        // hostname.test ap v3 username password encpass tcp context
        $snmp_v3_auth = [ [ 'authlevel' => 'authPriv', 'authname' => 'username', 'authpass' => 'password', 'authalgo' => 'MD5', 'cryptopass' => 'encpass', 'cryptoalgo' => 'AES' ] ];
        $array[] = [ [ 'hostname.test', 'ap', 'v3', 'username', 'password', 'encpass', 'tcp', 'context' ],
                     [ 'hostname' => 'hostname.test', 'snmp_v3_auth' => $snmp_v3_auth, 'snmp_version' => 'v3', 'snmp_transport' => 'tcp', 'snmp_port' => 161 ],
                     [ 'snmp_context' => 'context' ] ];

        // SNMP port
        // hostname.test:123 community
        $array[] = [ [ 'hostname.test:123', 'community' ],
                     [ 'hostname' => 'hostname.test', 'snmp_community' => [ 'community' ], 'snmp_version' => 'v2c', 'snmp_transport' => 'udp', 'snmp_port' => 123 ] ];
        // 127.0.0.1:123 community
        $array[] = [ [ '127.0.0.1:123', 'community', 'v2c' ],
                     [ 'hostname' => '127.0.0.1', 'snmp_community' => [ 'community' ], 'snmp_version' => 'v2c', 'snmp_transport' => 'udp', 'snmp_port' => 123 ] ];
        // hostname.test community v2c 123
        $array[] = [ [ 'hostname.test', 'community', 'v2c', '123' ],
                     [ 'hostname' => 'hostname.test', 'snmp_community' => [ 'community' ], 'snmp_version' => 'v2c', 'snmp_transport' => 'udp', 'snmp_port' => 123 ] ];
        // ::1 community v2c 123
        $array[] = [ [ '::1', 'community', 'v2c', '123' ],
                     [ 'hostname' => '::1', 'snmp_community' => [ 'community' ], 'snmp_version' => 'v2c', 'snmp_transport' => 'udp', 'snmp_port' => 123 ] ];

        // Only hostname/ip for detect all possible params
        // hostname.test
        $array[] = [ [ 'hostname.test' ],
                     [ 'hostname' => 'hostname.test', 'snmp_version' => NULL, 'snmp_transport' => 'udp', 'snmp_port' => 161 ] ];
        // 127.0.0.1
        $array[] = [ [ '127.0.0.1' ],
                     [ 'hostname' => '127.0.0.1', 'snmp_version' => NULL, 'snmp_transport' => 'udp', 'snmp_port' => 161 ] ];

        return $array;
    }

    /**
     * @dataProvider providerIsModuleEnabled
     * @group device
     */
    public function testIsModuleEnabled($device, $module, $default, $enabled, $disabled, $attrib = TRUE) {
        $process = 'poller';
        // Pseudo cache for attribs:
        $GLOBALS['cache']['entity_attribs_all']['device'][$device['device_id']] = []; // set array, for skip query db
        // Check definition(s)
        $orig = $GLOBALS['config']['poller_modules'][$module];
        $this->assertSame($default, is_module_enabled($device, $module, $process));
        $GLOBALS['config']['poller_modules'][$module] = 1;
        $this->assertSame($enabled, is_module_enabled($device, $module, $process));
        $GLOBALS['config']['poller_modules'][$module] = 0;
        $this->assertSame($disabled, is_module_enabled($device, $module, $process));
        $GLOBALS['config']['poller_modules'][$module] = $orig;

        if ($module === 'os') { return; } // os ignore attrib

        // Check attrib
        $setting_name = 'poll_' . $module;
        $GLOBALS['cache']['entity_attribs']['device'][$device['device_id']][$setting_name] = '1'; // attrib true
        if ($attrib) {
            $this->assertTrue(is_module_enabled($device, $module, $process));
        } else {
            $this->assertFalse(is_module_enabled($device, $module, $process));
        }
        $GLOBALS['cache']['entity_attribs']['device'][$device['device_id']][$setting_name] = '0'; // attrib false
        $this->assertFalse(is_module_enabled($device, $module, $process));
    }

    public function providerIsModuleEnabled() {
        $device_linux = [ 'device_id' => 999, 'os' => 'linux', 'os_group' => 'unix' ];
        $device_win   = [ 'device_id' => 998, 'os' => 'windows' ];
        $device_ios   = [ 'device_id' => 997, 'os' => 'ios', 'os_group' => 'cisco', 'type' => 'network' ];
        $device_vrp   = [ 'device_id' => 996, 'os' => 'vrp', 'type' => 'network' ];
        $device_amm   = [ 'device_id' => 995, 'os' => 'ibm-amm' ]; // poller/discovery blacklists
        $device_gen   = [ 'device_id' => 994, 'os' => 'generic' ];
        $device_aruba = [ 'device_id' => 993, 'os' => 'arubaos', 'type' => 'wireless' ];

        $result = [];
        $result[] = [ $device_linux, 'os', TRUE, TRUE, TRUE ];
        $result[] = [ $device_win,   'os', TRUE, TRUE, TRUE ];
        $result[] = [ $device_ios,   'os', TRUE, TRUE, TRUE ];
        $result[] = [ $device_vrp,   'os', TRUE, TRUE, TRUE ];
        $result[] = [ $device_amm,   'os', TRUE, TRUE, TRUE ];
        $result[] = [ $device_gen,   'os', TRUE, TRUE, TRUE ];

        $result[] = [ $device_linux, 'unix-agent', FALSE,  TRUE, FALSE ];
        $result[] = [ $device_win,   'unix-agent', FALSE, FALSE, FALSE, FALSE ];
        $result[] = [ $device_ios,   'unix-agent', FALSE, FALSE, FALSE, FALSE ];
        $result[] = [ $device_vrp,   'unix-agent', FALSE, FALSE, FALSE, FALSE ];
        $result[] = [ $device_amm,   'unix-agent', FALSE, FALSE, FALSE, FALSE ];
        //$result[] = [ $device_gen,   'unix-agent', FALSE,  TRUE, FALSE ];

        $result[] = [ $device_linux, 'ipmi',  TRUE,  TRUE, FALSE ];
        $result[] = [ $device_win,   'ipmi',  TRUE,  TRUE, FALSE ];
        $result[] = [ $device_ios,   'ipmi', FALSE, FALSE, FALSE, FALSE ];
        $result[] = [ $device_vrp,   'ipmi', FALSE, FALSE, FALSE, FALSE ];
        $result[] = [ $device_amm,   'ipmi',  TRUE,  TRUE, FALSE ];
        $result[] = [ $device_gen,   'ipmi',  TRUE,  TRUE, FALSE ];

        $result[] = [ $device_linux, 'ports',  TRUE,  TRUE, FALSE ];
        $result[] = [ $device_win,   'ports',  TRUE,  TRUE, FALSE ];
        $result[] = [ $device_ios,   'ports',  TRUE,  TRUE, FALSE ];
        $result[] = [ $device_vrp,   'ports',  TRUE,  TRUE, FALSE ];
        $result[] = [ $device_amm,   'ports', FALSE, FALSE, FALSE, FALSE ];
        $result[] = [ $device_gen,   'ports',  TRUE,  TRUE, FALSE ];

        $result[] = [ $device_linux, 'wifi', FALSE, FALSE, FALSE ];
        $result[] = [ $device_win,   'wifi', FALSE, FALSE, FALSE ];
        $result[] = [ $device_ios,   'wifi',  TRUE,  TRUE, FALSE ];
        $result[] = [ $device_vrp,   'wifi',  TRUE,  TRUE, FALSE ];
        $result[] = [ $device_amm,   'wifi', FALSE, FALSE, FALSE ];
        $result[] = [ $device_gen,   'wifi', FALSE, FALSE, FALSE ];
        $result[] = [ $device_aruba, 'wifi',  TRUE,  TRUE, FALSE ];

        foreach ([ 'cipsec-tunnels', 'cisco-ipsec-flow-monitor', 'cisco-remote-access-monitor',
                   'cisco-cef', 'cisco-cbqos', 'cisco-eigrp', /*'cisco-vpdn'*/ ] as $module) {
            $result[] = [ $device_linux, $module, FALSE, FALSE, FALSE, FALSE ];
            $result[] = [ $device_win,   $module, FALSE, FALSE, FALSE, FALSE ];
            $result[] = [ $device_ios,   $module, TRUE, TRUE, FALSE ];
            $result[] = [ $device_vrp,   $module, FALSE, FALSE, FALSE, FALSE ];
            $result[] = [ $device_amm,   $module, FALSE, FALSE, FALSE, FALSE ];
            $result[] = [ $device_gen,   $module, FALSE, FALSE, FALSE, FALSE ];
        }

        $result[] = [ $device_linux, 'aruba-controller', FALSE, FALSE, FALSE, FALSE ];
        $result[] = [ $device_win,   'aruba-controller', FALSE, FALSE, FALSE, FALSE ];
        $result[] = [ $device_ios,   'aruba-controller', FALSE, FALSE, FALSE, FALSE ];
        $result[] = [ $device_vrp,   'aruba-controller', FALSE, FALSE, FALSE, FALSE ];
        $result[] = [ $device_amm,   'aruba-controller', FALSE, FALSE, FALSE, FALSE ];
        $result[] = [ $device_gen,   'aruba-controller', FALSE, FALSE, FALSE, FALSE ];
        $result[] = [ $device_aruba, 'aruba-controller',  TRUE,  TRUE, FALSE ];

        return $result;
    }
}

// EOF
