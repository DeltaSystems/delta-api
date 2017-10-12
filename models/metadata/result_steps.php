<?php

return array(
    'titles'            => array(
        'singular' => 'Result Step',
        'plural'   => 'Result Steps',
    ),
    'columns'           => array (
  'result_step_id' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'result_steps',
    'COLUMN_NAME' => 'result_step_id',
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
  'result_id' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'result_steps',
    'COLUMN_NAME' => 'result_id',
    'COLUMN_POSITION' => 2,
    'DATA_TYPE' => 'int4',
    'GENERIC_TYPE' => 'integer',
    'DEFAULT' => NULL,
    'NULLABLE' => false,
    'LENGTH' => 4,
    'SCALE' => NULL,
    'PRECISION' => NULL,
    'UNSIGNED' => NULL,
    'PRIMARY' => false,
    'PRIMARY_POSITION' => NULL,
    'IDENTITY' => false,
  ),
  'step_number' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'result_steps',
    'COLUMN_NAME' => 'step_number',
    'COLUMN_POSITION' => 3,
    'DATA_TYPE' => 'int4',
    'GENERIC_TYPE' => 'integer',
    'DEFAULT' => NULL,
    'NULLABLE' => false,
    'LENGTH' => 4,
    'SCALE' => NULL,
    'PRECISION' => NULL,
    'UNSIGNED' => NULL,
    'PRIMARY' => false,
    'PRIMARY_POSITION' => NULL,
    'IDENTITY' => false,
  ),
  'result_status_id' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'result_steps',
    'COLUMN_NAME' => 'result_status_id',
    'COLUMN_POSITION' => 4,
    'DATA_TYPE' => 'int4',
    'GENERIC_TYPE' => 'integer',
    'DEFAULT' => NULL,
    'NULLABLE' => false,
    'LENGTH' => 4,
    'SCALE' => NULL,
    'PRECISION' => NULL,
    'UNSIGNED' => NULL,
    'PRIMARY' => false,
    'PRIMARY_POSITION' => NULL,
    'IDENTITY' => false,
  ),
  'status_message' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'result_steps',
    'COLUMN_NAME' => 'status_message',
    'COLUMN_POSITION' => 5,
    'DATA_TYPE' => 'text',
    'GENERIC_TYPE' => 'clob',
    'DEFAULT' => NULL,
    'NULLABLE' => true,
    'LENGTH' => -1,
    'SCALE' => NULL,
    'PRECISION' => NULL,
    'UNSIGNED' => NULL,
    'PRIMARY' => false,
    'PRIMARY_POSITION' => NULL,
    'IDENTITY' => false,
  ),
  'result_output_type_id' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'result_steps',
    'COLUMN_NAME' => 'result_output_type_id',
    'COLUMN_POSITION' => 6,
    'DATA_TYPE' => 'int4',
    'GENERIC_TYPE' => 'integer',
    'DEFAULT' => NULL,
    'NULLABLE' => false,
    'LENGTH' => 4,
    'SCALE' => NULL,
    'PRECISION' => NULL,
    'UNSIGNED' => NULL,
    'PRIMARY' => false,
    'PRIMARY_POSITION' => NULL,
    'IDENTITY' => false,
  ),
  'output' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'result_steps',
    'COLUMN_NAME' => 'output',
    'COLUMN_POSITION' => 7,
    'DATA_TYPE' => 'text',
    'GENERIC_TYPE' => 'clob',
    'DEFAULT' => NULL,
    'NULLABLE' => true,
    'LENGTH' => -1,
    'SCALE' => NULL,
    'PRECISION' => NULL,
    'UNSIGNED' => NULL,
    'PRIMARY' => false,
    'PRIMARY_POSITION' => NULL,
    'IDENTITY' => false,
  ),
  'name' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'result_steps',
    'COLUMN_NAME' => 'name',
    'COLUMN_POSITION' => 8,
    'DATA_TYPE' => 'varchar',
    'GENERIC_TYPE' => 'text',
    'DEFAULT' => NULL,
    'NULLABLE' => false,
    'LENGTH' => '255',
    'SCALE' => NULL,
    'PRECISION' => NULL,
    'UNSIGNED' => NULL,
    'PRIMARY' => false,
    'PRIMARY_POSITION' => NULL,
    'IDENTITY' => false,
  ),
),
    'references'        => array (
  'result_id' => 
  array (
    'table' => 'results',
    'column' => 'result_id',
  ),
  'result_status_id' => 
  array (
    'table' => 'result_statuses',
    'column' => 'result_status_id',
  ),
  'result_output_type_id' => 
  array (
    'table' => 'result_output_types',
    'column' => 'result_output_type_id',
  ),
),
    'uniqueConstraints' => array (
),
);