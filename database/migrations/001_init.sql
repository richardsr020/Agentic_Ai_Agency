CREATE TABLE IF NOT EXISTS visitors (
  id TEXT PRIMARY KEY,
  language TEXT NULL,
  theme TEXT NULL,
  created_at TEXT NOT NULL,
  updated_at TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS ui_translations (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  namespace TEXT NOT NULL,
  translation_key TEXT NOT NULL,
  lang TEXT NOT NULL,
  text TEXT NOT NULL,
  updated_at TEXT NOT NULL,
  UNIQUE(namespace, translation_key, lang)
);

CREATE TABLE IF NOT EXISTS pages (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  slug TEXT NOT NULL UNIQUE,
  updated_at TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS page_translations (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  page_id INTEGER NOT NULL,
  lang TEXT NOT NULL,
  title TEXT NOT NULL,
  meta_description TEXT NOT NULL,
  body_html TEXT NOT NULL,
  updated_at TEXT NOT NULL,
  UNIQUE(page_id, lang),
  FOREIGN KEY(page_id) REFERENCES pages(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS contact_submissions (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  visitor_id TEXT NULL,
  name TEXT NOT NULL,
  email TEXT NOT NULL,
  company TEXT NULL,
  message TEXT NOT NULL,
  created_at TEXT NOT NULL,
  FOREIGN KEY(visitor_id) REFERENCES visitors(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS discovery_calls (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  visitor_id TEXT NULL,
  name TEXT NOT NULL,
  email TEXT NOT NULL,
  company TEXT NULL,
  preferred_date TEXT NULL,
  notes TEXT NULL,
  created_at TEXT NOT NULL,
  FOREIGN KEY(visitor_id) REFERENCES visitors(id) ON DELETE SET NULL
);

INSERT OR IGNORE INTO pages (id, slug, updated_at) VALUES
  (1, 'home', strftime('%Y-%m-%dT%H:%M:%fZ','now')),
  (2, 'solutions', strftime('%Y-%m-%dT%H:%M:%fZ','now')),
  (3, 'integration-levels', strftime('%Y-%m-%dT%H:%M:%fZ','now')),
  (4, 'how-it-works', strftime('%Y-%m-%dT%H:%M:%fZ','now')),
  (5, 'use-cases', strftime('%Y-%m-%dT%H:%M:%fZ','now')),
  (6, 'about', strftime('%Y-%m-%dT%H:%M:%fZ','now')),
  (7, 'contact', strftime('%Y-%m-%dT%H:%M:%fZ','now'));
