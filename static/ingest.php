<?php
include('config.php');
$expected_key = $config['api_key'];
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
    
    // Security Check
    $received_key = $_SERVER['HTTP_X_API_KEY'] ?? '';
    if ($received_key !== $config['api_key']) {
        header('HTTP/1.1 403 Forbidden');
        exit("Unauthorized");
    }

    if ($received_key == $expected_key) {
    header('HTTP/1.1 200 OK');
    }

    $rawPayload = trim(file_get_contents('php://input'));
    if (empty($rawPayload)) die("No data");

    $rows = explode(';', $rawPayload);
    $stmt = $db->prepare("INSERT INTO telemetry (lat, lng, vel, alt, usat, accu, time, extra, server_time) 
                      VALUES (:lat, :lng, :vel, :alt, :usat, :accu, :time, :extra, :server_time)");

    $db->beginTransaction();

    foreach ($rows as $row) {
        $parts = explode(',', trim($row));
        if (count($parts) >= 7) {
            // Fix the time formatting
            $rawTime = trim($parts[6]);
            $timeParts = explode(':', $rawTime);
            
            if (count($timeParts) === 3) {
                $formattedTime = sprintf('%02d:%02d:%02d', $timeParts[0], $timeParts[1], $timeParts[2]);
            } else {
                $formattedTime = htmlspecialchars($rawTime);
            }

            $stmt->execute([
                ':lat'  => (float)$parts[0],
                ':lng'  => (float)$parts[1],
                ':vel'  => (float)$parts[2],
                ':alt'  => (float)$parts[3],
                ':usat' => (int)$parts[4],
                ':accu' => (float)$parts[5],
                ':time' => $formattedTime,
                ':extra'       => null,
                ':server_time' => date('Y-m-d H:i:s')
            ]);
        }
    }
    $db->commit();
    echo "Success";

} catch (PDOException $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo "Database error: " . $e->getMessage();
}
?>
