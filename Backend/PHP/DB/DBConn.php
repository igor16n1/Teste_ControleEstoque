<?php
    /*Classe estática de conexão com o banco de dados*/
    class DBConn
    {
        private static $initialized = false;
        private static function initialize()
        {
            if (self::$initialized)
                return;
            self::$initialized = true;
        }
        /*Abre conexão com o banco de dados*/
        public static function AbrirConexao()
        {
            self::initialize();
            $dbConn = mysqli_connect('localhost', 'root', '') or die (mysql_error());
            mysqli_set_charset($dbConn, "utf8");
            mysqli_select_db($dbConn, 'teste_controleestoque') or die (mysql_error());
            return $dbConn;
        }
        /*Fecha conexão com o banco de dados*/
        public static function FecharConexao($dbConnParametro)
        {
            self::initialize();
            mysqli_close($dbConnParametro);
        }
        /*Insere registro no banco*/
        public static function Inserir($dbConnParametro, $nomeTabela, $colunas, $valores)
        {
            try 
            { 
                $sql = "INSERT INTO " . $nomeTabela . "(" . $colunas . ") VALUES (" .  $valores . ")";
                if ($dbConnParametro->query($sql) === TRUE) {
                    return "Registro inserido com sucesso";
                } else {
                    return "ERRO: " . $sql . " " . $dbConnParametro->error;
                }
            } catch (Exception $e) {
                return "ERRO: " .  $e->getMessage();
            }
        }
        /*Apagar registro no banco*/
        public static function Apagar($dbConnParametro, $nomeTabela, $condicaoCampo  = '')
        {
            try 
            {
                $sql = "DELETE FROM " . $nomeTabela . " " . $condicaoCampo;
                if ($dbConnParametro->query($sql) === TRUE) {
                    return "Apagado com sucesso";
                } else {
                    return "ERRO: " . $dbConnParametro->error;
                }
            } catch (Exception $e) {
                return "ERRO: " .  $e->getMessage();
            }
        }
        /*Atualizar registro no banco*/
        public static function Atualizar($dbConnParametro, $nomeTabela, $valor, $condicaoCampo = '')
        {
            try 
            {
                $sql = "UPDATE " . $nomeTabela . " SET " . $valor . " " . $condicaoCampo;
                echo $sql;
                if ($dbConnParametro->query($sql) === TRUE) {
                    return "Atualizado com sucesso";
                } else {
                    return "ERRO: " . $dbConnParametro->error;
                }
            }catch (Exception $e) {
                return "ERRO: " .  $e->getMessage();
            }
        }        
        /*Consulta registro no banco*/
        public static function Consultar($dbConnParametro, $nomeTabela, $colunas, $condicaoCampo = '')
        {
            try 
            {
                $rows = array();
                $sql = "SELECT " . $colunas . " FROM " . $nomeTabela . " " . $condicaoCampo;
                //echo $sql;
                $result = $dbConnParametro->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $rows[] = $row;
                    }
                }
                return json_encode($rows, JSON_UNESCAPED_UNICODE);
            }catch (Exception $e) {
                return "ERRO: " .  $e->getMessage();
            }
        }
    }
?>