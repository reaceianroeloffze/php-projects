-- ----------------------------------------
# SQL Queries for journal database creation
-- ----------------------------------------

# Create journal database
CREATE DATABASE IF NOT EXISTS `journal`;

# Use journal database
USE `journal`;

# Create a table to store journal entries
CREATE TABLE IF NOT EXISTS `entries`
(
    `id`         int  NOT NULL AUTO_INCREMENT, # Autofilled
    `title`      varchar(1100) DEFAULT NULL,   # Optional
    `body`       text NOT NULL,                # Required
    `created_on` date NOT NULL,                # Based on the current date
    PRIMARY KEY (`id`)
) ENGINE = InnoDB # Default storage engine
  DEFAULT CHARSET = utf8mb4 # recommended character set
  COLLATE = utf8mb4_0900_ai_ci
# recommended collation;

# Create a table index
# CREATE INDEX `journal_entry_date` ON `entries` (`created_on`);