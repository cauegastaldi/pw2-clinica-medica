<?php
    //NOMES: ISABELA FORTI, CAUE GASTALDI, SUZANA XAVIER   
    require_once("./configs/BancoDados.php");
    require_once("./configs/json/header.php");
    require_once("./configs/json/utils.php");
    require_once("./configs/json/verbs.php");
    require_once("./classes/Consulta.php");
    require_once("./classes/Medico.php");
    require_once("./classes/Paciente.php");

    $logado = Token::verificarTokenJWT("http://localhost/PW2/EAD04");
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

            $consulta = Consulta::listarConsulta($id);
            if ($consulta == null) {
                header("HTTP/1.1 404 Not Found");
                echo json_encode([
                    "status" => "error",
                    "msg" => "Consulta de id = $id não encontrada!"
                ]);
                die;
            } else {
                header("HTTP/1.1 200 OK");
                echo json_encode($consulta);
                die;
            }
        } else {
            $consultas = Consulta::listarConsultas();   
            if ($consultas == null) {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode([
                    "status" => "error",
                    "msg" => "Não há consultadas cadastradas!"
                ]);
                die;
            } else {
                header("HTTP/1.1 200 OK");
                echo json_encode($consultas);
                die;
            }
        }
    }
    
    // ============================ POST ================================

    if (isMetodo("POST")) {
        if(!parametrosValidos($_POST, ["data_consulta", "id_medico", "id_paciente"])){
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetros inválidos!"
            ]);
            die;
        }

        $data_consulta = $_POST["data_consulta"];
        $id_medico = $_POST["id_medico"];
        $id_paciente = $_POST["id_paciente"];

        if (!dataEhValida($data_consulta, 'Y-m-d H:i')) {      
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetro 'data_consulta' não é válido!",
            ]);
            die;
        }      

        if (!filterIsInt($id_medico)) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetro 'id_medico' não é um inteiro!"
            ]);
            die;
        }

        if($id_medico < 0){
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetro 'id_medico' não pode ser um número negativo!"
            ]);
            die;
        }

        if (!filterIsInt($id_paciente)) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetro 'id_paciente' não é um inteiro!"
            ]);
            die;
        }

        if($id_paciente < 0){
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetro 'id_paciente' não pode ser um número negativo!"
            ]);
            die;
        }

        $paciente_procurado = Paciente::listarPaciente($id_paciente);

        if($paciente_procurado == null){
                header("HTTP/1.1 404 Not Found");
                echo json_encode([
                    "status" => "error",
                    "msg" => "Paciente com id = $id_paciente não encontrado no sistema!"
            ]);
            die;
        }

        $medico_procurado = Medico::buscarMedico($id_medico);

        if($medico_procurado == null){
                header("HTTP/1.1 404 Not Found");
                echo json_encode([
                    "status" => "error",
                    "msg" => "Médico com id = $id_medico não encontrado no sistema!"
            ]);
            die;
        }

        $id = Consulta::adicionarConsulta($data_consulta, $id_medico, $id_paciente);

        if ($id) {
            $consulta = Consulta::listarConsulta($id);
            header("HTTP/1.1 201 Created");
            echo json_encode($consulta);
            die;
        } else { 
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode([
                "status" => "error",
                "msg" => "Não foi possivel inserir a consulta!"
            ]);
            die; 
        }
    }

    // ============================ PUT =================================

    if (isMetodo("PUT")) {
        if (!parametrosValidos($_PUT, ["id", "data_consulta", "id_medico", "id_paciente"])) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetros inválidos!"
            ]);
            die;
        }
            
        $id = $_PUT["id"];
        $data_consulta = $_PUT["data_consulta"];
        $id_medico = $_PUT["id_medico"];
        $id_paciente = $_PUT["id_paciente"];

        if (!filterIsInt($id)) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetro 'id' não é inteiro!"
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

        if (Consulta::listarConsulta($id) == null) {
            header("HTTP/1.1 404 Not Found");
            echo json_encode([
                "status" => "error",
                "msg" => "Consulta de id = $id não existe!"
            ]);
            die;
        }

        if (!dataEhValida($data, 'Y-m-d H:i')) {      
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetro 'data_consulta' não é válido",
            ]);
            die;
        } 

        if (!filterIsInt($id_medico)) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetro 'id_medico' não é um inteiro!"
            ]);
            die;
        }

        if($id_medico < 0){
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetro 'id_medico' não pode ser um número negativo!"
            ]);
            die;
        }

        if (!filterIsInt($id_paciente)) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetro 'id_paciente' não é um inteiro!"
            ]);
            die;
        }

        if($id_paciente < 0){
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetro 'id_paciente' não pode ser um número negativo!"
            ]);
            die;
        }
            
        if (Medico::buscarMedico($id_medico) == null) {
            header("HTTP/1.1 404 Not Found");
            echo json_encode([
                "status" => "error",
                "msg" => "Médico de id = $id_medico não encontrado!"
            ]);
            die; 
        }

        if (Paciente::listarPaciente($id_paciente) == null) {
            header("HTTP/1.1 404 Not Found");
            echo json_encode([
                "status" => "error",
                "msg" => "Paciente de id = $id_paciente não encontrado!"
            ]);
            die; 
        }
    
        $res = Consulta::atualizarConsulta($id, $data_consulta, $id_medico, $id_paciente);
        if ($res) {
            header("HTTP/1.1 200 OK");
            echo json_encode([
                "status" => "OK",
                "msg" => "Consulta de id = $id editada com sucesso!"
            ]);
            die;
        } 
        else {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode([
                "status" => "error",
                "msg" => "Não foi possivel editar a consulta com id = $id!"
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

        $consulta = Consulta::listarConsulta($id);

        if ($consulta == null) {
            header("HTTP/1.1 404 Not Found");
            echo json_encode([
                "status" => "error",
                "msg" => "Consulta de id = $id não encontrada!"
            ]);
            die;
        } else {
            $res = Consulta::deletarConsulta($id);
            if ($res) {
                header("HTTP/1.1 200 OK");
                echo json_encode([
                    "status" => "OK",
                    "msg" => "Consulta de id = $id deletada com sucesso!"
                ]);
                die;
            } else {
                header("HTTP/1.1 500 Internal Server Error");
                echo json_encode([
                    "status" => "error",
                    "msg" => "Não foi possivel deletar a consulta com id = $id!"
                ]);
                die;
            }
        }
    }   
?>
    