<?php
declare(strict_types=1);

/**
 * Self-initializing Corporate Persistence Layer (SQLite3 with automatic JSON file fallback)
 * Stores corporate/dealer inquiries and career applications with verifiable reference numbers.
 */

function get_storage_dir(): string
{
    $dir = dirname(__DIR__) . '/storage';
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    return $dir;
}

function get_sqlite_connection(): ?SQLite3
{
    if (!class_exists('SQLite3')) {
        return null;
    }
    try {
        $dbPath = get_storage_dir() . '/softrings.sqlite';
        $db = new SQLite3($dbPath);
        $db->busyTimeout(3000);
        $db->exec('
            CREATE TABLE IF NOT EXISTS inquiries (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                ref_code TEXT UNIQUE NOT NULL,
                inquiry_type TEXT NOT NULL,
                full_name TEXT NOT NULL,
                company_name TEXT,
                phone TEXT NOT NULL,
                email TEXT NOT NULL,
                preferred_branch TEXT,
                message TEXT NOT NULL,
                status TEXT NOT NULL DEFAULT "Received — Assigned to Corporate Desk",
                created_at TEXT NOT NULL
            );
            CREATE TABLE IF NOT EXISTS applications (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                ref_code TEXT UNIQUE NOT NULL,
                job_code TEXT NOT NULL,
                job_title TEXT NOT NULL,
                full_name TEXT NOT NULL,
                phone TEXT NOT NULL,
                email TEXT NOT NULL,
                district TEXT NOT NULL,
                experience_years TEXT NOT NULL,
                summary TEXT NOT NULL,
                status TEXT NOT NULL DEFAULT "Received — Under HR Review (Homagama / Maharagama)",
                created_at TEXT NOT NULL
            );
        ');
        return $db;
    } catch (Throwable $e) {
        return null;
    }
}

function save_corporate_inquiry(array $payload): array
{
    $ref = 'SFT-' . date('Y') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
    $createdAt = date('Y-m-d H:i:s') . ' SLST';
    $status = 'Received — Routed to Softrings Corporate & Dealer Desk';

    $db = get_sqlite_connection();
    if ($db !== null) {
        $stmt = $db->prepare('
            INSERT INTO inquiries (ref_code, inquiry_type, full_name, company_name, phone, email, preferred_branch, message, status, created_at)
            VALUES (:ref, :type, :name, :company, :phone, :email, :branch, :msg, :status, :created)
        ');
        $stmt->bindValue(':ref', $ref, SQLITE3_TEXT);
        $stmt->bindValue(':type', $payload['inquiry_type'], SQLITE3_TEXT);
        $stmt->bindValue(':name', $payload['full_name'], SQLITE3_TEXT);
        $stmt->bindValue(':company', $payload['company_name'] ?? '', SQLITE3_TEXT);
        $stmt->bindValue(':phone', $payload['phone'], SQLITE3_TEXT);
        $stmt->bindValue(':email', $payload['email'], SQLITE3_TEXT);
        $stmt->bindValue(':branch', $payload['preferred_branch'] ?? 'Colombo Corporate Office (Maharagama)', SQLITE3_TEXT);
        $stmt->bindValue(':msg', $payload['message'], SQLITE3_TEXT);
        $stmt->bindValue(':status', $status, SQLITE3_TEXT);
        $stmt->bindValue(':created', $createdAt, SQLITE3_TEXT);
        $stmt->execute();
        $db->close();
    } else {
        $jsonFile = get_storage_dir() . '/records.json';
        $all = file_exists($jsonFile) ? (json_decode((string) file_get_contents($jsonFile), true) ?: []) : [];
        $all[$ref] = array_merge($payload, [
            'record_kind' => 'Corporate Inquiry',
            'ref_code' => $ref,
            'status' => $status,
            'created_at' => $createdAt,
        ]);
        file_put_contents($jsonFile, json_encode($all, JSON_PRETTY_PRINT));
    }

    return [
        'ref_code' => $ref,
        'status' => $status,
        'created_at' => $createdAt,
    ];
}

function save_career_application(array $payload): array
{
    $ref = 'HR-SFT-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
    $createdAt = date('Y-m-d H:i:s') . ' SLST';
    $status = 'Received — Under Review by Human Resources Department';

    $db = get_sqlite_connection();
    if ($db !== null) {
        $stmt = $db->prepare('
            INSERT INTO applications (ref_code, job_code, job_title, full_name, phone, email, district, experience_years, summary, status, created_at)
            VALUES (:ref, :jcode, :jtitle, :name, :phone, :email, :district, :exp, :summary, :status, :created)
        ');
        $stmt->bindValue(':ref', $ref, SQLITE3_TEXT);
        $stmt->bindValue(':jcode', $payload['job_code'], SQLITE3_TEXT);
        $stmt->bindValue(':jtitle', $payload['job_title'], SQLITE3_TEXT);
        $stmt->bindValue(':name', $payload['full_name'], SQLITE3_TEXT);
        $stmt->bindValue(':phone', $payload['phone'], SQLITE3_TEXT);
        $stmt->bindValue(':email', $payload['email'], SQLITE3_TEXT);
        $stmt->bindValue(':district', $payload['district'], SQLITE3_TEXT);
        $stmt->bindValue(':exp', $payload['experience_years'], SQLITE3_TEXT);
        $stmt->bindValue(':summary', $payload['summary'], SQLITE3_TEXT);
        $stmt->bindValue(':status', $status, SQLITE3_TEXT);
        $stmt->bindValue(':created', $createdAt, SQLITE3_TEXT);
        $stmt->execute();
        $db->close();
    } else {
        $jsonFile = get_storage_dir() . '/records.json';
        $all = file_exists($jsonFile) ? (json_decode((string) file_get_contents($jsonFile), true) ?: []) : [];
        $all[$ref] = array_merge($payload, [
            'record_kind' => 'Career Application',
            'ref_code' => $ref,
            'status' => $status,
            'created_at' => $createdAt,
        ]);
        file_put_contents($jsonFile, json_encode($all, JSON_PRETTY_PRINT));
    }

    return [
        'ref_code' => $ref,
        'status' => $status,
        'created_at' => $createdAt,
    ];
}

function find_reference_record(string $refCode): ?array
{
    $ref = strtoupper(trim($refCode));
    if ($ref === '') {
        return null;
    }

    $db = get_sqlite_connection();
    if ($db !== null) {
        $stmt = $db->prepare('SELECT ref_code, inquiry_type AS subject, full_name, preferred_branch AS branch, status, created_at FROM inquiries WHERE ref_code = :ref LIMIT 1');
        $stmt->bindValue(':ref', $ref, SQLITE3_TEXT);
        $res = $stmt->execute();
        $row = $res ? $res->fetchArray(SQLITE3_ASSOC) : false;
        if (is_array($row)) {
            $db->close();
            $row['record_kind'] = 'Corporate / Dealer Inquiry';
            return $row;
        }

        $stmt2 = $db->prepare('SELECT ref_code, job_title AS subject, full_name, district AS branch, status, created_at FROM applications WHERE ref_code = :ref LIMIT 1');
        $stmt2->bindValue(':ref', $ref, SQLITE3_TEXT);
        $res2 = $stmt2->execute();
        $row2 = $res2 ? $res2->fetchArray(SQLITE3_ASSOC) : false;
        $db->close();
        if (is_array($row2)) {
            $row2['record_kind'] = 'Career Application';
            return $row2;
        }
    }

    $jsonFile = get_storage_dir() . '/records.json';
    if (file_exists($jsonFile)) {
        $all = json_decode((string) file_get_contents($jsonFile), true) ?: [];
        if (isset($all[$ref])) {
            return $all[$ref];
        }
    }

    return null;
}
