<?php
    /*Classe de negócio para produto*/
    require_once "../DB/Produto.php";
    require_once "Usuario.php";
    require_once "ProdutoCategoria.php";
    class ProdutoBI
    {
        private $caminhoImagemServidorSalvar = 'C:\\xampp\\htdocs\\Teste_ControleEstoque\\Backend\\PHP\\Img';
        private $caminhoImagemServidor = 'C:\\\xampp\\\htdocs\\\Teste_ControleEstoque\\\Backend\\\PHP\\\Img';
        //Insere um produto
        public function Inserir($usuarioID, $Nome, $CategoriaID, $CaminhoImagem = '', $imagemExtensao = '', $Descricao = '""')
        {
            try 
            {
                //Validar dados do usuário
                $usuarioBI = new UsuarioBI();
                if(!$usuarioBI->ValidarUsuarioPorID($usuarioID))
                    return 'Usuário não encontrado';
                //Validar parâmetros
                if($Nome == null || $Nome == '')
                    return 'Por favor preencha o nomme do produto';
                if($CategoriaID == null || $CategoriaID == 0)
                    return 'Por favor selecione uma categoria';
                //Validar categoria
                $ProdutoCategoriaBI = new ProdutoCategoriaBI();
                if(!$ProdutoCategoriaBI->ValidarCategoriaProdutoPorID($CategoriaID))
                    return 'Categoria não registrada';
                //Verifica se já existe um produto com o mesmo nome
                $produtoDB = new ProdutoDB();
                $colunasProduto = Util::MontarStringComArray($produtoDB->getColunas());
                //Valida nome e categoria do produto
                if(count(json_decode($produtoDB->Consultar($colunasProduto, ' WHERE Nome=' . "'" . $Nome . "'" . ' AND CategoriaID=' . $CategoriaID), true)) > 0) 
                    return 'Produto já cadastrado';
                $colunasRemover = $produtoDB->getColunas();//Recebe as coluna do banco
                array_splice($colunasRemover, 0, 1);//Retira a coluna ID
                $colunasInserir = Util::MontarStringComArray($colunasRemover);
                $nomeSemEspacos = preg_replace('/\s+/', '', $Nome);
                $nomeSemEspacos = preg_replace('/[^A-Za-z0-9\-]/', '', $nomeSemEspacos);
                $verificarImagemExiste = $this->caminhoImagemServidorSalvar . "\\" . $nomeSemEspacos . "." . $imagemExtensao;
                $imgExiste = "";
                if(file_exists($verificarImagemExiste)) 
                    $imgExiste = "1";
                $caminhoImagemfinal = "'" . $this->caminhoImagemServidor . "\\" . $nomeSemEspacos . $imgExiste . "." . $imagemExtensao . "'";
                $caminhoImagemfinalSalvar = $this->caminhoImagemServidorSalvar . "\\" . $nomeSemEspacos . $imgExiste . "." . $imagemExtensao;
                $valoresInserir = Util::MontarStringComArray(array("'" . $Nome . "'", $caminhoImagemfinal , $Descricao, $CategoriaID, 1));
                //Insere os dados cadastrados no banco
                $produtoDB->Inserir($colunasInserir, $valoresInserir);
                //Verifica se uma imagem foi carregada pelo usuário
                if($CaminhoImagem != '' && $CaminhoImagem != null && $imagemExtensao != '' && $imagemExtensao != null)
                    copy($CaminhoImagem , $caminhoImagemfinalSalvar);
                //Verifica se a imagem foi salva no banco
                if (!file_exists($caminhoImagemfinalSalvar)) 
                    return 'Não foi possível salvar a imagem em nosso servidor';
                //Verifica se o produto foi cadastrado
                if(count(json_decode($produtoDB->Consultar($colunasProduto, ' WHERE Nome=' . "'" . $Nome . "'" . ' AND CategoriaID=' . $CategoriaID), true)) == 1)
                    return 'Produto cadastrado com sucesso';
                else
                    return 'Não foi possível cadastrar o produto';
            }catch (Exception $e) {
                return "ERRO: " .  $e->getMessage();
            }
        }
        /*Exclui um produto*/
        public function Excluir($usuarioID, $ID, $CategoriaID)
        {
            try 
            {
                if($CategoriaID == null || $CategoriaID == 0)
                    return 'Por favor selecione uma categoria';
                if($ID == null || $ID == 0)
                    return 'ID do produto inválido';
                $usuarioBI = new UsuarioBI();
                if(!$usuarioBI->ValidarUsuarioPorID($usuarioID))
                    return 'Usuário não encontrado';
                $ProdutoCategoriaBI = new ProdutoCategoriaBI();
                if(!$ProdutoCategoriaBI->ValidarCategoriaProdutoPorID($CategoriaID))
                    return 'Categoria não registrada';
                //Exclui o produto
                $produtoDB = new ProdutoDB();
                $colunasProduto = Util::MontarStringComArray($produtoDB->getColunas());
                $produtoDB->Apagar(' WHERE ID=' . $ID . ' AND CategoriaID=' . $CategoriaID);
                //Verifica se o produto foi excluído
                if(count(json_decode($produtoDB->Consultar($colunasProduto, ' WHERE ID=' . $ID . ' AND CategoriaID=' . $CategoriaID), true)) == 0)
                    return 'Produto excluído com sucesso';
                else
                    return 'Não foi possível excluir o produto';
            }catch (Exception $e) {
                return "ERRO: " .  $e->getMessage();
            }
        }
        //Atualiza um produto
        public function Atualizar($usuarioID, $ID, $Nome, $CaminhoImagem, $Descricao, $CategoriaID, $Ativo, $imagemExtensao = '')
        {
            try 
            {
                $usuarioBI = new UsuarioBI();
                if(!$usuarioBI->ValidarUsuarioPorID($usuarioID))
                    return 'Usuário não encontrado';
                 if($ID == null || $ID == 0)
                    return 'ID do produto inválido';
                if($Nome == null)
                    return 'Nome do produto inválido';
                if($CaminhoImagem == null)
                    return 'Imagem do produto inválida';
                if($Descricao == null)
                    return 'Descrição do produto inválida';
                 if($CategoriaID == null || $CategoriaID == 0)
                    return 'Por favor selecione uma categoria'; 
                $ProdutoCategoriaBI = new ProdutoCategoriaBI();
                if(!$ProdutoCategoriaBI->ValidarCategoriaProdutoPorID($CategoriaID))
                    return 'Categoria não registrada';
                $produtoDB = new ProdutoDB();
                $colunasRemover = $produtoDB->getColunas();//Recebe as coluna do banco
                $colunasProduto = Util::MontarStringComArray($produtoDB->getColunas());
                $nomeSemEspacos = preg_replace('/\s+/', '', $Nome);
                $nomeSemEspacos = preg_replace('/[^A-Za-z0-9\-]/', '', $nomeSemEspacos);
                $verificarImagemExiste = $this->caminhoImagemServidorSalvar . "\\" . $nomeSemEspacos . "." . $imagemExtensao;
                $imgExiste = "";
                if(file_exists($verificarImagemExiste)) 
                    $imgExiste = "1";
                $caminhoImagemfinal = "'" . $this->caminhoImagemServidor . "\\\\" . $nomeSemEspacos . $imgExiste . "." . $imagemExtensao . "'";
                $caminhoImagemfinalSalvar = $this->caminhoImagemServidorSalvar . "\\\\" . $nomeSemEspacos . $imgExiste . "." . $imagemExtensao;
                array_splice($colunasRemover, 0, 1);//Retira a coluna ID
                $valorAtualizar = array(
                    $colunasRemover[0] => $Nome,
                    $colunasRemover[1] => $caminhoImagemfinal,
                    $colunasRemover[2] => $Descricao,
                    $colunasRemover[3] => $CategoriaID,
                    $colunasRemover[4] => $Ativo
                );
                $colunasInserir = Util::MontarStringComDictionaryUpdate($valorAtualizar);
                //Atualiza imagem no banco
                $nomeSemEspacos = preg_replace('/\s+/', '', $Nome);
                $nomeSemEspacos = preg_replace('/[^A-Za-z0-9\-]/', '', $nomeSemEspacos);
                $verificarImagemExiste = $this->caminhoImagemServidorSalvar . "\\" . $nomeSemEspacos . "." . $imagemExtensao;
                $imgExiste = "";
                if(file_exists($verificarImagemExiste)) 
                    $imgExiste = "1";
                $caminhoImagemfinal = "'" . $this->caminhoImagemServidor . "\\" . $nomeSemEspacos . $imgExiste . "." . $imagemExtensao . "'";
                $caminhoImagemfinalSalvar = $this->caminhoImagemServidorSalvar . "\\" . $nomeSemEspacos . $imgExiste . "." . $imagemExtensao;
                //Verifica se uma imagem foi carregada pelo usuário
                if($CaminhoImagem != '' && $CaminhoImagem != null && $imagemExtensao != '' && $imagemExtensao != null)
                    copy($CaminhoImagem , $caminhoImagemfinalSalvar);
                //Verifica se a imagem foi salva no banco
                if (!file_exists($caminhoImagemfinalSalvar)) 
                    return 'Não foi possível salvar a imagem em nosso servidor';
                //Deleta imagem antiga
                $colunasProduto = Util::MontarStringComArray($produtoDB->getColunas());
                $json = json_decode($produtoDB->Consultar($colunasProduto,  ' WHERE ID='. $ID), true);
                $imagemAntgaExcluir = $json[0]['CaminhoImagem'];
                unlink($imagemAntgaExcluir);
                //Verifica se a imagem foi deletada
                if(file_exists($imagemAntgaExcluir)) 
                    return "Não foi possível substituir a imagem";
                //Atualiza os dados cadastrados no banco
                $produtoDB->Atualizar($colunasInserir, ' WHERE ID='. $ID);
                //Verifica se o produto foi atualizado
                if(count(json_decode($produtoDB->Consultar($colunasProduto,  ' WHERE Nome=' . $Nome . ' AND ID='. $ID . ' AND CategoriaID='. $CategoriaID . ' AND Ativo='.$Ativo), true)) == 1)
                    return 'Produto ' . $Nome . ' atualizado com sucesso';
                else
                    return 'Não foi possível atualizar o produto ' . $Nome;
            }catch (Exception $e) {
                return "ERRO: " .  $e->getMessage();
            }
        }
        //Consultar produtos
        public function ConsultarProdutos($Nome = '', $Ativo = 0, $CategoriaID = 0)
        {
            try 
            {
                $produtoDB = new ProdutoDB();
                $colunasProduto= Util::MontarStringComArray($produtoDB->getColunas());
                $produtoNome = '';
                if($Nome != '')//Caso o nome esteja ativo
                    $produtoNome= ' Nome LIKE'. "'%" . $Nome . "%'";
                $produtoAtivo = '';
                if($Ativo != 2)//Caso o ID do Ativo seja 2 traga os produtos ativos e inativos
                {
                    if($Nome != '')
                        $produtoAtivo =  ' AND ';
                    $produtoAtivo = $produtoAtivo . " Ativo=". $Ativo;
                }
                $produtoCategoria = '';
                if($CategoriaID != 0)//Caso o ID da categoria seja diferente de zero
                {
                    if($Nome != '' || $Ativo != 2)
                        $produtoCategoria = ' AND ';
                    $produtoCategoria = $produtoCategoria . " CategoriaID=". $CategoriaID;
                }
                $clausulaWhere = '';
                if($Nome != '' || $Ativo != 2 || $CategoriaID = 0)
                    $clausulaWhere = ' WHERE '; 
                $resultado = $produtoDB->Consultar($colunasProduto, $clausulaWhere . $produtoNome . $produtoAtivo . $produtoCategoria);
                if(count(json_decode($resultado, true)) > 0)
                    return $resultado;
                else
                    return 'Nehum resultado encontrado para ' . $Nome;
            }catch (Exception $e) {
                return "ERRO: " .  $e->getMessage();
            }
        }
    }
?>