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

$where = ' WHERE 1 ' . generate_query_values_and($device['device_id'], 'device_id');

$timestamp_min = dbFetchCell('SELECT `timestamp` FROM `eventlog` ' . $where . ' ORDER BY `timestamp` LIMIT 0,1;');
$timestamp_max = dbFetchCell('SELECT `timestamp` FROM `eventlog` ' . $where . ' ORDER BY `timestamp` DESC LIMIT 0,1;');

// Note, this form have more complex grid and class elements for responsive datetime field
$form = ['type'          => 'rows',
         'space'         => '5px',
         'submit_by_key' => TRUE,
         'url'           => generate_url($vars)];

// Message field
$form['row'][0]['message'] = [
  'type'        => 'text',
  'name'        => 'Message',
  'placeholder' => 'Message',
  'width'       => '100%',
  'div_class'   => 'col-lg-4 col-md-6 col-sm-6',
  'value'       => $vars['message']];

// Severities field
$form_filter                = dbFetchColumn('SELECT DISTINCT `severity` FROM `eventlog`' . $where);
$form_items['severities']   = generate_form_values('eventlog', $form_filter, 'severity');
$form['row'][0]['severity'] = [
  'type'      => 'multiselect',
  'name'      => 'Severities',
  'width'     => '100%',
  'div_class' => 'col-lg-1 col-md-2 col-sm-2',
  'subtext'   => TRUE,
  'value'     => $vars['severity'],
  'values'    => $form_items['severities']];

// Types field
$form_filter            = dbFetchColumn('SELECT DISTINCT `entity_type` FROM `eventlog` IGNORE INDEX (`type`)' . $where);
$form_items['types']    = generate_form_values('eventlog', $form_filter, 'type');
$form['row'][0]['type'] = [
  'type'      => 'multiselect',
  'name'      => 'Types',
  'width'     => '100%',
  'div_class' => 'col-lg-1 col-md-2 col-sm-2',
  'size'      => '15',
  'value'     => $vars['type'],
  'values'    => $form_items['types']];

// Datetime field
$form['row'][0]['timestamp'] = [
  'type'      => 'datetime',
  'div_class' => 'col-lg-5 col-md-7 col-sm-10',
  'presets'   => TRUE,
  'min'       => $timestamp_min,
  'max'       => $timestamp_max,
  'from'      => $vars['timestamp_from'],
  'to'        => $vars['timestamp_to']];

// search button
$form['row'][0]['search'] = [
  'type'      => 'submit',
  //'name'        => 'Search',
  //'icon'        => 'icon-search',
  'div_class' => 'col-lg-1 col-md-5 col-sm-2',
  //'grid'        => 1,
  'right'     => TRUE];

print_form($form);
unset($form, $form_items, $form_devices);

/// Pagination
$vars['pagination'] = TRUE;

print_events($vars);

register_html_title("Events");

// EOF
