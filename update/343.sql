ALTER TABLE `mac_accounting` ADD `bytes_input` bigint(20) DEFAULT NULL, ADD `bytes_input_delta` bigint(20) DEFAULT NULL, ADD `bytes_input_rate` int(11) DEFAULT NULL, ADD `bytes_output` bigint(20) DEFAULT NULL, ADD `bytes_output_delta` bigint(20) DEFAULT NULL, ADD `bytes_output_rate` int(11) DEFAULT NULL, ADD `pkts_input` bigint(20) DEFAULT NULL, ADD `pkts_input_delta` bigint(20) DEFAULT NULL, ADD `pkts_input_rate` int(11) DEFAULT NULL, ADD `pkts_output` bigint(20) DEFAULT NULL, ADD `pkts_output_delta` bigint(20) DEFAULT NULL, ADD `pkts_output_rate` int(11) DEFAULT NULL, ADD `poll_time` int(11) DEFAULT NULL, ADD `poll_period` int(11) DEFAULT NULL;
UPDATE `mac_accounting` p, `mac_accounting-state` s SET p.`bytes_input` = s.`bytes_input`,p.`bytes_input_delta` = s.`bytes_input_delta`,p.`bytes_input_rate` = s.`bytes_input_rate`,p.`bytes_output` = s.`bytes_output`,p.`bytes_output_delta` = s.`bytes_output_delta`,p.`bytes_output_rate` = s.`bytes_output_rate`,p.`pkts_input` = s.`pkts_input`,p.`pkts_input_delta` = s.`pkts_input_delta`,p.`pkts_input_rate` = s.`pkts_input_rate`,p.`pkts_output` = s.`pkts_output`,p.`pkts_output_delta` = s.`pkts_output_delta`,p.`pkts_output_rate` = s.`pkts_output_rate`,p.`poll_time` = s.`poll_time`,p.`poll_period` = s.`poll_period` WHERE p.`ma_id` = s.`ma_id`;
DROP TABLE `mac_accounting-state`;
