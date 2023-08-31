<?php
session_start();
ob_start();
include_once 'conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Robies - Login</title>
</head>

<body>
    <?php
    //Exemplo criptografar a senha
    //echo password_hash(123456, PASSWORD_DEFAULT);
    ?>

    <h1>Login</h1>

    <?php
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    if (!empty($dados['SendLogin'])) {
        //var_dump($dados);
        $query_usuario = "SELECT id_usuario, nome_u, email_u, senha_u
                        FROM usuarios 
                        WHERE email_u =:email_u  
                        LIMIT 1";
      
        $result_usuario = $conexao->prepare($query_usuario);
        $result_usuario->bindParam(':email_u', $dados['email_u'], PDO::PARAM_STR);
        $result_usuario->execute();
        
       

        if(($result_usuario) AND ($result_usuario->rowCount() != 0)){
          
            $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
           // var_dump($row_usuario);
            if(password_verify($dados['senha_u'], $row_usuario['senha_u'])){
                echo "teste";
                $_SESSION['id_usuario'] = $row_usuario['id_usuario'];
                $_SESSION['nome_u'] = $row_usuario['nome_u'];
                echo "Usuario: ".$row_usuario['id_usuario'];
                echo "Senha: ".$row_usuario['senha_u'];
                header("Location: dashboard.php");
            }else{
                $_SESSION['msg'] = "<p style='color: #ff0000'>Erro: Usuário ou senha inválida!</p>";
            }
        }else{
            $_SESSION['msg'] = "<p style='color: #ff0000'>Erro: Usuário ou senha inválida!</p>";
        }

        
    }

    if(isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>

    <form method="POST" action="">
        <label>Usuário</label>
        <input type="text" name="email_u" placeholder="Digite o usuário" value="<?php if(isset($dados['email_u'])){ echo $dados['email_u']; } ?>"><br><br>

        <label>Senha</label>
        <input type="password" name="senha_u" placeholder="Digite a senha" value="<?php if(isset($dados['senha_u'])){ echo $dados['senha_u']; } ?>"><br><br>

        <input type="submit" value="Acessar" name="SendLogin">
    </form>

    <br><br>
</body>

</html>