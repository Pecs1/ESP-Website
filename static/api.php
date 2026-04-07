<?php
include('config.php');

try {
    $mode = $_GET['mode'] ?? 'log';

    if ($mode === 'buffer') {
        $lastId = (int)($_GET['last_id'] ?? 0);
        $stmt = $pdo->prepare("SELECT * FROM telemetry WHERE id > :last_id ORDER BY id ASC");
        $stmt->execute([':last_id' => $lastId]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } 
    else {
        $stmt = $pdo->query("SELECT id, lat, lng FROM telemetry ORDER BY id DESC LIMIT 50");
        echo json_encode(array_reverse($stmt->fetchAll(PDO::FETCH_ASSOC)));
    }
} catch (PDOException $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(["error" => $e->getMessage()]);
}
?>
