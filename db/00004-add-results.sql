CREATE TABLE results (
    result_id SERIAL PRIMARY KEY,
    project_id INTEGER REFERENCES projects NOT NULL,
    script VARCHAR (255) NOT NULL,
    environment VARCHAR (255),
    run_by_user_id INTEGER REFERENCES users NOT NULL,
    date_run TIMESTAMP DEFAULT NOW() NOT NULL
);

CREATE INDEX result_project_id_idx ON results (project_id);
CREATE INDEX result_script_idx ON results (script);
CREATE INDEX result_environment_idx ON results (environment);
CREATE INDEX result_date_run_idx ON results (date_run);

CREATE TABLE result_output_types (
    result_output_type_id SERIAL PRIMARY KEY,
    name VARCHAR(32) UNIQUE NOT NULL
);

INSERT INTO result_output_types (name) VALUES
    ('text'),
    ('changeset');

CREATE TABLE result_statuses (
    result_status_id SERIAL PRIMARY KEY,
    name VARCHAR (32) UNIQUE NOT NULL,
    code VARCHAR (32) UNIQUE NOT NULL
);

INSERT INTO result_statuses (name, code) VALUES
    ('Success', 'success'),
    ('Failure', 'failure'),
    ('Invalid', 'invalid'),
    ('Warning', 'warning'),
    ('Skipped', 'skipped');

CREATE TABLE result_steps (
    result_step_id SERIAL PRIMARY KEY,
    result_id INTEGER REFERENCES results NOT NULL,
    step_number INTEGER NOT NULL,
    result_status_id INTEGER REFERENCES result_statuses NOT NULL,
    status_message TEXT,
    result_output_type_id INTEGER REFERENCES result_output_types NOT NULL,
    output TEXT
);

CREATE INDEX result_step_step_number_idx ON result_steps (step_number);

CREATE TABLE result_attribute_types (
    result_attribute_type_id SERIAL PRIMARY KEY,
    name VARCHAR (32) UNIQUE NOT NULL
);

INSERT INTO result_attribute_types (name) VALUES
    ('Text'),
    ('Date'),
    ('Git Commit');

CREATE TABLE result_attributes (
    result_attribute_id SERIAL PRIMARY KEY,
    result_id INTEGER REFERENCES results NOT NULL,
    attribute_number INTEGER NOT NULL,
    result_attribute_type_id INTEGER REFERENCES result_attribute_types NOT NULL,
    value TEXT
);

CREATE INDEX result_attribute_attribute_number_idx ON result_attributes (attribute_number);
