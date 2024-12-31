<?php

/**
 * Docker discovery and monitoring
 * 
 * Parse docker stats and info from agent <data value="
 * 
 * Author: VSC55 (Javier Pastor)
 * Version: 1.0
 * 
 */

$dockers       = json_decode($agent_data['docker']);
unset($agent_data['docker']);

$dockers_info         = $dockers->info;
$dockers_stats        = $dockers->stats;
$dockers_stats_result = [];
unset($dockers);

if (! function_exists('convertToBytesDocker'))
{
    function convertToBytesDocker($size = "0") {
        $units = [
            'B'   => 1,
            'kB'  => 1000,
            'MB'  => 1000 ** 2,
            'GB'  => 1000 ** 3,
            'TB'  => 1000 ** 4,
            'KiB' => 1024,
            'MiB' => 1024 ** 2,
            'GiB' => 1024 ** 3,
            'TiB' => 1024 ** 4
        ];
        $return_data = "0";
        $size = trim($size);
        foreach ($units as $unit => $factor) {
            if (stripos($size, $unit) !== false) {
                $value = floatval($size);
                $return_data = $value * $factor;
            }
        }
        return $return_data;
    }
}

//Stats:
// IN
// $dockers_stats = [
//     [
//         "ID" => "4cc28876c952",
//         "BlockIO" => "53.9MB / 737kB",
//         "NetIO" => "1.82MB / 433kB",
//         "MemUsage" => "59.61MiB / 7.755GiB",
//         "CPUPerc" => "0.02%",
//         "Name" => "www_LogAnalyzer",
//         "PIDs" => "9"
//     ],
// ];
// OUT
// $dockers_stats_result = [
//     "4cc28876c952" => [
//         "ID" => "4cc28876c952",
//         "BlockIO" => [
//             "in" => 53900000,
//             "out" => 737000
//         ],
//         "NetIO" => [
//             "in" => 1820000,
//             "out" => 433000
//         ],
//         "MemUsage" => [
//             "current" => 62566400,
//             "max" => 8327507968
//         ],
//         "CPUPerc" => "0.02%",
//         "Name" => "www_LogAnalyzer",
//         "PIDs" => "9"
//     ],
// ];
foreach ($dockers_stats as $docker_stats)
{
    $id = $docker_stats->ID;
    foreach ($docker_stats as $key => &$value)
    {
        if (in_array($key, ["BlockIO", "NetIO"])) {
            list($value1, $value2) = explode(' / ', $value);
            $value = [
                "in"  => convertToBytesDocker($value1),
                "out" => convertToBytesDocker($value2)
            ];
        }
        if (in_array($key, ["MemUsage"])) {
            list($value1, $value2) = explode(' / ', $value);
            $value = [
                "current" => convertToBytesDocker($value1),
                "max"     => convertToBytesDocker($value2)
            ];
        }
    }
    $dockers_stats_result[$id] = $docker_stats;
}
unset($dockers_stats);

echo("Dockers: ");
foreach ($dockers_info as $docker)
{    
    // Docker:
    // "Command": "/entrypoint.sh start",
    // "CreatedAt": "2024-07-09 19:34:11 +0200 CEST",
    // "ID": "4cc28876c952",
    // "Image": "vsc55/loganalyzer:latest",
    // "Labels": "description=Docker webapp loganalyzer,com.docker.compose.config-hash=c517a51f0266dd38b7557d79fcd7e10cab194bd08a8fcfd25fe37978b17316dd,com.docker.compose.depends_on=,com.docker.compose.oneoff=False,com.docker.compose.project=loganalyzer,com.docker.compose.project.config_files=/data/compose/29/v1/docker-compose.yml,com.docker.compose.project.working_dir=/data/compose/29/v1,com.docker.compose.version=2.26.1,com.docker.compose.container-number=1,com.docker.compose.image=sha256:6f9f86177fe241b0a71f34cd4d1b085909c8aa7012500f1de9d4d5321b1837ca,com.docker.compose.service=LogAnalyzer,maintainer=vsc55@cerebelum.net,version=1.1",
    // "LocalVolumes": "0",
    // "Mounts": "/docker/log_da…",
    // "Names": "www_LogAnalyzer",
    // "Networks": "bridge",
    // "Ports": "0.0.0.0:8086->80/tcp, [::]:8086->80/tcp",
    // "RunningFor": "5 months ago",
    // "Size": "0B",
    // "State": "running",
    // "Status": "Up 47 hours (healthy)"

    $docker_id   = $docker->ID;
    $device_data = [
        'id'        => $docker_id,
        'name'      => $docker->Names,
        'cpucount'  => "1",
        'memory'    => $dockers_stats_result[$docker_id]->MemUsage['max'],
        'status'    => $docker->State,
        'os'        => $docker->Image,
        'type'      => 'docker',
        'source'    => 'agent'
    ];

    discover_virtual_machine($valid['vm'], $device, $device_data);
}

echo(PHP_EOL);

unset($dockers_info);
unset($dockers_stats_result);

// EOF