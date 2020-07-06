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
        $this->setFileName('config_conexao.php');
        $path = TGeneratorHelper::getPathNewSystem().DS.'app'.DS.'config';
        $this->setFilePath($path);
    }
    //--------------------------------------------------------------------------------------
    public function show($print = false)
    {
        $this->lines=null;
        $this->addLine('<?php');
        $this->addSysGenHeaderNote();
        $this->addBlankLine();
        $this->addLine('define(\'BANCO\'   , \''.$_SESSION[APPLICATION_NAME]['DBMS']['TYPE'].'\');');
        $this->addLine('define(\'HOST\'    , \''.$_SESSION[APPLICATION_NAME]['DBMS']['HOST'].'\');');
        $this->addLine('define(\'PORT\'    , \''.$_SESSION[APPLICATION_NAME]['DBMS']['PORT'].'\');');
        $this->addLine('define(\'DATABASE\', \''.$_SESSION[APPLICATION_NAME]['DBMS']['DATABASE'].'\');');
        $this->addLine('define(\'SCHEMA\'  , \''.$_SESSION[APPLICATION_NAME]['DBMS']['SCHEMA'].'\');');
        $this->addLine('define(\'USUARIO\' , \''.$_SESSION[APPLICATION_NAME]['DBMS']['USER'].'\');');
        $this->addLine('define(\'SENHA\'   , \''.$_SESSION[APPLICATION_NAME]['DBMS']['PASSWORD'].'\');');
        $this->addLine('define(\'UTF8_DECODE\'   , 0); //Decode String APP (UTF-8) to Database ISO-8859-1');
        $this->addLine('?>');
        if ($print) {
            echo $this->getLinesString();
        } else {
            return $this->getLinesString();
        }
    }
}
