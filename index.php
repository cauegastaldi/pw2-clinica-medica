<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Webservice Clínica Médica com autenticação JWT</title>
</head>

<body>
    <!-- INTEGRANTES DO GRUPO: SUZANA XAVIER, ISABELA FORTI, CAUÊ GASTALDI !-->

    <h1>Webservice de Clínica Médica</h1>
    <p>Este webservice JSON contém um sistema de exemplo de uma clínica médica, formada por <b>pacientes</b>,
        <b>médicos</b>, <b>especializações</b> e <b>consultas</b>. Para entrar, o usuário deve criar e fazer o login de
        <b>Admin</b> para realizar qualquer operação no sistema. Veja detalhes do login na rota
        <i>administradores.php</i>
    </p>
    <h2>As rotas disponíveis são:</h2>
    <p><a href="administradores.php">administradores.php</a> suporta as seguintes operações:</p>
    <ul>
        <li><b>PUT</b> - Adiciona um novo administrador. Requer parâmetros <i>login</i> e <i>senha</i>. O login deve ser
            único na aplicação (e não precisa ser um email!).</li>
        <li><b>POST</b> - Usado para login. Requer os parâmetros <i>login</i> e <i>senha</i>. Retorna um json contendo
            um token JWT válido para 10 minutos de interação.</li>
    </ul>
    <p><a href="pacientes.php">pacientes.php</a> suporta as seguintes operações:</p>
    <ul>
        <li><b>GET</b> - Retorna a lista completa de pacientes se não houver nenhum parâmetro id do tipo GET. Se houver um parâmetro id (do tipo GET), caso exista, retorna o paciente com o id especificado.</li>
        <li><b>POST</b> - Adiciona um novo paciente. Requer parâmetros <i>nome</i> e <i>dataNascimento</i> (no formato
            YYYY-MM-DD).</li>
        <li><b>PUT</b> - Edita um paciente. Requer parâmetros <i>id</i>, <i>nome</i> e <i>dataNascimento</i> (no formato
            YYYY-MM-DD).</li>
        <li><b>DELETE</b> - Remove um paciente. Requer parâmetro <i>id</i> (do tipo GET).</li>
    </ul>
    <p><a href="medicos.php">medicos.php</a> suporta as seguintes operações:</p>
    <ul>
        <li><b>GET</b> - Retorna a lista completa de médicos se não houver nenhum parâmetro id do tipo GET. Se houver um parâmetro id (do tipo GET), caso exista, retorna o médico com o id especificado.</li>
        <li><b>POST</b> - Adiciona um novo médico. Requer parâmetros <i>nome</i>, <i>crm</i> (deve ser único e possuir 6 dígitos) e <i>idEspecialidade</i>.</li>
        <li><b>PUT</b> - Edita um médico. Requer parâmetros <i>id</i>, <i>nome</i>, <i>crm</i> (deve ser único e possuir 6 dígitos) e <i>idEspecialidade</i>.</li>
        <li><b>DELETE</b> - Remove um médico. Requer parâmetro <i>id</i> (do tipo GET).</li>
    </ul>
    <p><a href="consultas.php">consultas.php</a> suporta as seguintes operações:</p>
    <ul>
        <li><b>GET</b> - Retorna a lista completa de consultas se não houver nenhum parâmetro id do tipo GET. Se houver um parâmetro id (do tipo GET), caso exista, retorna a consulta com o id especificado.</li>
        <li><b>POST</b> - Adiciona uma nova consulta. Requer parâmetros <i>id_paciente</i>, <i>id_medico</i> e
            <i>data_consulta</i> (no formato YYYY-MM-DD HH:mm).
        </li>
        <li><b>PUT</b> - Edita uma consulta. Requer parâmetros <i>id</i>, <i>id_paciente</i> e <i>id_medico</i> e
            <i>data_consulta</i> (no formato YYYY-MM-DD HH:mm).
        </li>
        <li><b>DELETE</b> - Remove uma consulta. Requer parâmetro <i>id</i> (do tipo GET).</li>
    </ul>
    <p><a href="especialidades.php">especialidades.php</a> suporta as seguintes operações:</p>
    <ul>
        <li><b>GET</b> - Retorna a lista completa de especialidades.</li>
        <li><b>POST</b> - Adiciona uma nova especialidade. Requer o parâmetro <i>nome</i>.
        <li><b>PUT</b> - Edita uma especialidade. Requer os parâmetros <i>id</i> e <i>nome</i>
        </li>
        <li><b>DELETE</b> - Remove uma especialidade. Requer parâmetro <i>id</i> (do tipo GET).
        </li>
        </li>
    </ul>
</body>

</html>
