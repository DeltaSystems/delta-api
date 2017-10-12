<?php

return array(
    'titles'            => array(
        'singular' => 'Dewdrop Activity Log',
        'plural'   => 'Dewdrop Activity Logs',
    ),
    'columns'           => array (
  'dewdrop_activity_log_id' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'dewdrop_activity_log',
    'COLUMN_NAME' => 'dewdrop_activity_log_id',
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
  'message' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'dewdrop_activity_log',
    'COLUMN_NAME' => 'message',
    'COLUMN_POSITION' => 2,
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
  'date_created' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'dewdrop_activity_log',
    'COLUMN_NAME' => 'date_created',
    'COLUMN_POSITION' => 3,
    'DATA_TYPE' => 'timestamp',
    'GENERIC_TYPE' => 'timestamp',
    'DEFAULT' => NULL,
    'NULLABLE' => false,
    'LENGTH' => 8,
    'SCALE' => NULL,
    'PRECISION' => NULL,
    'UNSIGNED' => NULL,
    'PRIMARY' => false,
    'PRIMARY_POSITION' => NULL,
    'IDENTITY' => false,
  ),
  'dewdrop_activity_log_user_information_id' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'dewdrop_activity_log',
    'COLUMN_NAME' => 'dewdrop_activity_log_user_information_id',
    'COLUMN_POSITION' => 4,
    'DATA_TYPE' => 'int4',
    'GENERIC_TYPE' => 'integer',
    'DEFAULT' => NULL,
    'NULLABLE' => true,
    'LENGTH' => 4,
    'SCALE' => NULL,
    'PRECISION' => NULL,
    'UNSIGNED' => NULL,
    'PRIMARY' => false,
    'PRIMARY_POSITION' => NULL,
    'IDENTITY' => false,
  ),
),
    'references'        => array (
  'dewdrop_activity_log_user_information_id' => 
  array (
    'table' => 'dewdrop_activity_log_user_information',
    'column' => 'dewdrop_activity_log_user_information_id',
  ),
),
    'uniqueConstraints' => array (
),
);
