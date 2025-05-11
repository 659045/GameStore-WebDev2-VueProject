<?php
require_once __DIR__ . '/repository.php';
require_once __DIR__ . '/../models/cart.php';

class CartRepository extends Repository {

    public function getCartByUserId($user_id) {
        try {
            $stmt = $this->connection->prepare("SELECT id, game_id, user_id FROM cart WHERE user_id = :user_id");
            $stmt->execute(array(':user_id' => $user_id));

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Cart');
            $cart = $stmt->fetchAll();

            return $cart;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function insert($cart) {
        try {
            $stmt = $this->connection->prepare(
                "INSERT INTO cart (game_id, user_id) VALUES (:game_id, :user_id)"
            );
            $results = $stmt->execute([
                ':game_id' => $cart->getGameId(),
                ':user_id' => $cart->getUserId()
            ]);

            return $results;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function deleteAllFromUser($user_id) {
        try {
            $stmt = $this->connection->prepare("DELETE FROM cart WHERE user_id = :user_id");
            $stmt->execute(array(':user_id' => $user_id));
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function delete($cart) {
        try {
            $stmt = $this->connection->prepare("DELETE FROM cart WHERE game_id = :id AND user_id = :user_id");
            $stmt->execute(array(':id' => $cart->getGameId(), ':user_id' => $cart->getUserId()));
        } catch (PDOException $e) {
            echo $e;
        }
    }
}