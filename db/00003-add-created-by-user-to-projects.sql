ALTER TABLE projects ADD created_by_user_id INTEGER REFERENCES users NOT NULL;
