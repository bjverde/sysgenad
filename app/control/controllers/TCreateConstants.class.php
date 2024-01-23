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

class TCreateConstants extends TCreateFileContent
{

    public function __construct()
    {
        $this->setFileName('application.ini');
        $path = TGeneratorHelper::getPathNewSystem().DS.'app'.DS.'config';
        $this->setFilePath($path);
    }
    public function addTheme($qtdTab)
    {   
        $tpSystemTheme = TSysgenSession::getValue(TableInfo::TP_SYSTEM_THEME);
        
        if( $tpSystemTheme == TGeneratorHelper::THEME_THEME3 ){
            $this->addLine('theme = theme3');
        }elseif( $tpSystemTheme == TGeneratorHelper::THEME_THEME3V5 ){
            $this->addLine('theme = theme3_v5');
        }elseif( $tpSystemTheme == TGeneratorHelper::THEME_THEME4 ){
            $this->addLine('theme = theme4');                        
        }elseif( $tpSystemTheme == TGeneratorHelper::THEME_THEME4V5 ){
            $this->addLine('theme = theme4_v5');
        }else{
            $this->addLine('theme = theme_formdinv');
        }
    }
    public function addGeneral($systemAcronym)
    {
        $this->addBlankLine();
        $this->addLine('[general]');
        $this->addLine('timezone = America/Sao_Paulo');
        $this->addLine('language = pt');
        $this->addLine('application = '.$systemAcronym);
        $this->addTheme(null);
        $this->addLine('seed = ');
        $this->addLine('debug = 1');
        $this->addBlankLine();
        $this->addBlankLine();
    }
    public function addSystem($systemAcronym)
    {
        $this->addLine('[system]');
        $this->addLine('formdin_min_version='.FormDinHelper::version());
        $this->addLine('adianti_min_version='.FormDinHelper::getAdiantiFrameWorkVersion());
        $this->addLine('version='.TSysgenSession::getValue('GEN_SYSTEM_VERSION'));
        $this->addLine('system_name= "'.TSysgenSession::getValue('GEN_SYSTEM_NAME').'"' );
        $this->addLine('system_name_sub=\'Subtítulo do sistema\'');
        $this->addLine('login = \'login\'');
        $this->addLine('logo-lg='.$systemAcronym);
        $this->addLine('logo-mini = /images/icon.png');
        $this->addLine('logo-link-class = \'index.php?class=Gen00\'');
        $this->addLine('login-link = https://localhost/'.$systemAcronym);
    }    
    //--------------------------------------------------------------------------------------
    public function show($print = false)
    {
        $systemAcronym = TGeneratorHelper::getGenSystemAcronym();
        $this->setLinesArrayBranco();
        $this->addGeneral($systemAcronym);
        $this->addSystem($systemAcronym);
        if ($print) {
            echo $this->getLinesString();
        } else {
            return $this->getLinesString();
        }
    }
}
