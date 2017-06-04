<?php
    /*Classe de negócio para categoria de produto*/
    require_once "../DB/ProdutoCategoria.php";
    require_once "../DB/Usuario.php";
    class ProdutoCategoriaBI{
        //Insere uma categoria
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
                    return 'Não foi possível cadastrar a categoria';
            }catch (Exception $e) {
                return "ERRO: " .  $e->getMessage();
            }
        }
        //Exclui uma categoria
        public function ExcluirCategoria($usuarioID, $Nome)
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
                //Exclui a categoria
                $produtoCategoriaDB = new ProdutoCategoriaDB();
                $colunasProdutoCategoria = Util::MontarStringComArray($produtoCategoriaDB->getColunas());
                $produtoCategoriaDB->Excluir(' WHERE Nome=' . $Nome);
                //Verifica se a categoria foi excluída
                if(count(json_decode($produtoCategoriaDB->Consultar($colunasProdutoCategoria, ' WHERE Nome=' . $Nome), true)) == 0)
                    return 'Categoria de produto excluída com sucesso';
                else
                    return 'Não foi possível excluir a categoria';
            }catch (Exception $e) {
                return "ERRO: " .  $e->getMessage();
            }
        }
        //Atualiza uma categoria
        public function AtualizarCategoria($usuarioID, $Nome, $Ativo)
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
                //Verifica se já existe uma categoria com o nome
                $produtoCategoriaDB = new ProdutoCategoriaDB();
                $colunasProdutoCategoria = Util::MontarStringComArray($produtoCategoriaDB->getColunas());
                if(count(json_decode($produtoCategoriaDB->Consultar($colunasProdutoCategoria, ' WHERE Nome=' . $Nome), true)) == 0) 
                    return 'Categoria de produto não existe';
                $colunasRemover = $produtoCategoriaDB->getColunas();//Recebe as coluna do banco
                array_splice($colunasRemover, 0, 1);//Retira a coluna ID
                $valorAtualizar = array(
                    $colunasRemover[0] => $Nome,
                    $colunasRemover[1] => $Ativo
                );
                $colunasInserir = Util::MontarStringComDictionaryUpdate($valorAtualizar);
                //Atualiza os dados cadastrados no banco
                $produtoCategoriaDB->Atualizar($colunasInserir, ' WHERE Nome=' . $Nome);
                //Verifica se a categoria foi atualizada
                if(count(json_decode($produtoCategoriaDB->Consultar($colunasProdutoCategoria, ' WHERE Nome=' . $Nome . ' AND Ativo='.$Ativo), true)) == 1)
                    return 'Categoria ' . $Nome . ' atualizada com sucesso';
                else
                    return 'Não foi possível atualizar a categoria ' . $Nome;                
            }catch (Exception $e) {
                return "ERRO: " .  $e->getMessage();
            }
        }

    }
    
    $a = new ProdutoCategoriaBI();
    //echo $a->InserirCategoria(3, "'Livros'");
    //echo $a->ExcluirCategoria(3, "'Livros'");
    echo $a->AtualizarCategoria(3, "'Livros'", 1);
?>