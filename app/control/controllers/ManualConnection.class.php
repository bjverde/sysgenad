<?php
class ManualConnection
{
    /**
     * Transforma os parametros do Form para o padrão do Adianti 
     * @param array $db
     * @return array
     */
    public static function transFormParam($db)
    {   
        $paramConnect = array();
        $paramConnect['host'] = $db['HOST'];
        $paramConnect['port'] = $db['PORT'];
        $paramConnect['name'] = $db['DATABASE'];
        $paramConnect['user'] = $db['USER'];
        $paramConnect['pass'] = $db['PASSWORD'];
        $paramConnect['type'] = $db['TYPE'];
        $paramConnect['prep'] = $db['PREP'];
        return $paramConnect;
    }
    
    public static function testConnection($db)
    {
        $db = self::transFormParam($db);
        TTransaction::open(NULL, $db); // open transaction
        $conn = TTransaction::get(); // get PDO connection             
        TTransaction::close(); // close transaction
        return $conn;
    }
}