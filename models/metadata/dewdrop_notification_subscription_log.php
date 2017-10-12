<?php

return array(
    'titles'            => array(
        'singular' => 'Dewdrop Notification Subscription Log',
        'plural'   => 'Dewdrop Notification Subscription Logs',
    ),
    'columns'           => array (
  'dewdrop_notification_subscription_id' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'dewdrop_notification_subscription_log',
    'COLUMN_NAME' => 'dewdrop_notification_subscription_id',
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
  'dewdrop_mail_log_id' => 
  array (
    'SCHEMA_NAME' => 'public',
    'TABLE_NAME' => 'dewdrop_notification_subscription_log',
    'COLUMN_NAME' => 'dewdrop_mail_log_id',
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
  'dewdrop_notification_subscription_id' => 
  array (
    'table' => 'dewdrop_notification_subscriptions',
    'column' => 'dewdrop_notification_subscription_id',
  ),
  'dewdrop_mail_log_id' => 
  array (
    'table' => 'dewdrop_mail_log',
    'column' => 'dewdrop_mail_log_id',
  ),
),
    'uniqueConstraints' => array (
),
);
