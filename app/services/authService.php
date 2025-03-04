<?php 

require_once __DIR__ . '/loginService.php';
require_once __DIR__ . '/userService.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService {

    private $secretKey;
    private $loginService;
    private $userService;

    public function __construct() {
        $config = require __DIR__ . '/../config/secret.php';
        $this->secretKey = $config['key'];
        $this->loginService = new LoginService();
        $this->userService = new UserService();
    }

    public function authenticate($username, $password) {
        $result = $this->loginService->login($username, $password);

        if ($result) {
            $user = $this->userService->getUserByUsername($username);

            $issuedAt = time();
            $expirationTime = $issuedAt + 86400;
            $payload = [
                'user_id' => $user->getId(),
                'username' => $user->getUsername(),
                'role' => $user->getRole(),
                'iat' => $issuedAt,
                'exp' => $expirationTime
            ];

            $token = JWT::encode($payload, $this->secretKey, 'HS256');
            
            return $token;
        }
        return false;
    }

    public function validateToken($token) {
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            return $decoded;
        } catch (Exception $e) {
            return false;
        }
    }
}

