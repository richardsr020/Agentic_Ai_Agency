-- Add role to users
ALTER TABLE users ADD COLUMN role TEXT NOT NULL DEFAULT 'user';

-- Backfill existing rows
UPDATE users SET role = 'user' WHERE role IS NULL OR role = '';
