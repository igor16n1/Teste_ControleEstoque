<?php
    /*classe de métodos padrões*/
    class Util
    {
        private static $initialized = false;
        private static function initialize()
        {
            if (self::$initialized)
                return;
            self::$initialized = true;
        }
        /*Recebe um array e retorna um string*/
        public static function MontarStringComArray(array $arrayString)
        {
            self::initialize();
            try 
            {
                $array = $arrayString;
                $arrlength = count($array);
                $valores = '';
                for($x = 0; $x < $arrlength; $x++) {
                    $valores = $valores .  $array[$x] . ',';
                }
                $valores = substr_replace($valores, "", -1);
                return $valores;
            }
            catch (Exception $e) {
                return "ERRO: " .  $e->getMessage();
            }
        }
        /*Recebe um dicionário e retorna um array com duas strings*/
        public static function MontarStringComDictionary(array $arrayString)
        {
            self::initialize();
            try 
            {
                $array = array();
                $valor1 = '';
                $valor2 = '';
                foreach($arrayString as $key=>$value) {
                    $valor1 = $valor1 . $key . ',';
                    $valor2 = $valor2 . $value . ',';
                }
                $valor1 = substr_replace($valor1, "", -1);
                $valor2 = substr_replace($valor2, "", -1);
                array_push($array, $valor1, $valor2);
                return $array;
            }
            catch (Exception $e) {
                return "ERRO: " .  $e->getMessage();
            }
        }
        /*Monta uma string com valores para atualizar banco*/
        public static function MontarStringComDictionaryUpdate(array $arrayString)
        {
            self::initialize();
            try 
            {
                $valor = '';
                foreach($arrayString as $key=>$value) {
                    $valor = $valor . ' ' . $key . '=' . $value . ',';
                }
                $valor = substr_replace($valor, "", -1);
                return $valor;
            }
            catch (Exception $e) {
                return "ERRO: " .  $e->getMessage();
            }
        }
    }
?>