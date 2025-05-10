<?php

class CartService {

    public function getCart() {
        return $_SESSION["cart"] ?? [];
    }

    public function insert($id) {
        if(!isset($_SESSION["cart"])) {
            $_SESSION["cart"] = [];
        }

        if(in_array($id, $_SESSION["cart"])) {
            return $_SESSION["cart"];
        } else {
            array_push($_SESSION["cart"], $id);
        }

        return $_SESSION["cart"];
    }

    public function delete($id) {
        if (!isset($_SESSION["cart"])) {
            return;
        }

        $index = array_search($id, $_SESSION["cart"]);
        if ($index !== false) {
            unset($_SESSION["cart"][$index]);
        }
    }
}