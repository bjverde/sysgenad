<?php
class ManualConnection
{
    public static function testConnection($db)
    {
        TTransaction::open(NULL, $db); // open transaction
        $conn = TTransaction::get(); // get PDO connection             
        TTransaction::close(); // close transaction
        return $conn;
    }
}