<?php
require __DIR__ . '/../../services/cartService.php';
require __DIR__ . '/../../services/authService.php';

class CartController {
    
    private $cartService;
    private $authService;

    function __construct() {
        $this->cartService = new CartService();
        $this->authService = new AuthService();
    }

    public function index() {
        try {
            $headers = apache_request_headers();
            $authorizationHeader = $headers['Authorization'] ?? '';

            if (empty($authorizationHeader)) {
                $this->sendUnauthorizedResponse("Authorization token is missing");
            }

            $token = str_replace('Bearer ', '', $authorizationHeader);
            $decoded = $this->authService->validateToken($token);

            if (!$decoded) {
                $this->sendUnauthorizedResponse("Invalid token");
            }

            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET':
                    $cart = $this->cartService->getCart();

                    header("Content-type: application/json");
                    echo json_encode($cart);
                    break;
                case 'POST':
                    $data = json_decode(file_get_contents('php://input'));
                    $this->cartService->insert(htmlspecialchars($data->id));
                    break;
                case 'DELETE':
                    $data = json_decode(file_get_contents('php://input'));
                    $this->cartService->delete(htmlspecialchars($data->id));
                    break;
                default:
                    break;
            }  
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Internal server error: " . $e->getMessage()]);
        }
    }

    private function sendUnauthorizedResponse($message) {
        http_response_code(401);
        echo json_encode(["message" => $message]);
        exit;
    }
}