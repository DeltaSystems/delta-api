<?php

return array(
    'titles'            => array(
        'singular' => 'Dewdrop Test Fruit',
        'plural'   => 'Dewdrop Test Fruits',
    ),
    'columns'           => array (
  'dewdrop_test_fruit_id' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'dewdrop_test_fruits',
    'COLUMN_NAME' => 'dewdrop_test_fruit_id',
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
    'TABLE_NAME' => 'dewdrop_test_fruits',
    'COLUMN_NAME' => 'name',
    'COLUMN_POSITION' => 2,
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
  'is_delicious' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'dewdrop_test_fruits',
    'COLUMN_NAME' => 'is_delicious',
    'COLUMN_POSITION' => 3,
    'DATA_TYPE' => 'bool',
    'GENERIC_TYPE' => 'boolean',
    'DEFAULT' => 1,
    'NULLABLE' => false,
    'LENGTH' => 1,
    'SCALE' => NULL,
    'PRECISION' => NULL,
    'UNSIGNED' => NULL,
    'PRIMARY' => false,
    'PRIMARY_POSITION' => NULL,
    'IDENTITY' => false,
  ),
  'level_of_deliciousness' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'dewdrop_test_fruits',
    'COLUMN_NAME' => 'level_of_deliciousness',
    'COLUMN_POSITION' => 4,
    'DATA_TYPE' => 'int4',
    'GENERIC_TYPE' => 'integer',
    'DEFAULT' => '0',
    'NULLABLE' => false,
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
),
    'uniqueConstraints' => array (
  17284 => 
  array (
    0 => 'name',
  ),
),
);
