<?php
require __DIR__ . '/../../services/userService.php';

class UpgradeController {
    
    private $userService;

    function __construct() {
        $this->userService = new UserService();
    }

    public function index() {
        try {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $this->upgrade();
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Internal server error: " . $e->getMessage()]);
        }
    }

    public function upgrade() {
        $id = htmlspecialchars($_POST['id']);

        $this->userService->upgrade($id);

        $_SESSION['role'] = 'premium';
    }
}