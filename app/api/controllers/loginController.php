<?php
require __DIR__ . '/../../services/loginService.php';
require __DIR__ . '/../../services/userService.php';

class LoginController {

    private $loginService;
    private $userService;

    function __construct() {
        $this->loginService = new LoginService();
        $this->userService = new UserService();
    }

    public function index() {
        try {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);

                if (!isset($input['username']) || !isset($input['password'])) {
                    http_response_code(400);
                    echo json_encode(["message" => "Fill in all fields"]);
                    return;
                }

                $username = htmlspecialchars($input['username']);
                $password = htmlspecialchars($input['password']);

                if ($this->loginService->login($username, $password)) {
                    $user = $this->userService->getUserByUsername($username);
                    
                    http_response_code(200);
                    echo json_encode([
                        "user_id" => $user->getId(),
                        "role" => $user->getRole(),
                    ]);
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