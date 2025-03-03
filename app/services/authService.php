<?php 

require_once __DIR__ . '/loginService.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService {

    private $secretKey;
    private $loginService;

    public function __construct() {
        $this->secretKey = require __DIR__ . '/../config/secret.php';
        $this->loginService = new LoginService();
    }

    public function authenticate($username, $password) {
        $user = $this->loginService->login($username, $password);

        if ($user) {
            $issuedAt = time();
            $expirationTime = $issuedAt + 86400;
            $payload = [
                'user_id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'],
                'iat' => $issuedAt,
                'exp' => $expirationTime
            ];
            
            return JWT::encode($payload, $this->secretKey, 'HS256');
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

