<?php
require __DIR__ . '/../../services/userService.php';
require __DIR__ . '/../../services/authService.php';

class UpgradeController {
    
    private $userService;
    private $authService;

    function __construct() {
        $this->userService = new UserService();
        $this->authService = new AuthService();
    }

    public function index() {
        try {
            $headers = apache_request_headers();
            $authorizationHeader = $headers['Authorization'] ?? '';

            if ($_SERVER["REQUEST_METHOD"] !== 'GET' && empty($authorizationHeader)) {
                http_response_code(401);
                echo json_encode(["message" => "Authorization token is missing"]);
                return;
            }

            $token = str_replace('Bearer ', '', $authorizationHeader);
            $decoded = $this->authService->validateToken($token);

            if (!$decoded) {
                http_response_code(401);
                echo json_encode(["message" => "Invalid token"]);
                return;
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                header("Content-type: application/json");
                if (isset($_POST['id'])) {
                    $this->upgrade();
                } else {
                    http_response_code(400);
                }
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Internal server error: " . $e->getMessage()]);
        }
    }

    public function upgrade() {
        $id = htmlspecialchars($_POST['id']);

        $this->userService->upgrade($id);
    }
}