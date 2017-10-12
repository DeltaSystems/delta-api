CREATE TABLE environments (
    environment_id SERIAL PRIMARY KEY,
    project_id INTEGER REFERENCES projects NOT NULL,
    date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    date_last_used TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    created_by_user_id INTEGER REFERENCES users,
    name VARCHAR (128) NOT NULL,
    public_key_pem TEXT NOT NULL,
    resources TEXT,
    UNIQUE (project_id, name)
);
