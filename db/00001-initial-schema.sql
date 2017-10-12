CREATE TABLE users (
    user_id SERIAL PRIMARY KEY,
    email_address VARCHAR(255) UNIQUE NOT NULL,
    api_key VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    date_created TIMESTAMP DEFAULT NOW() NOT NULL
);

CREATE TABLE authorized_email_domains (
    authorized_email_domain_id SERIAL PRIMARY KEY,
    domain_name VARCHAR(128) UNIQUE NOT NULL
);

INSERT INTO authorized_email_domains (domain_name) VALUES
    ('deltasys.com'),
    ('voltagekc.com');

CREATE TABLE sign_up_requests (
    sign_up_request_id SERIAL PRIMARY KEY,
    email_address VARCHAR(255) NOT NULL,
    authorized_email_domain_id INTEGER REFERENCES authorized_email_domains,
    authorized_by_user_id INTEGER REFERENCES users,
    authorization_code VARCHAR(32) NOT NULL,
    date_created TIMESTAMP DEFAULT NOW() NOT NULL
);

CREATE TABLE projects (
    project_id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    api_id VARCHAR(255) NOT NULL,
    date_created TIMESTAMP DEFAULT NOW() NOT NULL
);

