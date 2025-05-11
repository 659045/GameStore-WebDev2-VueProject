<?php
require_once __DIR__ . '/../repositories/cartRepository.php';

class CartService {

    private $cartRepository;

    function __construct() {
        $this->cartRepository = new CartRepository;
    }

    public function getCartByUserId($user_id) {
        return $this->cartRepository->getCartByUserId($user_id);
    }
    

    public function insert($cart) {
        return $this->cartRepository->insert($cart);
    }

    public function deleteAllFromUser($user_id) {
        return $this->cartRepository->deleteAllFromUser($user_id);
    }

    public function delete($cart) {
        return $this->cartRepository->delete($cart);
    }
}