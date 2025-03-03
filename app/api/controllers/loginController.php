<?php
require __DIR__ . '/../../services/loginService.php';
require __DIR__ . '/../../services/userService.php';
require __DIR__ . '/../../services/authService.php';

class LoginController {

    private $authService;

    function __construct() {
        $this->authService = new AuthService();
    }

    public function index() {
        try {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);

                if (empty($data)) {
                    http_response_code(400);
                    echo json_encode(["message" => "Fill in all fields"]);
                    return;
                }

                $username = htmlspecialchars($data['username']);
                $password = htmlspecialchars($data['password']);

                $result = $this->authService->authenticate($username, $password);
                
                if ($result !== false) {
                    http_response_code(200);
                    echo json_encode(["token" => $result]);
                } else {
                    http_response_code(401);
                    echo json_encode(["message" => "Invalid username or password"]);
                }
            }
        } catch(Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Internal server error: " . $e->getMessage()]);
        }
        
    }
}