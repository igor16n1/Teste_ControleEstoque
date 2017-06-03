<?php
    /*Classe de acesso ao produto pelo banco*/
    include("DBConn.php");
    include("../Util.php");
    class Produto
    {
        public function Inserir($nomeTabela, $colunas, $valores)
        {
            try 
            {
                $dbConn = DBConn::AbrirConexao();
                DBConn::Inserir($dbConn, $nomeTabela, $colunas, $valores);
                DBConn::FecharConexao($dbConn);
            }catch (Exception $e) {
                return "ERRO: " .  $e->getMessage();
            }
        }
        public function Apagar($nomeTabela, $condicaoCampo = '')
        {
            try 
            {
                $dbConn = DBConn::AbrirConexao();
                DBConn::Apagar($dbConn, $nomeTabela, $condicaoCampo);
                DBConn::FecharConexao($dbConn);
            }catch (Exception $e) {
                return "ERRO: " .  $e->getMessage();
            }
        }
        public function Atualizar($nomeTabela, $valor, $condicaoCampo = '')
        {
            try 
            {
                $dbConn = DBConn::AbrirConexao();
                DBConn::Atualizar($dbConn, $nomeTabela, $valor, $condicaoCampo);
                DBConn::FecharConexao($dbConn);
            }catch (Exception $e) {
                return "ERRO: " .  $e->getMessage();
            }
        }
        public function Consultar($nomeTabela, $colunas, $condicaoCampo = '')
        {
            try 
            {
                $dbConn = DBConn::AbrirConexao();
                DBConn::Consultar($dbConn, $nomeTabela, $colunas, $condicaoCampo);
                DBConn::FecharConexao($dbConn);
            }catch (Exception $e) {
                return "ERRO: " .  $e->getMessage();
            }          
        }
    }
?>