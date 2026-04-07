<?php
// Note that the key must match what you set in the ESP32 board
$config['api_key'] = "your_key/API";

$db_file = __DIR__ . '/telemetry_db.sqlite';
$schema_file = __DIR__ . '/schema.db';

try {
    $pdo = new PDO("sqlite:" . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Check if the telemetry table already exists
    $check = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='telemetry'");
    
    if (!$check->fetch() && file_exists($schema_file)) {
        $sql = file_get_contents($schema_file);
        $pdo->exec($sql);
    }

} catch (PDOException $e) {
    // In production, log this instead of using die()
    die("Connection failed: " . $e->getMessage());
}
?>
