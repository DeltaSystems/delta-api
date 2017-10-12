<?php

return array(
    'titles'            => array(
        'singular' => 'Result Attribute',
        'plural'   => 'Result Attributes',
    ),
    'columns'           => array (
  'result_attribute_id' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'result_attributes',
    'COLUMN_NAME' => 'result_attribute_id',
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
    'TABLE_NAME' => 'result_attributes',
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
  'attribute_number' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'result_attributes',
    'COLUMN_NAME' => 'attribute_number',
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
  'result_attribute_type_id' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'result_attributes',
    'COLUMN_NAME' => 'result_attribute_type_id',
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
  'value' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'result_attributes',
    'COLUMN_NAME' => 'value',
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
),
    'references'        => array (
  'result_id' => 
  array (
    'table' => 'results',
    'column' => 'result_id',
  ),
  'result_attribute_type_id' => 
  array (
    'table' => 'result_attribute_types',
    'column' => 'result_attribute_type_id',
  ),
),
    'uniqueConstraints' => array (
),
);
