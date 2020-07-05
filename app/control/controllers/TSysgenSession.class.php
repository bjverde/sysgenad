<?php
/**
 * SysGen - System Generator with Formdin Framework
 * Download Formdin Framework: https://github.com/bjverde/formDin
 *
 * @author  Bjverde <bjverde@yahoo.com.br>
 * @license https://github.com/bjverde/sysgen/blob/master/LICENSE GPL-3.0
 * @link    https://github.com/bjverde/sysgen
 *
 * PHP Version 7.1
 */
class TSysgenSession
{    
    const TP_SYSGEN_ADIANTI = 'TP_SYSGEN_ADIANTI';
    const TP_SYSGEN_FROMDIN = 'TP_SYSGEN_FORMDIN';

    
    public static function getTpSysgen()
    {
        return self::TP_SYSGEN_ADIANTI;
    }
    
    public static function setValue($var, $value)
    {
        if( self::TP_SYSGEN_ADIANTI == self::getTpSysgen() ){
            TSession::setValue($var, $value);
        }else{
            $_SESSION[APLICATIVO][$var]=$value;
        }
    }
    
    public static function getValue($var)
    {
        $result = null;
        if( self::TP_SYSGEN_ADIANTI == self::getTpSysgen() ){
            $result = TSession::getValue($var);
        }else{
            $result = $_SESSION[APLICATIVO][$var];
        }
        return $result;
    }
    
}
