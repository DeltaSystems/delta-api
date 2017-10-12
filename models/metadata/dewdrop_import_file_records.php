<?php

return array(
    'titles'            => array(
        'singular' => 'Dewdrop Import File Record',
        'plural'   => 'Dewdrop Import File Records',
    ),
    'columns'           => array (
  'dewdrop_import_file_id' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'dewdrop_import_file_records',
    'COLUMN_NAME' => 'dewdrop_import_file_id',
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
    'IDENTITY' => false,
  ),
  'record_primary_key_value' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'dewdrop_import_file_records',
    'COLUMN_NAME' => 'record_primary_key_value',
    'COLUMN_POSITION' => 2,
    'DATA_TYPE' => 'int4',
    'GENERIC_TYPE' => 'integer',
    'DEFAULT' => NULL,
    'NULLABLE' => false,
    'LENGTH' => 4,
    'SCALE' => NULL,
    'PRECISION' => NULL,
    'UNSIGNED' => NULL,
    'PRIMARY' => true,
    'PRIMARY_POSITION' => 2,
    'IDENTITY' => false,
  ),
),
    'references'        => array (
  'dewdrop_import_file_id' => 
  array (
    'table' => 'dewdrop_import_files',
    'column' => 'dewdrop_import_file_id',
  ),
),
    'uniqueConstraints' => array (
),
);
