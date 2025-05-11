<?php

class Cart implements JsonSerializable {
    private int $id;
    private int $game_id;
    private int $user_id;

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getGameId(): int {
        return $this->game_id;
    }

    public function setGameId(int $game_id): void {
        $this->game_id = $game_id;
    }

    public function getUserId(): int {
        return $this->user_id;
    }

    public function setUserId(int $user_id): void {
        $this->user_id = $user_id;
    }

    public function jsonSerialize(): mixed {
        return [
            'id' => $this->id,
            'game_id' => $this->game_id,
            'user_id' => $this->user_id
        ];
    }
}