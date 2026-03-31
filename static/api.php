<?php
include('config.php');
$db_path = '/var/www/html/schema.db';

try {
    $db = new PDO("sqlite:$db_path");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->exec("CREATE TABLE IF NOT EXISTS telemetry (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    data_type TEXT DEFAULT 'gps',
    lat REAL,
    lng REAL,
    vel REAL,
    alt REAL,
    usat INTEGER,
    accu REAL,
    time TEXT,
    extra TEXT,
    server_time DATETIME DEFAULT CURRENT_TIMESTAMP
)");

    $mode = $_GET['mode'] ?? 'log';

    if ($mode === 'buffer') {
        $lastId = (int)($_GET['last_id'] ?? 0);
        // Table must be 'telemetry' to match ingest.php
        $stmt = $db->prepare("SELECT * FROM telemetry WHERE id > :last_id ORDER BY id ASC");
        $stmt->execute([':last_id' => $lastId]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } 
    else {
    // Ensure 'id' is included in the selection
    $stmt = $db->query("SELECT id, lat, lng FROM telemetry ORDER BY id DESC LIMIT 100");
    echo json_encode(array_reverse($stmt->fetchAll(PDO::FETCH_ASSOC)));
    }
} catch (PDOException $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(["error" => $e->getMessage()]);
}
