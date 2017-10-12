<?php

return array(
    'titles'            => array(
        'singular' => 'Result Status',
        'plural'   => 'Result Statuseses',
    ),
    'columns'           => array (
  'result_status_id' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'result_statuses',
    'COLUMN_NAME' => 'result_status_id',
    'COLUMN_POSITION' => 1,
    'DATA_TYPE' => 'int4',
    'GENERIC_TYPE' => 'integer',
    'DEFAULT' => NULL,
    'NULLABLE' => false,
    'LENGTH' => 4,
    'SCALE' => NULL,
    'PRECISION' => NULL,
    'UNSIGNED' => NULL,
    'PRIMARY' => true,
    'PRIMARY_POSITION' => 1,
    'IDENTITY' => true,
  ),
  'name' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'result_statuses',
    'COLUMN_NAME' => 'name',
    'COLUMN_POSITION' => 2,
    'DATA_TYPE' => 'varchar',
    'GENERIC_TYPE' => 'text',
    'DEFAULT' => NULL,
    'NULLABLE' => false,
    'LENGTH' => '32',
    'SCALE' => NULL,
    'PRECISION' => NULL,
    'UNSIGNED' => NULL,
    'PRIMARY' => false,
    'PRIMARY_POSITION' => NULL,
    'IDENTITY' => false,
  ),
  'code' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'result_statuses',
    'COLUMN_NAME' => 'code',
    'COLUMN_POSITION' => 3,
    'DATA_TYPE' => 'varchar',
    'GENERIC_TYPE' => 'text',
    'DEFAULT' => NULL,
    'NULLABLE' => false,
    'LENGTH' => '32',
    'SCALE' => NULL,
    'PRECISION' => NULL,
    'UNSIGNED' => NULL,
    'PRIMARY' => false,
    'PRIMARY_POSITION' => NULL,
    'IDENTITY' => false,
  ),
  'slack_color' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'result_statuses',
    'COLUMN_NAME' => 'slack_color',
    'COLUMN_POSITION' => 4,
    'DATA_TYPE' => 'varchar',
    'GENERIC_TYPE' => 'text',
    'DEFAULT' => NULL,
    'NULLABLE' => false,
    'LENGTH' => '16',
    'SCALE' => NULL,
    'PRECISION' => NULL,
    'UNSIGNED' => NULL,
    'PRIMARY' => false,
    'PRIMARY_POSITION' => NULL,
    'IDENTITY' => false,
  ),
),
    'references'        => array (
),
    'uniqueConstraints' => array (
  17308 => 
  array (
    0 => 'code',
  ),
  17310 => 
  array (
    0 => 'name',
  ),
),
);
