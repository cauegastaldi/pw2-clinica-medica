<?php

/* 
    Exemplo:
    parametroValidos($_POST, ["id", "nome"]);
*/
function parametrosValidos($metodo, $lista)
{
    $obtidos = array_keys($metodo);
    $nao_encontrados = array_diff($lista, $obtidos);
    if (empty($nao_encontrados)) {
        foreach ($lista as $p) {
            if (empty(trim($metodo[$p])) and trim($metodo[$p]) != "0") {
                return false;
            }
        }
        return true;
    }
    return false;
}

/* 
    Exemplo:
    isMetodo("PUT");
*/
function isMetodo($metodo)
{
    if (!strcasecmp($_SERVER['REQUEST_METHOD'], $metodo)) {
        return true;
    }
    return false;
}


function filterIsInt($v) {
    return filter_var($v, FILTER_VALIDATE_INT);
}

function filterIsEmail($v) {
    return filter_var($v, FILTER_VALIDATE_EMAIL);
}

function emptyString($str) {
    if(strlen(trim($str)) == 0) {
        return true;
    } 
    return false;
}

function adminLoginIsUnique($login, $idAdmin = null) {
    require_once("./classes/Administrador.php");
    try {
        $conexao = Conexao::getConexao();
        $stmt = $conexao->prepare("SELECT id from administradores WHERE login = ?");
        $stmt->execute([$login]);

        $res = $stmt->fetchAll();
        if (count($res) > 0) {
            if (isset($idAdmin)) {
                $id = $res[0]["id"];
                if ($id == $idAdmin)
                    return true;
            }
            return false;
        } else {
            return true;
        }
    } catch (Exception $e) {
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode([
            "msg" => "Houve um erro na base de dados: " . $e->getMessage(),
        ]);
        exit;
    }
}

function dataEhValida($data, $formato) {

    $dateTime = DateTime::createFromFormat($formato, $data);

    return $dateTime and $dateTime->format($formato) == $data;
    /* $dia = $dateTime->format("d");
    $mes = $dateTime->format("m");
    $ano = $dateTime->format("Y");

    return false;
    if (!checkdate($mes, $dia, $ano))
        return false;*/

    //return true;
    //return $dateTime and $dateTime->format($formato) == $data;
    /*if (!DateTime::createFromFormat("Y-m-d", $data))
        return false;
    return true;*/
}

function crmEhUnico($crm, $idMedico = null) {
    require_once("./classes/Medico.php");
    try {
        $conexao = Conexao::getConexao();
        $stmt = $conexao->prepare("SELECT id from medicos WHERE crm = ?");
        $stmt->execute([$crm]);

        $res = $stmt->fetchAll();
        if (count($res) > 0) {
            if (isset($idMedico)) {
                $id = $res[0]["id"];
                if ($id == $idMedico)
                    return true;
            }
            return false;
        } else {
            return true;
        }
    } catch (Exception $e) {
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode([
            "msg" => "Houve um erro na base de dados: " . $e->getMessage(),
        ]);
        exit;
    }
}

function existeEspecialidade($id) {
    try {
        $conexao = Conexao::getConexao();
        $stmt = $conexao->prepare("SELECT * from especialidades WHERE id = ?");
        $stmt->execute([$id]);

        $res = $stmt->fetchAll();
        if (count($res) > 0) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode([
            "msg" => "Houve um erro na base de dados: " . $e->getMessage(),
        ]);
        exit;
    }
}

function nomeDaEspecialidadeEhUnico($nome, $idEspecialidade = null) {
    try {
        $conexao = Conexao::getConexao();
        $stmt = $conexao->prepare("SELECT id from especialidades WHERE lower(nome) = lower(trim(?))");
        $stmt->execute([$nome]);

        $res = $stmt->fetchAll();
        if (count($res) > 0) {
            if (isset($idEspecialidade)) {
                $id = $res[0]["id"];
                if ($id == $idEspecialidade)
                    return true;
            }
            return false;
        } else {
            return true;
        }
    } catch (Exception $e) {
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode([
            "msg" => "Houve um erro na base de dados: " . $e->getMessage(),
        ]);
        exit;
    }
}
