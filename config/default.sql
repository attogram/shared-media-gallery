
-----------------------------------------------------------------------
-- site
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [site];

CREATE TABLE [site]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [title] VARCHAR(255),
    [about] MEDIUMTEXT,
    [header] MEDIUMTEXT,
    [footer] MEDIUMTEXT,
    [use_cdn] INTEGER DEFAULT 0,
    [curation] INTEGER DEFAULT 0,
    [created_at] TIMESTAMP,
    [updated_at] TIMESTAMP,
    UNIQUE ([id])
);
