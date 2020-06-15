<?php
class ManualConnection
{
    public static function testConnection($db)
    {
        TTransaction::open(NULL, $db); // open transaction
        $conn = TTransaction::get(); // get PDO connection

        FormDinHelper::debug($conn,'$conn');
        
        TTransaction::close(); // close transaction
    }
}