<?php

return array(
    'titles'            => array(
        'singular' => 'Authorized Email Domain',
        'plural'   => 'Authorized Email Domains',
    ),
    'columns'           => array (
  'authorized_email_domain_id' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'authorized_email_domains',
    'COLUMN_NAME' => 'authorized_email_domain_id',
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
  'domain_name' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'authorized_email_domains',
    'COLUMN_NAME' => 'domain_name',
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
),
    'references'        => array (
),
    'uniqueConstraints' => array (
  17246 => 
  array (
    0 => 'domain_name',
  ),
),
);
