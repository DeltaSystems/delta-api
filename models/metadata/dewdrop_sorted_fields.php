<?php

return array(
    'titles'            => array(
        'singular' => 'Dewdrop Sorted Field',
        'plural'   => 'Dewdrop Sorted Fields',
    ),
    'columns'           => array (
  'component' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'dewdrop_sorted_fields',
    'COLUMN_NAME' => 'component',
    'COLUMN_POSITION' => 1,
    'DATA_TYPE' => 'varchar',
    'GENERIC_TYPE' => 'text',
    'DEFAULT' => NULL,
    'NULLABLE' => false,
    'LENGTH' => '128',
    'SCALE' => NULL,
    'PRECISION' => NULL,
    'UNSIGNED' => NULL,
    'PRIMARY' => true,
    'PRIMARY_POSITION' => 1,
    'IDENTITY' => false,
  ),
  'field_id' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'dewdrop_sorted_fields',
    'COLUMN_NAME' => 'field_id',
    'COLUMN_POSITION' => 2,
    'DATA_TYPE' => 'varchar',
    'GENERIC_TYPE' => 'text',
    'DEFAULT' => NULL,
    'NULLABLE' => false,
    'LENGTH' => '128',
    'SCALE' => NULL,
    'PRECISION' => NULL,
    'UNSIGNED' => NULL,
    'PRIMARY' => true,
    'PRIMARY_POSITION' => 2,
    'IDENTITY' => false,
  ),
  'dewdrop_field_group_id' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'dewdrop_sorted_fields',
    'COLUMN_NAME' => 'dewdrop_field_group_id',
    'COLUMN_POSITION' => 3,
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
  'sort_index' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'dewdrop_sorted_fields',
    'COLUMN_NAME' => 'sort_index',
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
),
    'references'        => array (
  'dewdrop_field_group_id' => 
  array (
    'table' => 'dewdrop_field_groups',
    'column' => 'dewdrop_field_group_id',
  ),
),
    'uniqueConstraints' => array (
),
);
