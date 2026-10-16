CREATE DATABASE IF NOT EXISTS dark_sky CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dark_sky;

CREATE TABLE sites (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name                VARCHAR(200)    NOT NULL,
    country             VARCHAR(100)    NOT NULL,
    site_type           VARCHAR(50)     NOT NULL,
    designation_year    YEAR            NOT NULL,
    sky_quality_index   DECIMAL(4,2),
    lat                 DECIMAL(9,6)    NOT NULL,
    lng                 DECIMAL(9,6)    NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE observations (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    site_id         INT UNSIGNED    NOT NULL,
    object_name     VARCHAR(150)    NOT NULL,
    object_type     VARCHAR(50)     NOT NULL,
    observation_date DATE,
    notes           TEXT,
    FOREIGN KEY (site_id) REFERENCES sites(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS contact_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(254) NOT NULL,
    subject VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
