ALTER TABLE environments DROP resources;

CREATE TABLE environment_resources (
    environment_resource_id SERIAL PRIMARY KEY,
    environment_id INTEGER REFERENCES environments NOT NULL,
    name VARCHAR (128) NOT NULL,
    encrypted_key TEXT NOT NULL,
    encrypted_contents TEXT NOT NULL,
    date_created TIMESTAMP DEFAULT NOW() NOT NULL
);
