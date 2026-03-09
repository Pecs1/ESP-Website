<?php
$config = include('config.php');
$expected_key = $config['api_key'];

// 1. Security Check
// Use $_SERVER instead of getallheaders() as it's more reliable across different firewalls
$received_key = $_SERVER['HTTP_X_API_KEY'] ?? '';

if ($received_key !== $expected_key) {
    header('HTTP/1.1 403 Forbidden');
}

if ($received_key == $expected_key) {
    header('HTTP/1.1 200 OK');
}

$rawPayload = trim(file_get_contents('php://input'));
if (empty($rawPayload)) die("No data");

// Split the payload into individual rows using the semicolon
$rows = explode(';', $rawPayload);
$newPoints = [];

foreach ($rows as $row) {
    $parts = explode(',', trim($row));
    if (count($parts) >= 7) {
        $newPoints[] = [
            "lat"    => (float)$parts[0],
            "lng"    => (float)$parts[1],
            "vel"    => (float)$parts[2],
            "alt"    => (float)$parts[3],
            "usat"   => (int)$parts[4],
            "accu"   => (float)$parts[5],
            "time"   => htmlspecialchars($parts[6]),
        ];
    }
}

if (empty($newPoints)) die("No valid points found");

// Since the ESP32 already buffers 6 points, we can just overwrite data.json 
// with the latest 6-point batch to keep the dashboard current.
$dataFile = 'data.json';
if (file_put_contents($dataFile, json_encode($newPoints))) {
    echo "Successfully updated data.json with " . count($newPoints) . " points.";
} else {
    header('HTTP/1.1 500 Internal Server Error');
    echo "File write failed. Check permissions.";
}

// Open file for reading and writing
$fp = fopen($bufferFile, "c+"); 

// Acquire an exclusive lock (Wait if another request is using it)
if (flock($fp, LOCK_EX)) {
    // Read current content
    $content = stream_get_contents($fp);
    $currentBuffer = !empty($content) ? json_decode($content, true) : [];
    
    // Add new data
    $currentBuffer[] = $newRow;
    
    if (count($currentBuffer) >= 6) {
        // Push to main data file and clear buffer
        file_put_contents('data.json', json_encode($currentBuffer));
        ftruncate($fp, 0); 
        rewind($fp);
        fwrite($fp, json_encode([]));
        echo "Batch pushed to data.json";
    } else {
        // Save back to buffer
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($currentBuffer));
        echo "Point stored in buffer (" . count($currentBuffer) . "/6)";
    }
    
    fflush($fp);            // flush output before releasing lock
    flock($fp, LOCK_UN);    // release the lock
} else {
    echo "Could not lock file";
}

fclose($fp);
?>
