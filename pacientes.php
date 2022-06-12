<?php

    require_once("./configs/BancoDados.php");
    require_once("./configs/json/header.php");
    require_once("./configs/json/utils.php");
    require_once("./configs/json/verbs.php");
    require_once("./classes/Paciente.php");
    require_once("./classes/Token.php");

    $logado = Token::verificarTokenJWT("http://localhost.com");
    if (!$logado) {
        die;
    }

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

            $paciente = Paciente::listarPaciente($id);
            if ($paciente == null) {
                header("HTTP/1.1 404 Not Found");
                echo json_encode([
                    "status" => "error",
                    "msg" => "Paciente de id = $id não encontrado!"
                ]);
                die;
            } else {
                header("HTTP/1.1 200 OK");
                echo json_encode($paciente);
                die;
            }
        } else {
            $pacientes = Paciente::listarPacientes();
            if ($pacientes == null) {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode([
                    "status" => "error",
                    "msg" => "Não há pacientes cadastrados!"
                ]);
            } else {
                header("HTTP/1.1 200 OK");
                echo json_encode($pacientes);
                die;
            }
        }
    }

    if (isMetodo("POST")) {
        if (!parametrosValidos($_POST, ["nome", "dataNascimento"])) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetros inválidos!"
            ]);
            die;
        }

        $nome = $_POST["nome"];
        $dataNascimento = $_POST["dataNascimento"];
       
        if (!dataEhValida($dataNascimento, "Y-m-d")) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Data de nascimento não é válida!"
            ]);
            die;
        }

        $id = Paciente::adicionarPaciente($nome, $dataNascimento);
        if ($id) {
            $paciente = Paciente::listarPaciente($id);
            header("HTTP/1.1 200 OK");
            echo json_encode($paciente);
            die;
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode([
                "status" => "error",
                "msg" => "Não foi possivel cadastrar o paciente!"
                ]);
            die;
        }
    }

    if (isMetodo("PUT")) {

        if (!parametrosValidos($_PUT, ["id", "nome", "dataNascimento"])) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetros inválidos!"
            ]);
            die;
        }
        
        $id = $_PUT["id"];

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

        if (Paciente::listarPaciente($id) == null) {
            header("HTTP/1.1 404 Not Found");
            echo json_encode([
                "status" => "error",
                "msg" => "Paciente especificado não existe!"
            ]);
            die;
        }

        $nome = $_PUT["nome"];
        $dataNascimento = $_PUT["dataNascimento"];

        if (!dataEhValida($dataNascimento, "Y-m-d")) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Data de nascimento não é válida!"
            ]);
            die;
        }

        $res = Paciente::atualizarPaciente($id, $nome, $dataNascimento);
        if ($res) {
            $paciente = Paciente::listarPaciente($id);
            header("HTTP/1.1 200 OK");
            echo json_encode(
                "status" => "OK",
                "msg" => "Paciente de id = $id editado com sucesso!"
            );
            die;
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode([
                "status" => "error",
                "msg" => "Não foi possivel editar o paciente!"
                ]);
            die;
        }
    }

    if (isMetodo("DELETE")) {
        
        if (!parametrosValidos($_GET, ["id"])) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetro inválido!"
            ]);
            die;
        }

        $id = $_GET["id"];

        if (!filterIsInt($id)) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetro ID não é um inteiro!"
            ]);
            die;
        }

        if ($id < 0) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetro ID não pode ser um número negativo!"
            ]);
            die;
        }

        if (Paciente::listarPaciente($id) == null) {
            header("HTTP/1.1 404 Not Found");
            echo json_encode([
                "status" => "error",
                "msg" => "Paciente especificado não existe!"
            ]);
            die;
        }

        $numeroDeConsultasVinculadas = Paciente::buscarConsultasVinculadas($id);
        if ($numeroDeConsultasVinculadas > 0) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Não é possível deletar o paciente de id = $id, pois ele possui $numeroDeConsultasVinculadas consulta(s) vinculada(s) a ele!"
            ]);
            die;
        }

        $res = Paciente::deletarPaciente($id);
        if ($res) {
            $paciente = Paciente::listarPaciente($id);
            header("HTTP/1.1 200 OK");
            echo json_encode([
                "status" => "OK",
                "msg" => "Paciente deletado com sucesso!"
            ]);
            die;
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode([
                "status" => "error",
                "msg" => "Não foi possivel deletar o paciente!"
                ]);
            die;
        }

    }
