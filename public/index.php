<?php
declare(strict_types=1);

require_once __DIR__ . '/../controllers/ContactController.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$controller = new ContactController();

$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri    = rtrim($uri, '/');
$method = $_SERVER['REQUEST_METHOD'];

$basePath = str_replace('/public', '', dirname($_SERVER['SCRIPT_NAME']));
$path     = preg_replace('#^' . preg_quote($basePath, '#') . '#', '', $uri);
$path     = trim($path, '/');

$segments = $path === '' ? [] : explode('/', $path);

$resource = $segments[0] ?? null;
$id       = isset($segments[1]) ? (int) $segments[1] : null;

if ($resource === 'contacts') {
    switch ($method) {
        case 'GET':
            if ($id) {
                $controller->show($id);
            } else {
                $controller->index();
            }
            break;

        case 'POST':
            $controller->store();
            break;

        case 'DELETE':
            if ($id) {
                $controller->destroy($id);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Contact ID is required']);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['message' => 'Method not allowed']);
    }
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Route not found']);
}
