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

class TCreateConfigDataBase extends TCreateFileContent
{

    public function __construct()
    {
        $this->setFileName('maindatabase.php');
        $path = TGeneratorHelper::getPathNewSystem().DS.'app'.DS.'config';
        $this->setFilePath($path);
    }
    //--------------------------------------------------------------------------------------
    public function show($print = false)
    {
        $this->setLinesArrayBranco();
        $this->addLine('<?php');
        $this->addSysGenHeaderNote();
        $this->addLine('return [');
        $this->addLine(ESP.' "host" => "'.$_SESSION[APPLICATION_NAME]['DBMS']['HOST'].'"');
        $this->addLine(ESP.',"port" => "'.$_SESSION[APPLICATION_NAME]['DBMS']['PORT'].'"');
        $this->addLine(ESP.',"name" => "'.$_SESSION[APPLICATION_NAME]['DBMS']['DATABASE'].'"');
        $this->addLine(ESP.',"user" => "'.$_SESSION[APPLICATION_NAME]['DBMS']['USER'].'"');
        $this->addLine(ESP.',"pass" => "'.$_SESSION[APPLICATION_NAME]['DBMS']['PASSWORD'].'"');
        $this->addLine(ESP.',"type" => "'.$_SESSION[APPLICATION_NAME]['DBMS']['TYPE'].'"');
        $this->addLine(ESP.',"prep" => "1"');
        $this->addLine(ESP.',"slog" => "SystemSqlLogService"');
        $this->addLine('];');
        if ($print) {
            echo $this->getLinesString();
        } else {
            return $this->getLinesString();
        }
    }
}
