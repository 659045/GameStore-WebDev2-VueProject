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
                    header("Content-type: application/json");
                    if (!empty($_GET['user_id'])) {
                        $cart = $this->cartService->getCartByUserId(htmlspecialchars($_GET['user_id']));
                        echo json_encode($cart);
                    } else {
                        http_response_code(400);
                        echo json_encode(["message" => "Error fetching cart"]);
                    }
                    break;
                case 'POST':
                    $data = json_decode(file_get_contents('php://input'));

                    if (empty($data->game_id) || empty($data->user_id)) {
                        http_response_code(400);
                        echo json_encode(["message" => "Error adding cart"]);
                        return;
                    }

                    $cart = new Cart();
                    $cart->setGameId(htmlspecialchars($data->game_id));
                    $cart->setUserId(htmlspecialchars($data->user_id));

                    $this->cartService->insert($cart);
                    http_response_code(201);
                    break;
                case 'DELETE':
                    $data = json_decode(file_get_contents('php://input'));

                    if (!empty($data->game_id) && !empty($data->user_id)) {
                        $cart = new Cart();
                        $cart->setGameId(htmlspecialchars($data->game_id));
                        $cart->setUserId(htmlspecialchars($decoded->user_id));

                        $this->cartService->delete($cart);
                    } elseif (!empty($data->user_id)) {
                        $this->cartService->deleteAllFromUser(htmlspecialchars($data->user_id));
                    } else {
                        http_response_code(400);
                        echo json_encode(["message" => "Error deleting cart"]);
                        return;
                    }
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