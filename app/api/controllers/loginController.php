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
                $username = htmlspecialchars($_POST['username']);
                $password = htmlspecialchars($_POST['password']);

                if ($this->loginService->login($username, $password)) {
                    $_SESSION["username"] = $username;
                    $_SESSION["user_id"] = $this->userService->getUserByUsername($username)->getId();

                    $user = $this->userService->getUserByUsername($username);

                    switch ($user->getRole()) {
                        case 'admin':
                            $_SESSION["role"] = 'admin';
                            break;
                        case 'premium':
                            $_SESSION["role"] = 'premium';
                            break;
                        default:
                            $_SESSION["role"] = 'normal';
                            break;
                    }
                    
                    http_response_code(200);
                    return $_SESSION["role"];
                } else {
                    http_response_code(401);
                    echo "Invalid username or password";
                }
            }
        } catch(Exception $e) {
            http_response_code(500);
            echo "Internal server error";
        }
        
    }
}