<?php 
class LogoutController
{
    public function index()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                session_destroy();
                http_response_code(200);
                echo "Logged out";
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo "Internal server error";
        }
    }
}