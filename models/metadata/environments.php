<?php

return array(
    'titles'            => array(
        'singular' => 'Environment',
        'plural'   => 'Environments',
    ),
    'columns'           => array (
  'environment_id' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'environments',
    'COLUMN_NAME' => 'environment_id',
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
  'project_id' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'environments',
    'COLUMN_NAME' => 'project_id',
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
  'date_created' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'environments',
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
  'created_by_user_id' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'environments',
    'COLUMN_NAME' => 'created_by_user_id',
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
  'name' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'environments',
    'COLUMN_NAME' => 'name',
    'COLUMN_POSITION' => 5,
    'DATA_TYPE' => 'varchar',
    'GENERIC_TYPE' => 'text',
    'DEFAULT' => NULL,
    'NULLABLE' => false,
    'LENGTH' => '128',
    'SCALE' => NULL,
    'PRECISION' => NULL,
    'UNSIGNED' => NULL,
    'PRIMARY' => false,
    'PRIMARY_POSITION' => NULL,
    'IDENTITY' => false,
  ),
  'public_key_pem' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'environments',
    'COLUMN_NAME' => 'public_key_pem',
    'COLUMN_POSITION' => 6,
    'DATA_TYPE' => 'text',
    'GENERIC_TYPE' => 'clob',
    'DEFAULT' => NULL,
    'NULLABLE' => false,
    'LENGTH' => -1,
    'SCALE' => NULL,
    'PRECISION' => NULL,
    'UNSIGNED' => NULL,
    'PRIMARY' => false,
    'PRIMARY_POSITION' => NULL,
    'IDENTITY' => false,
  ),
  'managed_by_puppet' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'environments',
    'COLUMN_NAME' => 'managed_by_puppet',
    'COLUMN_POSITION' => 7,
    'DATA_TYPE' => 'bool',
    'GENERIC_TYPE' => 'boolean',
    'DEFAULT' => 0,
    'NULLABLE' => false,
    'LENGTH' => 1,
    'SCALE' => NULL,
    'PRECISION' => NULL,
    'UNSIGNED' => NULL,
    'PRIMARY' => false,
    'PRIMARY_POSITION' => NULL,
    'IDENTITY' => false,
  ),
),
    'references'        => array (
  'project_id' => 
  array (
    'table' => 'projects',
    'column' => 'project_id',
  ),
  'created_by_user_id' => 
  array (
    'table' => 'users',
    'column' => 'user_id',
  ),
),
    'uniqueConstraints' => array (
  17294 => 
  array (
    0 => 'name',
    1 => 'project_id',
  ),
),
);
