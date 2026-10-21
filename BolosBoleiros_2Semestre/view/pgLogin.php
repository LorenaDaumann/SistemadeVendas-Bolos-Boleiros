<?php
session_start();
$_SESSION["senha"] = "";
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="../assets/img/bolosboleiros.png">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/stylepgLogin.css">

    <title>Bolos Boleiros - Login</title>
</head>

<body id="B">

    <?php
    if (isset($_SESSION["erro"])) {
        echo '<div class="alert alert-danger text-center position-absolute top-0 mt-4" style="width:300px;">'
            . $_SESSION["erro"] .
            '</div>';
        unset($_SESSION["erro"]);
    }
    ?>

    <div class="FundoLogin">
        
        <div class="Esquerda"></div>
        <div class="Direita">
            <div class="body-login d-flex flex-column align-items-center shadow text-center" id="LoginFundo">
                <img src="../assets/img/bolosboleiros.png" alt="Login" class="mb-3" style="width: 300px;">

                <form name="login" action="../controller/login.php" method="POST">
                    <div class="mb-3 mt-3">
                        <label for="user" class="form-label">Usuário:</label>
                        <input type="text" class="form-control" placeholder="Usuário" name="nome" required>
                    </div>

                    <div class="mb-3 mt-3">
                        <label for="user" class="form-label">Senha:</label>
                        <input type="password" class="form-control" placeholder="Senha" name="senha" required>
                    </div>

                    <button type="submit" class="btn btn-primary center" id="BtnEnviar">Enviar</button>

                </form>
                <br>
            </div>
        </div>
    </div>
    
</body>

</html>