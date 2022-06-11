<?php
    require_once("./configs/BancoDados.php");

    class Paciente {

        public static function adicionarPaciente($nome, $dataNascimento) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("INSERT INTO pacientes(nome, data_nascimento) VALUES (?, ?)");
                $stmt->execute([$nome, $dataNascimento]);

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

        public static function listarPaciente($id) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT * FROM pacientes WHERE id = ?");
                $stmt->execute([$id]);

                $retorno = $stmt->fetchAll();
                if (count($retorno) == 1) {
                    return $retorno[0];
                } else { 
                    return null;
                }
            } catch (Exception $e) {
                header("HTTP/1.1 500 Internal Server Error");
                echo json_encode([
                    "msg" => "Houve um erro na base de dados: " . $e->getMessage()
                ]);
                die;
            }
        }
        
        public static function listarPacientes() {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT * FROM pacientes");
                $stmt->execute();

                $pacientes = $stmt->fetchAll();
                if (count($pacientes) > 0) {
                    return $pacientes;
                } else { 
                    return null;
                }
            } catch (Exception $e) {
                header("HTTP/1.1 500 Internal Server Error");
                echo json_encode([
                    "msg" => "Houve um erro na base de dados: " . $e->getMessage()
                ]);
                die;
            }
        }

        public static function atualizarPaciente($id, $nome, $dataNascimento) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("UPDATE pacientes SET nome = ?, data_nascimento = ? WHERE id = ?");
                $stmt->execute([$nome, $dataNascimento, $id]);

                if ($stmt->rowCount() > 0) {
                    return true;
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

        public static function deletarPaciente($id) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("DELETE FROM pacientes WHERE id = ?");
                $stmt->execute([$id]);

                if ($stmt->rowCount() > 0) {
                    return true;
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

        public static function buscarConsultasVinculadas($idPaciente) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT count(*) qtde FROM consultas WHERE id_paciente = ?");
                $stmt->execute([$idPaciente]);
                
                $resultado = $stmt->fetchAll();
                $resultado = $resultado[0]["qtde"];
                
                return $resultado;
            } catch (Exception $e) {
                header("HTTP/1.1 500 Internal Server Error");
                echo json_encode([
                    "msg" => "Houve um erro na base de dados: " . $e->getMessage()
                ]);
                die;
            }
        }
    }
?>