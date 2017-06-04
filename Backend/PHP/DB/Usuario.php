<?php
    /*Classe de acesso ao usuário pelo banco*/
    include("DBConn.php");
    include("../Util.php");
    class UsuarioDB
    {
        private $colunas = array("ID", "Usuario", "Senha", "Email");
        public function getColunas(){
            return $this->colunas;
        }
        private $nomeTabela = 'tb_usuario';
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