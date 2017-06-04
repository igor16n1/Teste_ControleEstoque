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
    }
    $a = new ProdutoBI();
    $a->Inserir(3, "Triste Visionário", 9, 'C:\Users\IGOR\Downloads\imagem.jpg', "jpg");
?>