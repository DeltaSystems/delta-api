<?php

return array(
    'titles'            => array(
        'singular' => 'Dewdrop Notification Subscription Recipient',
        'plural'   => 'Dewdrop Notification Subscription Recipients',
    ),
    'columns'           => array (
  'dewdrop_notification_subscription_recipient_id' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'dewdrop_notification_subscription_recipients',
    'COLUMN_NAME' => 'dewdrop_notification_subscription_recipient_id',
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
  'dewdrop_notification_subscription_id' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'dewdrop_notification_subscription_recipients',
    'COLUMN_NAME' => 'dewdrop_notification_subscription_id',
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
  'email_address' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'dewdrop_notification_subscription_recipients',
    'COLUMN_NAME' => 'email_address',
    'COLUMN_POSITION' => 3,
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
  'dewdrop_notification_subscription_id' => 
  array (
    'table' => 'dewdrop_notification_subscriptions',
    'column' => 'dewdrop_notification_subscription_id',
  ),
),
    'uniqueConstraints' => array (
),
);
