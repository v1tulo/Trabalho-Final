<?php
// Inclui os arquivos de conexão e classe Fornecedor
include_once 'conexao.php';
include_once 'fornecedor.php';

// Cria uma instância da classe de conexão
$conexao_banco = new Conexao();
// Conecta ao banco de dados
$banco = $conexao_banco->conectar();

// Cria uma instância da classe Fornecedor, passando a conexão como parâmetro
$fornecedor = new Fornecedor($banco);

// Verifica se o método de requisição é POST (se o formulário foi submetido)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se os campos 'fornecedor' e 'celular' foram preenchidos
    if (!empty($_POST['fornecedor']) && !empty($_POST['celular'])) {
        // Atribui os valores dos campos do formulário aos atributos da instância de Fornecedor
        $fornecedor->fornecedor = $_POST['fornecedor'];
        $fornecedor->celular = $_POST['celular'];

        // Tenta criar um novo fornecedor no banco de dados
        if ($fornecedor->criar()) {
            // Redireciona de volta para a página index.php após a inserção bem-sucedida
            header("Location: index.php");
            exit();
        } else {
            // Exibe uma mensagem de erro caso a criação do fornecedor falhe
            echo "<p>Não foi possível criar o fornecedor.</p>";
        }
    } else {
        // Exibe uma mensagem solicitando que todos os campos sejam preenchidos
        echo "<p>Por favor, preencha todos os campos.</p>";
    }
}

// Lê todos os fornecedores cadastrados no banco de dados
$stmt = $fornecedor->ler();
// Obtém o número de registros retornados pela consulta
$num = $stmt->rowCount();

// Verifica se existem fornecedores cadastrados
if ($num > 0) {
    echo "<h2>Fornecedores Cadastrados</h2>";
    echo "<div class='lista-fornecedores'>";
    echo "<ul>";
    // Itera sobre os registros retornados, extraindo os valores e exibindo-os na página
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        echo "<li>ID: {$id}, Fornecedor: {$fornecedor}, Celular: {$celular}</li>";
    }
    echo "</ul>";
    echo "</div>";
} else {
    // Exibe uma mensagem caso não haja fornecedores cadastrados
    echo "<p>Nenhum fornecedor cadastrado.</p>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Fornecedores</title>
    <link rel="stylesheet" href="estilo.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
</head>
<body>
    <div class="container">
        <h2>Adicionar Novo Fornecedor</h2>
        <!-- Formulário para adicionar novo fornecedor -->
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="fornecedor">Fornecedor:</label>
            <input type="text" id="fornecedor" name="fornecedor" required>

            <label for="celular">Celular:</label>
            <input type="text" id="celular" name="celular" required>

            <input type="submit" value="Adicionar Fornecedor">
        </form>
    </div>
    <!-- Script para aplicar máscara de telefone ao campo 'celular' -->
    <script>
        $(document).ready(function(){
            $('#celular').mask('(00)0 0000-0000');
        });
    </script>
</body>
</html>
