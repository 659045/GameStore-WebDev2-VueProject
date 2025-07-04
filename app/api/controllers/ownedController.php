<?php
require __DIR__ . '/../../services/ownedGameService.php';
require __DIR__ . '/../../services/cartService.php';

class OwnedController {

    private $ownedGameService;
    private $cartService;

    function __construct() {
        $this->ownedGameService = new OwnedGameService();
        $this->cartService = new CartService();
    }

    public function index() {
        try {
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET':
                    if (isset($_GET['user_id']) && isset($_GET['game_id'])) {
                        $ownedGame = $this->ownedGameService->getOwnedGameByUserIdAndGameId(htmlspecialchars($_GET['user_id']), htmlspecialchars($_GET['game_id']));
                        echo json_encode($ownedGame);
                    } else if (isset($_GET['user_id'])) {
                        $ownedGames = $this->ownedGameService->getOwnedGameByUserId(htmlspecialchars($_GET['user_id']));
                        echo json_encode($ownedGames);
                    } else {
                        $ownedGames = $this->ownedGameService->getAll();
                        echo json_encode($ownedGames);
                    }
                    break;
                case 'POST':
                    $data = json_decode(file_get_contents('php://input'));

                    $user_id = htmlspecialchars($data->user_id);
                    $game_id = htmlspecialchars($data->game_id);
            
                    $ownedGame = new OwnedGame;
                    $ownedGame->setUserId($user_id);
                    $ownedGame->setGameId($game_id);
            
                    $this->ownedGameService->insert($ownedGame);
                    break;
                default:
                    break;
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Internal server error: " . $e->getMessage()]);
        }
    }
}