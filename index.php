<!DOCTYPE html>
<html>
<head>
    <title>CRUD em PHP</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<div class="container">
    <h2>Formulário</h2>

    <!-- Formulário para adicionar produto -->
    <form method="post">
        <h3>Adicionar Produto</h3>
        <label for="name">Nome:</label>
        <input type="text" name="name" required><br>
        <label for="description">Descrição:</label>
        <textarea name="description"></textarea><br>
        <label for="price">Preço:</label>
        <input type="number" name="price" step="0.01" required><br>
        <input type="submit" name="submit" value="Adicionar Produto">
    </form>

    <!-- Tabela de produtos -->
    <h3>Lista de Produtos</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Preço</th>
            <th>Ação</th>
        </tr>
        <?php
    
        // Configurações do banco de dados
        $servername = "localhost";
        $username = "root"; 
        $password = ""; 
        $dbname = "crud_db"; 

        
        $conn = new mysqli($servername, $username, $password, $dbname);

        
        if ($conn->connect_error) {
            die("Conexão falhou: " . $conn->connect_error);
        }

        // Adicionar produto
        if (isset($_POST["submit"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST["name"];
            $description = $_POST["description"];
            $price = $_POST["price"];

            $sql = "INSERT INTO products (name, description, price) VALUES ('$name', '$description', $price)";

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Novo produto adicionado com sucesso.');</script>";
            } else {
                echo "Erro ao adicionar o produto: " . $conn->error;
            }
        }

        // Listar produtos
        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row["id"]."</td>";
                echo "<td>".$row["name"]."</td>";
                echo "<td>".$row["description"]."</td>";
                echo "<td>".$row["price"]."</td>";
                echo "<td>
                        <form method='post'>
                            <input type='hidden' name='id' value='".$row["id"]."'>
                            <button type='submit' name='update'>Atualizar</button>
                            <button type='submit' class='btn-danger' name='delete'>Excluir</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Nenhum produto encontrado.</td></tr>";
        }

        // Atualizar produto
        if (isset($_POST["update"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST["id"];

            // Consulta para obter os dados do produto selecionado
            $sql = "SELECT * FROM products WHERE id=$id";
            $result = $conn->query($sql);

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $name = $row["name"];
                $description = $row["description"];
                $price = $row["price"];
            }
        }

        // Excluir produto
        if (isset($_POST["delete"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST["id"];

            $sql = "DELETE FROM products WHERE id=$id";

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Produto excluído com sucesso.');</script>";
            } else {
                echo "Erro ao excluir o produto: " . $conn->error;
            }
        }

        $conn->close();
        ?>
    </table>
</div>

</body>
</html>
