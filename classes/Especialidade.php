<?php
    //NOMES: ISABELA FORTI, CAUE GASTALDI, SUZANA XAVIER
    require_once("./configs/BancoDados.php");
    
    class Especialidade{
        public static function adicionarEspecialidade($nome){
            try{
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("INSERT INTO especialidades(nome) VALUES(?)"); 
                $stmt->execute([$nome]); 
                
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

        public static function buscarEspecialidadePorNome($nome) {
            try{
                $conexao = Conexao::getConexao(); 
                $stmt = $conexao->prepare("SELECT * FROM especialidades WHERE lower(nome)=lower(?)"); 
                $stmt->execute([$nome]); 
                
                
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

        public static function buscarEspecialidadePorId($id) {
            try{
                $conexao = Conexao::getConexao(); 
                $stmt = $conexao->prepare("SELECT * FROM especialidades WHERE id=?"); 
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

        public static function listarEspecialidades(){
            try{
                $conexao = Conexao::getConexao(); 
                $stmt = $conexao->prepare("SELECT id, nome FROM especialidades ORDER BY id"); 
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

        public static function editarEspecialidade($id, $nome)
        {   
            try {
                $conexao = Conexao::getConexao();
                $stmt = $conexao->prepare("UPDATE especialidades SET nome = ? WHERE id = ?");
                $stmt->execute([$nome, $id]);

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

        public static function deletarEspecialidade($id){
            try{
                $conexao = Conexao::getConexao(); 
                $stmt = $conexao->prepare("DELETE FROM especialidades where id = ?"); 
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
    }

?>