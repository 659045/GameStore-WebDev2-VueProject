<?php
require __DIR__ . '/../../services/gameService.php';

class GameController {

    private $gameService;

    function __construct() {
        $this->gameService = new GameService();
    }

    public function index() {
        try {
            switch ($_SERVER["REQUEST_METHOD"]) {
                case 'GET':
                    header("Content-type: application/json");
                    if (!empty($_GET['id'])) {
                        $game = $this->gameService->getGameById(htmlspecialchars($_GET['id']));
                        echo json_encode($game);
                    } elseif (!empty($_GET['title'])) {
                        $game = $this->gameService->getGameByTitle(htmlspecialchars($_GET['title']));
                        echo json_encode($game);
                    } else {
                        $games = $this->gameService->getAll();
                        echo json_encode($games);
                    }
                    break;
                case 'POST':
                    if (empty($_POST['title']) || empty($_POST['description']) || !isset($_POST['price']) || empty($_FILES['image'])) {
                        http_response_code(400);
                        echo json_encode(["message" => $POST['title'], "description" => $_POST['description'], "price" => $_POST['price'], "image" => $_FILES['image']]);
                        return;
                    }

                    if (!empty($_POST['id'])) {
                        $this->editGame();  
                    } else {
                        $this->insertGame();
                    }
                    break;
                case 'DELETE':
                    $data = json_decode(file_get_contents('php://input'), true);

                    if (empty($data['id'])) {
                        http_response_code(400);
                        echo json_encode(["message" => "Fill in all fields"]);
                        return;
                    }

                    $this->deleteGame(htmlspecialchars($data['id']));
                    break;
                default:
                    echo 'Error controller';
                    break;
            }
        } catch(Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Internal server error: " . $e->getMessage()]);
        }
    }

    function insertGame() {
        $game = new Game();
        $game->setTitle(htmlspecialchars($_POST['title']));
        $game->setDescription(htmlspecialchars($_POST['description']));
        $game->setPrice(htmlspecialchars($_POST['price']));

        $gameImage = $_FILES['image'];
        if (!empty($gameImage) && $gameImage['error'] == 0) {
            //add image to img folder
            $filename = htmlspecialchars($gameImage['name']);
            $destination = __DIR__ . '/../../public/img/' . $filename;
            if (move_uploaded_file(htmlspecialchars($gameImage['tmp_name']), $destination)) {
                $game->setImage($filename);
            }
        }

        $this->gameService->insert($game);
        http_response_code(201);
    }

    function editGame() {
        $game = new Game();
        $game->setId(htmlspecialchars($_POST['id']));
        $game->setTitle(htmlspecialchars($_POST['title']));
        $game->setDescription(htmlspecialchars($_POST['description']));
        $game->setPrice(htmlspecialchars($_POST['price']));

        $gameImage = $_FILES['image'];
        if ($gameImage && $gameImage['error'] == 0) {
            //add image to img folder
            $filename = htmlspecialchars($gameImage['name']);
            $destination = __DIR__ . '/../../public/img/' . $filename;
            if (move_uploaded_file(htmlspecialchars($gameImage['tmp_name']), $destination)) {
                $game->setImage($filename);
            }
        }

        $this->gameService->edit($game);
    }

    function deleteGame($id) {
        $this->gameService->delete($id);
    } 
}
?>