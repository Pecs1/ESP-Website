<?php
include('config.php');
$expected_key = $config['api_key'];

// Security Check
$received_key = $_SERVER['HTTP_X_API_KEY'] ?? '';
if ($received_key !== $config['api_key']) {
    header('HTTP/1.1 403 Forbidden');
    exit("Unauthorized");
}

if ($received_key == $expected_key) {
    header('HTTP/1.1 200 OK');
}

try {
    $rawPayload = trim(file_get_contents('php://input'));
    if (empty($rawPayload)) die("No data");

    $rows = explode(';', $rawPayload);
    $stmt = $pdo->prepare("INSERT INTO telemetry (lat, lng, vel, alt, usat, accu, esp_time, extra, server_time) 
                      VALUES (:lat, :lng, :vel, :alt, :usat, :accu, :esp_time, :extra, :server_time)");

    $pdo->beginTransaction();

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
                ':esp_time' => $formattedTime,
                ':extra'       => null,
                ':server_time' => date('H:i:s')
            ]);
        }
    }
    $pdo->commit();
    echo "Success";

} catch (PDOException $e) {
    // If the transaction fails, roll it back to prevent partial inserts
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    header('HTTP/1.1 500 Internal Server Error');
    echo "Database error: " . $e->getMessage();
}
?>
