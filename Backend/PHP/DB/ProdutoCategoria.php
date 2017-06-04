<?php
    /*Classe de acesso a categoria do produto pelo banco*/
    require_once "DBConn.php";
    require_once "../Util.php";
    class ProdutoCategoriaDB
    {
        private $colunas = array("ID", "Nome", "Ativo");
        public function getColunas(){
            return $this->colunas;
        }
        private $nomeTabela = 'tb_produtocategoria';
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
        public function Excluir($condicaoCampo = '')
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