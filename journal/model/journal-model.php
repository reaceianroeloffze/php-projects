<?php

    /**
     * Display a set amount of journal entries per webpage.
     *
     * @param int $entriesPerPage <p>
     * A number of <p>entries to display per page
     * </p>
     * @var PDO $db <p>
     * Database connection
     * </p>
     * @var array $entries <p>
     * Associative array of retrieved journal entries
     * </p>
     * @var PDOStatement $stmt <p>
     * Prepared SQL statement
     * </p>
     * @var string $sql <p>
     * Stored SQL query
     * </p>
     * @param int $offset <p>
     * The offset for entries to display based on the page number
     * </p>
     * @return array <p>
     * The associative array of journal entries stored in $entires
     * </p>
     * */
    function displayJournalEntries(int $entriesPerPage, int $offset): array
    {
        $db = connectToDatabase();
        $sql = <<<'SQL'
            SELECT
                title,
                body,
                created_on
            FROM 
                entries
            ORDER BY 
                created_on DESC,
                id DESC
            LIMIT
                :entriesPerPage 
            OFFSET
                :offset;
            SQL;
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':entriesPerPage', $entriesPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $entries;
    }

    /**
     * Insert a new journal entry into the database.
     *
     * @param string $title <p>
     * The title of the journal entry
     * </p>
     * @param string $created_on <p>
     * The date the journal entry was created
     * </p>
     * @param string $body <p>
     * The body content of the journal entry
     * </p>
     * @var PDO $db <p>
     * Database connection
     * </p>
     * @var PDOStatement $stmt <p>
     * Prepared statement
     * </p>
     * @var string $sql <p>
     * SQL query
     * </p>
     * @var int $newRowCreated <p>
     * New row added to the database
     * </p>
     * @return bool <p>
     * 1 if the insertion was successful, 0 otherwise
     * </p>
     * */
    function insertNewJournalEntry(string $title, string $created_on, string $body): bool
    {
        $db = connectToDatabase();
        $sql = <<<'SQL'
            INSERT INTO 
                entries (title, body, created_on) 
            VALUES 
                (:title, :body, :created_on);
            SQL;
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':body', $body, PDO::PARAM_STR);
        $stmt->bindValue(':created_on', $created_on, PDO::PARAM_STR);
        $stmt->execute();
        $newRowCreated = $stmt->rowCount();
        $stmt->closeCursor();
        return $newRowCreated;
    }

    /**
     * Count all entries in the journal database.
     * 
     * @return int <p>
     * The total number of journal entries in the database
     * </p>
     * */
    function countAllJournalEntries(): int
    {
        $db = connectToDatabase();
        $sql = <<<'SQL'
            SELECT 
                COUNT(*) AS entryCount 
            FROM 
                entries;
            SQL;
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $entryCount = (int) $stmt->fetchColumn();
        $stmt->closeCursor();
        return $entryCount;
    }
