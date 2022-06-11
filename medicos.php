<?php
    //NOMES: ISABELA FORTI, CAUE GASTALDI, SUZANA XAVIER
    require_once("./configs/BancoDados.php");
    require_once("./configs/json/header.php");
    require_once("./configs/json/utils.php");
    require_once("./configs/json/verbs.php");
    require_once("./classes/Token.php");
    require_once("./classes/Medico.php");
    require_once("./classes/Especialidade.php");

    $logado = Token::verificarTokenJWT("http://localhost.com");
    if (!$logado) {
        die;
    }
    
    // ============================ GET =================================

    if (isMetodo("GET")) {
        if (isset($_GET["id"])) {
            $id = $_GET["id"];

            if (!filterIsInt($id)) {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode([
                    "status" => "error",
                    "msg" => "Parâmetro ID não é um inteiro!"
                ]);
                die;
            }

            if($id < 0){
                header("HTTP/1.1 400 Bad Request");
                echo json_encode([
                    "status" => "error",
                    "msg" => "Parâmetro ID não pode ser um número negativo!"
                ]);
                die;
            }

            $medico = Medico::buscarMedico($id);
            if ($medico == null) {
                header("HTTP/1.1 404 Not Found");
                echo json_encode([
                    "status" => "error",
                    "msg" => "Médico de id = $id não encontrado!"
                ]);
                die;
            } else {
                header("HTTP/1.1 200 OK");
                echo json_encode($medico);
                die;
            }
        } else {
            $medicos = Medico::listarMedicos();
            if ($medicos == null) {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode([
                    "status" => "error",
                    "msg" => "Não há médicos cadastrados!"
                ]);
                die;
            }else{
                header("HTTP/1.1 200 OK");
                echo json_encode($medicos);
                die;
            }
        }
    }

    // ============================ POST ================================

    if (isMetodo("POST")) {
        if(!parametrosValidos($_POST, ["nome", "crm", "idEspecialidade"])) {
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetros inválidos!"
            ]);
            die;
        }

        $nome = $_POST["nome"];
        $crm = $_POST["crm"];
        $idEspecialidade = $_POST["idEspecialidade"];
        
        if (!filterIsInt($crm)) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetro 'crm' não é um número inteiro!"
            ]);
            die;
        }

        if($crm < 0){
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetro 'crm' não pode ser um número negativo!"
            ]);
            die;
        }

        if (!filterIsInt($idEspecialidade)) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetro 'idEspecialidade' não é um número inteiro!"
            ]);
            die;
        }

        if($idEspecialidade < 0){
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetro 'idEspecialidade' não pode ser um número negativo!"
            ]);
            die;
        }

        if (strlen($crm) != 6) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "CRM deve possuir exatamente 6 dígitos!"
            ]);
            die;
        }

        if (!crmEhUnico($crm)) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "CRM não é único!"
            ]);
            die;
        }

        if (!Especialidade::buscarEspecialidadePorId($idEspecialidade)) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Especialidade de id = $idEspecialidade não existe!"
            ]);
            die;
        }

        $id = Medico::adicionarMedico($nome, $crm, $idEspecialidade);

        if($id){
            $medico = Medico::buscarMedico($id);
            header("HTTP/1.1 201 Created");
            echo json_encode([$medico]);
            die;
        }else{
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode([
                "status" => "error",
                "msg" => "Não foi possivel inserir médico!"
            ]);
            die;
        }
    }

    // ============================ PUT =================================

    if (isMetodo("PUT")) {
        if(!parametrosValidos($_PUT, ["id", "nome", "crm", "idEspecialidade"])) {
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetros inválidos!"
            ]);
            die;
        }

        $id = $_PUT["id"];
        $nome = $_PUT["nome"];
        $crm = $_PUT["crm"];
        $idEspecialidade = $_PUT["idEspecialidade"];

        if (!filterIsInt($id)) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetro 'id' não é número inteiro!"
            ]);
            die;
        }

        if($id < 0){
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetro 'id' não pode ser um número negativo!"
            ]);
            die;
        }

        if (!filterIsInt($crm)) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetro 'crm' não é número inteiro!"
            ]);
            die;
        }

        if($crm < 0){
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetro 'crm' não pode ser um número negativo!"
            ]);
            die;
        }

        if (!filterIsInt($idEspecialidade)) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetro 'idEspecialidade' não é um número inteiro!"
            ]);
            die;
        }

        if($idEspecialidade < 0){
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetro 'idEspecialidade' não pode ser um número negativo!"
            ]);
            die;
        }

        $medico = Medico::buscarMedico($id);

        if ($medico == null) {
            header("HTTP/1.1 404 Not Found");
            echo json_encode([
                "status" => "error",
                "msg" => "Médico de id = $id não encontrado!"
            ]);
            die; 
        }

        $res = Medico::editarMedico($id, $nome, $crm, $idEspecialidade);

        if ($res) {
            header("HTTP/1.1 200 OK");
            echo json_encode([
                "status" => "OK",
                "msg" => "Médico de id = $id editado com sucesso!"
            ]);
            die;
        }else{
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode([
                "status" => "error",
                "msg" => "Não foi possivel editar o médico com id = $id!"
            ]);
            die;
        } 
    }

    // ============================ DELETE ===============================

    if (isMetodo("DELETE")) {
        if (!parametrosValidos($_GET, ["id"])) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetro 'id' não especificado!"
            ]);
            die;
        }

        $id = $_GET["id"];

        if (!filterIsInt($id)) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetro 'id' não é um inteiro!"
            ]);
            die;
        }

        if($id < 0){
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetro 'id' não pode ser um número negativo!"
            ]);
            die;
        }
        
        $medico = Medico::buscarMedico($id);

        if ($medico == null) {
            header("HTTP/1.1 404 Not Found");
            echo json_encode([
                "msg" => "Medico de id = $id não encontrado!"
            ]);
            die;
        } else {
            $numeroDeConsultasVinculadas = Medico::buscarConsultasVinculadas($id);
            if ($numeroDeConsultasVinculadas > 0) {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode([
                    "status" => "error",
                    "msg" => "Não é possível deletar o(a) médico(a) de id = $id, pois ele(a) possui $numeroDeConsultasVinculadas consulta(s) vinculada(s) a ele(a)!"
                ]);
                die;
            }

            $res = Medico::deletarMedico($id);
            if ($res) {
                header("HTTP/1.1 200 OK");
                echo json_encode([
                    "status" => "OK",
                    "msg" => "Médico de id = $id deletado com sucesso!"
                ]);
                die;
            }else {
                header("HTTP/1.1 500 Internal Server Error");
                echo json_encode([
                    "status" => "error",
                    "msg" => "Não foi possivel deletar o médico com id = $id!"
                ]);
                die;
            }
        }
    }

?>
