<?php
    /*Classe de negócio para usuário*/
    require_once "../DB/Usuario.php";
    class UsuarioBI{
        //Insere o cadastro do usuário (todos os campos são preenchidos)
        public function InserirCadastro($usuario, $senha, $repetirSenha, $email)
        {
            try 
            {
                //Validações dos campos passados pelo usuário
                if($usuario == null)
                    return 'Por favor digite o nome de usuário';
                if($senha == null)
                    return 'Por favor digite uma senha';
                if($repetirSenha == null)
                    return 'Por favor digite a mesma senha novamente';
                if($senha != $repetirSenha)
                    return 'Senhas diferentes, por favor digite novamente';
                if($email == null)
                    return 'Por favor digite um email válido';
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
                    return 'Email em formato inválido'; 
                $usuarioDB = new UsuarioDB();
                $colunas = Util::MontarStringComArray($usuarioDB->getColunas());
                //Verifica se já existe um usuário cadastrado com nome ou email
                if(count(json_decode($usuarioDB->Consultar($colunas, ' WHERE Usuario=' . $usuario), true)) != 0)
                    return 'Já existe um usuário cadastrado com o nome ' . $usuario;
                if(count(json_decode($usuarioDB->Consultar($colunas, ' WHERE Email=' . "'".$email."'"), true)) != 0)
                    return 'Já existe um usuário cadastrado com o email ' . $email;
                //Insere os dados no banco de dados
                $senhaCrip = md5($senha);//Criptografa a senha
                $colunasRemover = $usuarioDB->getColunas();//Recebe as coluna do banco
                array_splice($colunasRemover, 0, 1);//Retira a coluna ID
                $colunasInserir = Util::MontarStringComArray($colunasRemover);
                $valoresInserir = Util::MontarStringComArray(array($usuario, "'".$senhaCrip."'", "'".$email."'"));
                //Insere os dados cadastrados no banco
                $usuarioDB->Inserir($colunasInserir, $valoresInserir);
                //Verifica se o usuário foi cadastrado
                if(count(json_decode($usuarioDB->Consultar($colunas, ' WHERE Usuario=' . $usuario . ' AND  Email=' . "'".$email."'" ), true)) == 1)
                    return 'Usuário cadastrado com sucesso';
                else
                    return 'Não foi possível realizar o cadastro';
            }catch (Exception $e) {
                return "ERRO: " .  $e->getMessage();
            }
        }
        //Realiza o Login
        public function Login($usuario, $senha, $email)
        {
            try 
            {
                //Validações dos campos passados pelo usuário
                if($usuario == null && $email == null)
                    echo 'Por favor digite o nome de usuário ou email';
                if($senha == null)
                   echo 'Por favor digite uma senha';
                $usuarioDB = new UsuarioDB();
                $colunas = Util::MontarStringComArray($usuarioDB->getColunas());
                $senhaCrip = md5($senha);//Criptografa a senha
                //Verifica se existe um usuário cadastrado com nome ou email e senha
                if($usuario != null)
                {
                    if(count(json_decode($usuarioDB->Consultar($colunas, ' WHERE Usuario=' . $usuario . ' AND Senha = ' . "'" . $senhaCrip ."'" ), true)) != 1)
                        return "Usuário ou senha inválidos";
                }
                else
                {
                    if(count(json_decode($usuarioDB->Consultar($colunas, ' WHERE Email=' . "'".$email."'" . ' AND Senha = ' . "'" . $senhaCrip ."'"  ), true)) != 1)
                        return "Usuário ou senha inválidos";
                }
                return "Login realizado com sucesso";
            }catch (Exception $e) {
                return "ERRO: " .  $e->getMessage();
            }
        }
        //Valida usuário por ID
        public function ValidarUsuarioPorID($usuarioID)
        {
            //validações de dados do cliente
            if($usuarioID == null || $usuarioID == 0)
                return false;
            $usuarioDB = new UsuarioDB();
            $colunas = Util::MontarStringComArray($usuarioDB->getColunas());
            //Verifica se existe um usuário cadastrado
            if(count(json_decode($usuarioDB->Consultar($colunas, ' WHERE ID=' . $usuarioID), true)) != 1)
                return false;
            return true;
        }
    }
?>