<?php
    //NOMES: ISABELA FORTI, CAUE GASTALDI, SUZANA XAVIER
    require_once("./configs/BancoDados.php");
    
    class Medico{
        public static function adicionarMedico($nome, $crm, $id_especialidade){
            try{
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("INSERT INTO medicos(nome, crm, id_especialidade) VALUES(?, ?, ?)"); 
                $stmt->execute([$nome, $crm, $id_especialidade]); 
                
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

        public static function buscarMedico($id) {
            try{
                $conexao = Conexao::getConexao(); 
                $stmt = $conexao->prepare("SELECT id, nome, crm, id_especialidade FROM medicos WHERE id=?"); 
                $stmt->execute([$id]); 
                
                
                $resultado = $stmt->fetchAll();
                if(count($resultado) == 0){
                    return null;
                }
                return $resultado[0];

            }catch(Exception $e){
                header("HTTP/1.1 500 Internal Server Error");
                echo json_encode([
                    "msg" => "Houve um erro na base de dados: " . $e->getMessage()
                ]);
                exit;
            }
        }

        public static function buscarMedicoPorCRM($crm) {
            try{
                $conexao = Conexao::getConexao(); 
                $stmt = $conexao->prepare("SELECT id, nome, crm, id_especialidade FROM medicos WHERE crm=?"); 
                $stmt->execute([$crm]); 
                
                $resultado = $stmt->fetchAll();
                if(count($resultado) == 0){
                    return null;
                }
                return $resultado[0];
            }catch(Exception $e){
                header("HTTP/1.1 500 Internal Server Error");
                echo json_encode([
                    "msg" => "Houve um erro na base de dados: " . $e->getMessage()
                ]);
                exit;
            }
        }

        public static function listarMedicos(){
            try{
                $conexao = Conexao::getConexao(); 
                $stmt = $conexao->prepare("SELECT id, nome, crm, id_especialidade FROM medicos ORDER BY id"); 
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

        public static function editarMedico($id, $nome, $crm, $id_especialidade)
        {   
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("UPDATE medicos SET nome = ?, crm = ?, id_especialidade = ? WHERE id = ?");
                $stmt->execute([$nome, $crm, $id_especialidade, $id]);

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
                exit;
            }
        }

        public static function deletarMedico($id){
            try{
                $conexao = Conexao::getConexao(); 
                $stmt = $conexao->prepare("DELETE FROM medicos where id = ?"); 
                $stmt->execute([$id]); 
                
                if($stmt->rowCount() > 0){ 
                    return true; 
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

        public static function buscarConsultasVinculadas($idMedico) {
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("SELECT count(*) qtde FROM consultas WHERE id_medico = ?");
                $stmt->execute([$idMedico]);
                
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