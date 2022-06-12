<?php
    //NOMES: ISABELA FORTI, CAUE GASTALDI, SUZANA XAVIER
    require_once("./configs/BancoDados.php");

    class Consulta{
        public static function adicionarConsulta($data_consulta, $id_medico, $id_paciente){
            try{
                $conexao = Conexao::getConexao(); 
                $stmt = $conexao->prepare("INSERT INTO consultas(data, id_medico, id_paciente) VALUES (?,?,?)"); 
                $stmt->execute([$data_consulta, $id_medico, $id_paciente]); 

                if($stmt->rowCount() > 0){ 
                    return $conexao->lastInsertId(); 
                }else{
                    return false;
                }

            }catch(Exception $e){
                header("HTTP/1.1 500 Internal Server Error");
                echo json_encode([
                    "msg" => "Houve um erro na base de dados: " . $e->getMessage()
                ]);
                exit;
            } 
        }

        public static function listarConsulta($id){
            try{
                $conexao = Conexao::getConexao(); 
                $stmt = $conexao->prepare("SELECT id, data, id_medico, id_paciente FROM consultas WHERE id=? "); 
                $stmt->execute([$id]); 
                
                $resultado = $stmt->fetchAll();
                if(count($resultado) == 1){
                    return $resultado[0];
                }else{
                    return null;
                }

            }catch(Exception $e){
                header("HTTP/1.1 500 Internal Server Error");
                echo json_encode([
                    "msg" => "Houve um erro na base de dados: " . $e->getMessage()
                ]);
                exit;
            }
        }

        public static function listarConsultas(){
            try{
                $conexao = Conexao::getConexao(); 
                $stmt = $conexao->prepare("SELECT id, id_paciente, id_medico, data FROM consultas"); 
                $stmt->execute(); 
                
                $resultado = $stmt->fetchAll(); 
                if(count($resultado) == 0){
                    return null;
                }
                return $resultado;

            }catch(Exception $e){
                header("HTTP/1.1 500 Internal Server Error");
                echo json_encode([
                    "msg" => "Houve um erro na base de dados: " . $e->getMessage()
                ]);
                exit;
            }
        }

        public static function atualizarConsulta($id, $data_consulta, $id_medico, $id_paciente){
            try{
                $conexao = Conexao::getConexao(); 
                $stmt = $conexao->prepare("UPDATE consultas SET data=?, id_medico=?, id_paciente=? WHERE id=?");
                $stmt->execute([$data_consulta, $id_medico, $id_paciente, $id]); 

                if($stmt->rowCount() > 0){ 
                    return true; 
                }else{
                    return false;
                }

            } catch(Exception $e){
                header("HTTP/1.1 500 Internal Server Error");
                echo json_encode([
                    "msg" => "Houve um erro na base de dados: " . $e->getMessage()
                ]);
                exit;
            }
        }

        public static function deletarConsulta($id){
            try{
                $conexao = Conexao::getConexao(); 
                $stmt = $conexao->prepare("DELETE FROM consultas WHERE id=? ");
                $stmt->execute([$id]); 

                if($stmt->rowCount() > 0){ 
                    return true; 
                }else{
                    return false;
                }

            } catch(Exception $e){
                header("HTTP/1.1 500 Internal Server Error");
                echo json_encode([
                    "msg" => "Houve um erro na base de dados: " . $e->getMessage()
                ]);
                exit;
            }
        }

    }
?>
