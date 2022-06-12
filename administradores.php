<?php

    require_once("./configs/BancoDados.php");
    require_once("./configs/json/header.php");
    require_once("./configs/json/utils.php");
    require_once("./configs/json/verbs.php");
    require_once("./classes/Administrador.php");
    require_once("./classes/Token.php");

    if (isMetodo("GET") || isMetodo("DELETE")) {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode([
            "status" => "error",
            "msg" => "Operação inválida!"
        ]);
        die;
    }

    if (isMetodo("PUT")) {
        if (!parametrosValidos($_PUT, ["login", "senha"])) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetros inválidos!"
            ]);
            die;
        }

        $login = $_PUT["login"];
        $senha = $_PUT["senha"];

        if (!adminLoginIsUnique($login)) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Login não é único!"
            ]);
            die;
        }

        $hash = password_hash($senha, PASSWORD_BCRYPT, ["cost" => 12]);

        $id = Administrador::adicionarAdmin($login, $hash);
        if ($id) {
            header("HTTP/1.1 200 OK");
            echo json_encode([
                "id" => intval($id),
                "login" => $login
            ]);
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode([
                "status" => "error",
                "msg" => "Não foi possivel cadastrar o administrador!"
            ]);
        }
    }

    if (isMetodo("POST")) {
        if (!parametrosValidos($_POST, ["login", "senha"])) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "status" => "error",
                "msg" => "Parâmetros inválidos!"
            ]);
            die;
        }
        
        $login = $_POST["login"];
        $senhaDigitada = $_POST["senha"];

        $contaAdmin = Administrador::listarAdministrador($login);
        if ($contaAdmin == null) {
            header("HTTP/1.1 404 Not Found");
            echo json_encode([
                "status" => "error",
                "msg" => "Usuário ou senha inválidos!"
            ]);
            die;
        }

        $senhaHash = $contaAdmin["senha"];
        if (!password_verify($senhaDigitada, $senhaHash)) {
            header("HTTP/1.1 404 Not Found");
            echo json_encode([
                "status" => "error",
                "msg" => "Usuário ou senha inválidos!"
            ]);
            die;
        }

        $idAdmin = $contaAdmin["id"];
        $token = Token::criarTokenJWT("http://localhost.com", $idAdmin);
        header("Authorization: Bearer $token");
        echo json_encode([
            "status" => "OK",
            "token" => $token
        ]);
        
    }
