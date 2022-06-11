<?php

    require_once "./configs/jwt/vendor/autoload.php";
    use \Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    class Token {

        private static $chaveSecreta = "qpoeiqo@#$*(çsdiewepoiewout";

        public static function criarTokenJWT($url, $idUsuario) {
            $chaveSecreta = "qpoeiqo@#$*(çsdiewepoiewout";
            $agora = new DateTimeImmutable();
            $validade = $agora->modify("+10 minutes")->getTimestamp();
            $servidor = $url;

            $dadosToken = [
                "iat" => $agora->getTimestamp(),
                "iss" => $servidor,
                "nbf" => $agora->getTimestamp(),
                "exp" => $validade,
                "sub" => $idUsuario,
            ];
            $token = JWT::encode($dadosToken, self::$chaveSecreta, 'HS512');
            
            return $token;
        }

        public static function verificarTokenJWT($url) {
            if (!preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(array("status" => "Erro", "msg" => "Bearer não enviado"));
                return false;
            }

            $jwt = $matches[1];
            if (!$jwt) {
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(array("status" => "Erro", "msg" => "Bearer incorreto"));
                return false;
            }

            $token = null;
            try {
                $token = JWT::decode($jwt, new Key(self::$chaveSecreta,"HS512"));
            } catch (Exception $e) {
                header('HTTP/1.1 401 Unauthorized');
                echo json_encode(array("status" => "Erro", "msg" => "Bearer inválido"));
                return false;
            }

            $agora = new DateTimeImmutable();
            if ($token->iss !== $url || $token->nbf > $agora->getTimestamp() || $token->exp < $agora->getTimeStamp()) {
                header('HTTP/1.1 401 Unauthorized');
                echo json_encode(array("status" => "Erro", "msg" => "Bearer inválido ou expirado"));
                return false;
            }

            return $token;
        }
    }