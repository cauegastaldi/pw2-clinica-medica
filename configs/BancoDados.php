<?php

/*
    Classe PHP que contémm um singleton que gera a conexão para um banco de dados usando PDO.
    Atenção que os valores de hostname, database, username e password dependendem do ambiente sendo utilizado.
    Lembre-se de colocar esse arquivo preferencialmente, em algum local com acesso restrito (.htaccess?)

    LAMP = linux, apache, mysql, php
    WAMP = windows, apache, mysql, php

    PDO = PHP Data Object = forma mais amigável de se conectar com o banco de dados
    singleton = somente uma classe/objeto faz o gerenciamento da conexao com o banco de dados para aumentar a seguranca e impedir que dois comandos sejam executados ao mesmo tempo de lugares diferentes, por exemplo


    - uma forma de importar o banco de dados é criar um novo arquivo no phpmyadmin com o nome do banco de dados, depois clicar em SQL e colar o código referente ao banco de dados


    */

class Conexao
{
    private static $instancia; //classe unica do singleton

    private function __construct()
    {
        $hostname = "localhost"; //endereco onde se encontra o banco de dados; localhost corresponde à maquina local
        $database = "clinica_medica_pw2"; //nome do banco de dados
        $username = "root"; //nome de usuário que possui permissão para esse banco de dados específico
        $password = ""; //senha do usuário que possui permissão para esse banco de dados específico

        $dsn = "mysql:host=$hostname;dbname=$database";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //sistema de excecoes
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //sistema de busca associativa
            PDO::ATTR_EMULATE_PREPARES => false, 
        ];

        try {
            self::$instancia = new PDO($dsn, $username, $password, $options);
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function getConexao()
    {
        if (!isset(self::$instancia)) { //se nao existir o objeto da conexao, essa funcao cria ele na linha abaixo
            new Conexao();
        }
        return self::$instancia; //se o objeto ja existir, ele o retorna
    }
}