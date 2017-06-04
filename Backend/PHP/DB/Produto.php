<?php
    /*Classe de acesso ao produto pelo banco*/
    include("DBConn.php");
    include("../Util.php");
    class ProdutoDB
    {
        private $colunas = array("ID", "Nome", "CaminhoImagem", "Descricao", "CategoriaID", "Ativo");
        public function getColunas(){
            return $this->colunas;
        }
        private $nomeTabela = 'tb_produto';
        public function Inserir($colunas, $valores)
        {
            try 
            {
                $dbConn = DBConn::AbrirConexao();
                DBConn::Inserir($dbConn, $this->nomeTabela, $colunas, $valores);
                DBConn::FecharConexao($dbConn);
            }catch (Exception $e) {
                return "ERRO: " .  $e->getMessage();
            }
        }
        public function Apagar($condicaoCampo = '')
        {
            try 
            {
                $dbConn = DBConn::AbrirConexao();
                DBConn::Apagar($dbConn, $this->nomeTabela, $condicaoCampo);
                DBConn::FecharConexao($dbConn);
            }catch (Exception $e) {
                return "ERRO: " .  $e->getMessage();
            }
        }
        public function Atualizar($valor, $condicaoCampo = '')
        {
            try 
            {
                $dbConn = DBConn::AbrirConexao();
                DBConn::Atualizar($dbConn, $this->nomeTabela, $valor, $condicaoCampo);
                DBConn::FecharConexao($dbConn);
            }catch (Exception $e) {
                return "ERRO: " .  $e->getMessage();
            }
        }
        public function Consultar($colunas, $condicaoCampo = '')
        {
            try 
            {
                $dbConn = DBConn::AbrirConexao();
                $resultado = DBConn::Consultar($dbConn, $this->nomeTabela, $colunas, $condicaoCampo);
                DBConn::FecharConexao($dbConn);
                return $resultado;
            }catch (Exception $e) {
                return "ERRO: " .  $e->getMessage();
            }          
        }
    }
?>