<?php
    /*Classe de negócio para categoria de produto*/
    require_once "../DB/ProdutoCategoria.php";
    require_once "../DB/Usuario.php";
    class ProdutoCategoriaBI{
        public function InserirCategoria($usuarioID, $Nome)
        {
            try 
            {
                //validações de dados do cliente
                if($usuarioID == null)
                    return 'Usuário inválido';
                if($Nome == null || $Nome == "")
                    return 'Por favor preencha o nome da categoria';
                $usuarioDB = new UsuarioDB();
                $colunas = Util::MontarStringComArray($usuarioDB->getColunas());
                //Verifica se existe um usuário cadastrado
                if(count(json_decode($usuarioDB->Consultar($colunas, ' WHERE ID=' . $usuarioID), true)) != 1)
                   return 'Usuário inválido';
                //Verifica se já existe uma categoria com o mesmo nome
                $produtoCategoriaDB = new ProdutoCategoriaDB();
                $colunasProdutoCategoria = Util::MontarStringComArray($produtoCategoriaDB->getColunas());
                if(count(json_decode($produtoCategoriaDB->Consultar($colunasProdutoCategoria, ' WHERE Nome=' . $Nome), true)) > 0) 
                    return 'Categoria de produto já registrada';
                 $colunasRemover = $produtoCategoriaDB->getColunas();//Recebe as coluna do banco
                 array_splice($colunasRemover, 0, 1);//Retira a coluna ID
                 $colunasInserir = Util::MontarStringComArray($colunasRemover);
                 $valoresInserir = Util::MontarStringComArray(array($Nome, 1));
                 //Insere os dados cadastrados no banco
                 $produtoCategoriaDB->Inserir($colunasInserir, $valoresInserir);
                 //Verifica se a categoria foi cadastrada
                 if(count(json_decode($produtoCategoriaDB->Consultar($colunasProdutoCategoria, ' WHERE Nome=' . $Nome), true)) == 1)
                    return 'Categoria de produto cadastrada com sucesso';
                 else
                    return 'Não foi possível realizar o cadastro';
            }catch (Exception $e) {
                return "ERRO: " .  $e->getMessage();
            }
        }
    }

    $a = new ProdutoCategoriaBI();
    echo $a->InserirCategoria(3, "'Livros'");
?>