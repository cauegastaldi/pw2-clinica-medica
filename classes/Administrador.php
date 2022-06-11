<?php

    require_once("./configs/BancoDados.php");

    class Administrador {
        public static function adicionarAdmin($login, $senha) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("INSERT INTO administradores(login,senha) VALUES (?, ?)");
                $stmt->execute([$login, $senha]);

                if ($stmt->rowCount() > 0) {
                    return $conexao->lastInsertId();
                } else {
                    return false;
                }
                
            } catch (Exception $e) {
                header("HTTP/1.1 500 Internal Server Error");
                echo json_encode([
                    "msg" => "Houve um erro na base de dados: " . $e->getMessage()
                ]);
                die;
            }
        }
        
        public static function listarAdministrador($login) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT * from administradores WHERE login = ?");
                $stmt->execute([$login]);

                $res = $stmt->fetchAll();
                if (count($res) > 0)
                    return $res[0];
                return null;
            } catch (Exception $e) {
                header("HTTP/1.1 500 Internal Server Error");
                echo json_encode([
                    "msg" => "Houve um erro na base de dados: " . $e->getMessage()
                ]);
                die;
            }
        }
    }