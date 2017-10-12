ALTER TABLE results ADD result_status_id INTEGER REFERENCES result_statuses;

UPDATE results SET result_status_id = (
    SELECT result_status_id FROM result_statuses WHERE code = 'success'
) WHERE result_id NOT IN (
    SELECT result_id FROM result_steps WHERE result_status_id = (
        SELECT result_status_id FROM result_statuses WHERE code = 'failure'
    )
);

UPDATE results SET result_status_id = (
    SELECT result_status_id FROM result_statuses WHERE code = 'failure'
) WHERE result_id IN (
    SELECT result_id FROM result_steps WHERE result_status_id = (
        SELECT result_status_id FROM result_statuses WHERE code = 'failure'
    )
);

ALTER TABLE results ALTER result_status_id SET NOT NULL;
