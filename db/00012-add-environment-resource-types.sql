CREATE TABLE environment_resource_types (
    environment_resource_type_id SERIAL PRIMARY KEY,
    name VARCHAR (128) UNIQUE NOT NULL,
    title VARCHAR (128) NOT NULL
);

INSERT INTO environment_resource_types (name, title) VALUES
    ('host', 'Host'),
    ('database', 'Database'),
    ('browser-url', 'Browser URL'),
    ('sftp-username', 'SFTP Username'),
    ('sftp-password', 'SFTP Password'),
    ('http-auth-username', 'HTTP Authentication Username'),
    ('http-auth-password', 'HTTP Authentication Password'),
    ('dev-environment-flag', 'Is Dev Environment?');

ALTER TABLE environment_resources ADD environment_resource_type_id INTEGER REFERENCES environment_resource_types NOT NULL;