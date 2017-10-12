ALTER TABLE result_statuses ADD slack_color VARCHAR(16);

UPDATE result_statuses SET slack_color = 'good' WHERE code = 'success';
UPDATE result_statuses SET slack_color = 'danger' WHERE code = 'failure';
UPDATE result_statuses SET slack_color = 'warning' WHERE code = 'warning';
UPDATE result_statuses SET slack_color = '#666666' WHERE code = 'invalid';
UPDATE result_statuses SET slack_color = '#439FE0' WHERE code = 'skipped';

ALTER TABLE result_statuses ALTER slack_color SET NOT NULL;
