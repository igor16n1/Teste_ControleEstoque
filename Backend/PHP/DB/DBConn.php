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
        public static function Inserir($dbConnParametro)
        {
            $sql = "INSERT INTO `tb_produto`(`Nome`, `CaminhoImagem`, `Descricao`, `CategoriaID`, `Ativo`) 
            VALUES ('Teste','teste/teste','lorem ipsum',1,0)";

            if ($dbConnParametro->query($sql) === TRUE) {
                echo "REGISTRO INSERIDO COM SUCESSO";
            } else {
                echo "ERRO: " . $sql . "<br>" . $dbConnParametro->error;
            }
        }
        /*Consulta registro no banco*/
        public static function Consulta($dbConnParametro)
        {
            $sql = "SELECT `ID`, `Nome`, `CaminhoImagem`, `Descricao`, `CategoriaID`, `Ativo` 
            FROM `tb_produto` WHERE 1";
            $result = $dbConnParametro->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo $row["ID"] . "<br>" .  $row["Nome"] . "<br>" .  $row["CaminhoImagem"] . "<br>" .  $row["Descricao"] . "<br>" .  $row["CategoriaID"] . "<br>" .  $row["Ativo"];
                }
            } else {
                echo "NENHUM RESULTADO";
            }
        }
        /*Atualizar registro no banco*/
        public static function Atualizar($dbConnParametro)
        {
            $sql = "UPDATE tb_produto SET Ativo=1 WHERE ID=1";
            if ($dbConnParametro->query($sql) === TRUE) {
                echo "ATUALIZADO COM SUCESSO";
            } else {
                echo "ERRO AO ATUALIZAR: " . $dbConnParametro->error;
            }
        }
        /*Apagar registro no banco*/
        public static function Apagar($dbConnParametro)
        {
            $sql = "DELETE FROM tb_produto WHERE ID=1";
            if ($dbConnParametro->query($sql) === TRUE) {
                echo "APAGADO COM SUCESSO";
            } else {
                echo "ERRO AO DELETAR: " . $dbConnParametro->error;
            }
        }
    }
?>