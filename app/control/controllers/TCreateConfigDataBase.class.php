<?php
/**
 * SysGen - System Generator with Formdin Framework
 * Download Formdin Framework: https://github.com/bjverde/formDin
 *
 * @author  Bjverde <bjverde@yahoo.com.br>
 * @license https://github.com/bjverde/sysgen/blob/master/LICENSE GPL-3.0
 * @link    https://github.com/bjverde/sysgen
 *
 * PHP Version 5.6
 */

class TCreateConfigDataBase extends TCreateFileContent
{

    public function __construct()
    {
        $this->setFileName('maindatabase.ini');
        $path = TGeneratorHelper::getPathNewSystem().DS.'app'.DS.'config';
        $this->setFilePath($path);
    }
    //--------------------------------------------------------------------------------------
    public function show($print = false)
    {
        $this->lines=null;
        $this->addLine('host = "'.$_SESSION[APPLICATION_NAME]['DBMS']['HOST'].'"');
        $this->addLine('port = "'.$_SESSION[APPLICATION_NAME]['DBMS']['PORT'].'"');
        $this->addLine('name = "'.$_SESSION[APPLICATION_NAME]['DBMS']['DATABASE'].'"');
        $this->addLine('user = "'.$_SESSION[APPLICATION_NAME]['DBMS']['USER'].'"');
        $this->addLine('pass = "'.$_SESSION[APPLICATION_NAME]['DBMS']['PASSWORD'].'"');
        $this->addLine('type = "'.$_SESSION[APPLICATION_NAME]['DBMS']['TYPE'].'"');
        $this->addLine('prep = "1"');
        $this->addLine('slog = SystemSqlLogService');
        if ($print) {
            echo $this->getLinesString();
        } else {
            return $this->getLinesString();
        }
    }
}
