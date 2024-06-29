<?php
require __DIR__ . '/../../services/userService.php';

class UserController {
    
    private $userService;

    function __construct() {
        $this->userService = new UserService();
    }

    public function index() {
        try {
            switch ($_SERVER["REQUEST_METHOD"]) {
                case "GET":
                    header("Content-type: application/json");
                    if (isset($_GET['username'])) {
                        $user = $this->userService->getUserByUsername(htmlspecialchars($_GET['username']));
                        echo json_encode($user);
                    } elseif (isset($_GET['email'])) {
                        $user = $this->userService->getUserByEmail(htmlspecialchars($_GET['email']));
                        echo json_encode($user);
                    } else {
                        $users = $this->userService->getAll();
                        echo json_encode($users);
                    }
                    break;
                case "POST":
                    $input = json_decode(file_get_contents('php://input'), true);
                    if (isset($input['id']))
                        $this->editUser($input);
                    else
                        $this->insertUser($input);
                    break;
                case "DELETE":
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

    public function insertUser($input) {
        $result = $this->validateInputs($input['email'], $input['username'], $input['password']);

        if ($result === true) {
            $user = new User();
            $user->setEmail(htmlspecialchars($input['email']));
            $user->setUsername(htmlspecialchars($input['username']));
            $user->setPassword(password_hash(htmlspecialchars($input['password']), PASSWORD_DEFAULT));
            $user->setRole('normal');
    
            $this->userService->insert($user);
            http_response_code(201);
        } else {
            http_response_code(400);
            echo json_encode(["message" => $result]);
        }
    }

    public function editUser($input) {
        $user = new User();
        $user->setId(htmlspecialchars($input['id']));
        $user->setEmail(htmlspecialchars($input['email']));
        $user->setUsername(htmlspecialchars($input['username']));
        
        $this->userService->edit($user);
        http_response_code(200);
    }

    public function deleteUser($id) {
        $this->userService->delete($id);
        http_response_code(200);
    }

    public function validateInputs($email, $username, $password) {
        if (empty($email) || empty($username) || empty($password)) {
            return 'Fill in all fields';
        } 
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Invalid email';
        } 
        
        if ($this->userService->getUserByEmail(htmlspecialchars($email))) {
            return 'Email already exists';
        } 
        
        if ($this->userService->getUserByUsername(htmlspecialchars($username))) {
            return 'Username already exists';
        } 
        
        return true;
    }
}