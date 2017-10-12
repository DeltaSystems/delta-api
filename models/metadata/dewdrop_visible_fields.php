<?php

return array(
    'titles'            => array(
        'singular' => 'Dewdrop Visible Field',
        'plural'   => 'Dewdrop Visible Fields',
    ),
    'columns'           => array (
  'component' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'dewdrop_visible_fields',
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
    'TABLE_NAME' => 'dewdrop_visible_fields',
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
),
    'references'        => array (
),
    'uniqueConstraints' => array (
),
);
