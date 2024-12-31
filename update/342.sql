ALTER TABLE `mempools` ADD `mempool_polled` int(11) NOT NULL, ADD `mempool_perc` int(11) NOT NULL, ADD `mempool_used` bigint(16) NOT NULL, ADD `mempool_free` bigint(16) NOT NULL, ADD `mempool_total` bigint(16) NOT NULL;
UPDATE `mempools` p, `mempools-state` s SET p.`mempool_polled` = s.`mempool_polled`,p.`mempool_perc` = s.`mempool_perc`,p.`mempool_used` = s.`mempool_used`,p.`mempool_free` = s.`mempool_free`,p.`mempool_total` = s.`mempool_total` WHERE p.`mempool_id` = s.`mempool_id`;
DROP TABLE `mempools-state`;
ALTER TABLE `processors` ADD `processor_usage` int(11) NOT NULL, ADD `processor_polled` int(11) NOT NULL;
UPDATE `processors` p, `processors-state` s SET p.`processor_usage` = s.`processor_usage`,p.`processor_polled` = s.`processor_polled` WHERE p.`processor_id` = s.`processor_id`;
DROP TABLE `processors-state`;
ALTER TABLE `pseudowires` ADD `pwOperStatus` varchar(16) NOT NULL, ADD `pwLocalStatus` varchar(16) NOT NULL, ADD `pwRemoteStatus` varchar(16) NOT NULL, ADD `pwUptime` int(11) UNSIGNED NOT NULL, ADD `event` enum('ok','warning','alert','ignore') NOT NULL, ADD `last_change` int(11) UNSIGNED NOT NULL;
UPDATE `pseudowires` p, `pseudowires-state` s SET p.`pwOperStatus` = s.`pwOperStatus`,p.`pwLocalStatus` = s.`pwLocalStatus`,p.`pwRemoteStatus` = s.`pwRemoteStatus`,p.`pwUptime` = s.`pwUptime`,p.`event` = s.`event`,p.`last_change` = s.`last_change` WHERE p.`pseudowire_id` = s.`pseudowire_id`;
DROP TABLE `pseudowires-state`;
ALTER TABLE `slas` ADD `rtt_value` decimal(11,2) NOT NULL, ADD `rtt_sense` varchar(32) CHARACTER SET utf8 NOT NULL, ADD `rtt_event` enum('ok','warning','alert','ignore') CHARACTER SET utf8 NOT NULL, ADD `rtt_unixtime` int(11) NOT NULL, ADD `rtt_last_change` int(11) NOT NULL, ADD `rtt_minimum` decimal(11,2) DEFAULT NULL, ADD `rtt_maximum` decimal(11,2) DEFAULT NULL, ADD `rtt_stddev` decimal(11,3) DEFAULT NULL, ADD `rtt_success` int(11) DEFAULT NULL, ADD `rtt_loss` int(11) DEFAULT NULL;
UPDATE `slas` p, `slas-state` s SET p.`rtt_value` = s.`rtt_value`,p.`rtt_sense` = s.`rtt_sense`,p.`rtt_event` = s.`rtt_event`,p.`rtt_unixtime` = s.`rtt_unixtime`,p.`rtt_last_change` = s.`rtt_last_change`,p.`rtt_minimum` = s.`rtt_minimum`,p.`rtt_maximum` = s.`rtt_maximum`,p.`rtt_stddev` = s.`rtt_stddev`,p.`rtt_success` = s.`rtt_success`,p.`rtt_loss` = s.`rtt_loss` WHERE p.`sla_id` = s.`sla_id`;
DROP TABLE `slas-state`;
ALTER TABLE `ucd_diskio` ADD `diskIONReadX` int(11) NOT NULL, ADD `diskIONReadX_rate` int(11) NOT NULL, ADD `diskIONWrittenX` int(11) NOT NULL, ADD `diskIONWrittenX_rate` int(11) NOT NULL, ADD `diskIOReads` int(11) NOT NULL, ADD `diskIOReads_rate` int(11) NOT NULL, ADD `diskIOWrites` int(11) NOT NULL, ADD `diskIOWrites_rate` int(11) NOT NULL, ADD `diskio_polled` int(11) NOT NULL;
UPDATE `ucd_diskio` p, `ucd_diskio-state` s SET p.`diskIONReadX` = s.`diskIONReadX`,p.`diskIONReadX_rate` = s.`diskIONReadX_rate`,p.`diskIONWrittenX` = s.`diskIONWrittenX`,p.`diskIONWrittenX_rate` = s.`diskIONWrittenX_rate`,p.`diskIOReads` = s.`diskIOReads`,p.`diskIOReads_rate` = s.`diskIOReads_rate`,p.`diskIOWrites` = s.`diskIOWrites`,p.`diskIOWrites_rate` = s.`diskIOWrites_rate`,p.`diskio_polled` = s.`diskio_polled` WHERE p.`diskio_id` = s.`diskio_id`;
drop table `ucd_diskio-state`;