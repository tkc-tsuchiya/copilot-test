<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Simple file-based storage for messages
$messagesFile = __DIR__ . '/messages.json';

function getMessages() {
    global $messagesFile;
    if (!file_exists($messagesFile)) {
        return [];
    }
    $content = file_get_contents($messagesFile);
    return json_decode($content, true) ?: [];
}

function saveMessage($name, $message) {
    global $messagesFile;
    $messages = getMessages();
    $newMessage = [
        'id' => count($messages) + 1,
        'name' => htmlspecialchars($name),
        'message' => htmlspecialchars($message),
        'timestamp' => date('Y-m-d H:i:s')
    ];
    $messages[] = $newMessage;
    file_put_contents($messagesFile, json_encode($messages));
    return $newMessage;
}

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($method) {
    case 'GET':
        if ($path === '/api/messages' || $path === '/backend/api/messages') {
            echo json_encode(getMessages());
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Not found']);
        }
        break;
        
    case 'POST':
        if ($path === '/api/messages' || $path === '/backend/api/messages') {
            $input = json_decode(file_get_contents('php://input'), true);
            if (isset($input['name']) && isset($input['message'])) {
                $newMessage = saveMessage($input['name'], $input['message']);
                echo json_encode($newMessage);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Name and message are required']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Not found']);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
?>