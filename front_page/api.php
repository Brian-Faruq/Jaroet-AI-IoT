<?php
header('Content-Type: application/json');

// IP ESP32 Kamu
$ESP32_IP = "http://10.70.70.35";

function sendESP32($endpoint) {
    global $ESP32_IP;
    $url = $ESP32_IP . $endpoint;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ($httpCode == 200);
}

// Handler Saklar Direct (Toggle)
if (isset($_GET['action']) && isset($_GET['device'])) {
    $device = $_GET['device'];
    $action = $_GET['action'];
    
    $success = sendESP32("/" . $device . "/" . $action);
    
    echo json_encode([
        "success" => $success,
        "message" => $success ? "Perintah $action untuk $device berhasil!" : "Gagal terhubung ke ESP32."
    ]);
    exit;
}

// Handler Chatbot NLP
$inputData = json_decode(file_get_contents('php://input'), true);

if (isset($inputData['message'])) {
    $text = strtolower(trim($inputData['message']));
    $reply = "";
    $deviceStatus = null;

    $keywordsOn = ['nyalakan', 'hidupkan', 'on', 'aktifkan', 'tolong hidupin', 'hidupin', 'putar', 'nyala'];
    $keywordsOff = ['matikan', 'padamkan', 'off', 'nonaktifkan', 'matiin', 'stop', 'mati'];

    $isTurnOn = false;
    $isTurnOff = false;

    foreach ($keywordsOn as $k) {
        if (strpos($text, $k) !== false) {
            $isTurnOn = true;
            break;
        }
    }

    foreach ($keywordsOff as $k) {
        if (strpos($text, $k) !== false) {
            $isTurnOff = true;
            break;
        }
    }

    // Perintah Lampu
    if (strpos($text, 'lampu') !== false) {
        if ($isTurnOn) {
            $success = sendESP32("/lampu/on");
            $reply = $success ? "💡 Siap! Lampu berhasil dinyalakan." : "⚠️ Gagal terhubung ke ESP32.";
            $deviceStatus = ['device' => 'lampu', 'state' => true];
        } elseif ($isTurnOff) {
            $success = sendESP32("/lampu/off");
            $reply = $success ? "⬛ Siap! Lampu berhasil dimatikan." : "⚠️ Gagal terhubung ke ESP32.";
            $deviceStatus = ['device' => 'lampu', 'state' => false];
        }
    }
    // Perintah Kipas
    elseif (strpos($text, 'kipas') !== false || strpos($text, 'angin') !== false) {
        if ($isTurnOn) {
            $success = sendESP32("/kipas/on");
            $reply = $success ? "🌀 Siap! Kipas angin berhasil dinyalakan." : "⚠️ Gagal terhubung ke ESP32.";
            $deviceStatus = ['device' => 'kipas', 'state' => true];
        } elseif ($isTurnOff) {
            $success = sendESP32("/kipas/off");
            $reply = $success ? "❌ Siap! Kipas angin berhasil dimatikan." : "⚠️ Gagal terhubung ke ESP32.";
            $deviceStatus = ['device' => 'kipas', 'state' => false];
        }
    }

    if (empty($reply)) {
        $reply = "🤖 Maaf, JAROET AI belum mengerti perintah itu. Coba sebut kata 'lampu' atau 'kipas' dengan instruksi (hidupkan/matikan).";
    }

    echo json_encode([
        "reply" => $reply,
        "deviceStatus" => $deviceStatus
    ]);
    exit;
}
?>