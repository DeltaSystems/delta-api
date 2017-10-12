<?php

return array(
    'titles'            => array(
        'singular' => 'Result Attribute Type',
        'plural'   => 'Result Attribute Types',
    ),
    'columns'           => array (
  'result_attribute_type_id' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'result_attribute_types',
    'COLUMN_NAME' => 'result_attribute_type_id',
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
    'TABLE_NAME' => 'result_attribute_types',
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
),
    'references'        => array (
),
    'uniqueConstraints' => array (
  17298 => 
  array (
    0 => 'name',
  ),
),
);
