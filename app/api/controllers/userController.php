<?php
require __DIR__ . '/../../services/userService.php';
require __DIR__ . '/../../services/authService.php';

class UserController {
    
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

            if ($_SERVER["REQUEST_METHOD"] !== 'POST' && empty($authorizationHeader)) {
                $this->sendUnauthorizedResponse("Authorization token is missing");
            }

            $token = str_replace('Bearer ', '', $authorizationHeader);
            $decoded = $this->authService->validateToken($token);

            switch ($_SERVER["REQUEST_METHOD"]) {
                case "GET":
                    if (!$decoded) {
                        $this->sendUnauthorizedResponse("Invalid token");
                    }

                    header("Content-type: application/json");
                    if (isset($_GET['username'])) {
                        $user = $this->userService->getUserByUsername(htmlspecialchars($_GET['username']));
                        echo json_encode($user);
                    } else {
                        $users = $this->userService->getAll();
                        echo json_encode($users);
                    }
                    break;
                case "POST":
                    $data = json_decode(file_get_contents('php://input'), true);

                    if (empty($data['username']) || empty($data['password'])) {
                        http_response_code(400);
                        echo json_encode(["message" => "Fill in all fields"]);
                        return;
                    }

                    if (isset($data['id'])) {
                        $this->editUser($data);
                    } else {
                        $this->insertUser($data);
                    }
                    break;
                case "DELETE":
                    if (!$decoded) {
                        $this->sendUnauthorizedResponse("Invalid token");
                    }

                    $data = json_decode(file_get_contents('php://input'));
                    $this->deleteUser(htmlspecialchars($data->id));
                    break;
                default:
                    break;
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Internal server error: " . $e->getMessage()]);
        }
    }

    private function insertUser($data) {
        $result = $this->validateInputs($data['username']);

        if ($result === true) {
            $user = new User();
            $user->setUsername(htmlspecialchars($data['username']));
            $user->setPassword(password_hash(htmlspecialchars($data['password']), PASSWORD_DEFAULT));
            $user->setRole('normal');
    
            $this->userService->insert($user);
            http_response_code(201);
        } else {
            http_response_code(400);
            echo json_encode(["message" => $result]);
        }
    }

    private function editUser($data) {
        $result = $this->validateEditInputs($data['id'], $data['username']);

        if ($result === true) {
            $user = new User();
            $user->setId(htmlspecialchars($data['id']));
            $user->setUsername(htmlspecialchars($data['username']));
            $user->setPassword(password_hash(htmlspecialchars($data['password']), PASSWORD_DEFAULT));
            
            $this->userService->edit($user);
            http_response_code(200);
        } else {
            http_response_code(400);
            echo json_encode(["message" => $result]);
        }
    }

    private function deleteUser($id) {
        $this->userService->delete($id);
        http_response_code(200);
    }

    private function validateInputs($username) {
        if ($this->userService->getUserByUsername(htmlspecialchars($username))) {
            return 'Username already exists';
        } 
        
        return true;
    }

    private function validateEditInputs($id, $username) {
        if ($this->userService->getUserByUsername(htmlspecialchars($username)) && $this->userService->getUserByUsername(htmlspecialchars($username))->getId() !== $id) {
            return 'Username already exists';
        } 
        
        return true;
    }

    private function sendUnauthorizedResponse($message) {
        http_response_code(401);
        echo json_encode(["message" => $message]);
        exit;
    }
}