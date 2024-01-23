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

class TCreateForm extends TCreateFormGeneric
{
    /**
     * Create file FROM form a table info
     * @param string $pathFolder   - folder path to create file
     * @param string $tableName    - table name
     * @param array $listColumnsProperties
     */
    public function __construct($pathFolder ,$tableName ,$listColumnsProperties)
    {
        parent::__construct($pathFolder,$tableName,$listColumnsProperties);
    }
    //--------------------------------------------------------------------------------------
    public function addMethod_onSave($qtdTab)
    {
        $this->addBlankLine();
        $this->addLine();
        $this->addLine($qtdTab.'public function onSave($param)');
        $this->addLine($qtdTab.'{');
        $this->addLine($qtdTab.ESP.'$data = $this->form->getData();');
        $this->addLine($qtdTab.ESP.'//Função do FormDin para Debug');
        $this->addLine($qtdTab.ESP.'FormDinHelper::d($param,\'$param\');');
        $this->addLine($qtdTab.ESP.'FormDinHelper::debug($data,\'$data\');');
        $this->addLine($qtdTab.ESP.'FormDinHelper::debug($_REQUEST,\'$_REQUEST\');');
        $this->addBlankLine();
        $this->addLine($qtdTab.ESP.'try{');
        $this->addLine($qtdTab.ESP.ESP.'$this->form->validate();');        
        $this->addLine($qtdTab.ESP.ESP.'$this->form->setData($data);');
        $this->addLine($qtdTab.ESP.ESP.'$vo = new '.$this->getTableRefVO().'();');
        $this->addLine($qtdTab.ESP.ESP.'$this->frm->setVo( $vo ,$data ,$param );');
        $this->addLine($qtdTab.ESP.ESP.'$controller = new '.$this->getTableRefClass().'Controller();');
        $this->addLine($qtdTab.ESP.ESP.'$resultado = $controller->save( $vo );');
        $this->addLine($qtdTab.ESP.ESP.'if( is_int($resultado) && $resultado!=0 ) {');
        $this->addLine($qtdTab.ESP.ESP.ESP.'//$text = TFormDinMessage::messageTransform($text); //Tranform Array in Msg Adianti');
        $this->addLine($qtdTab.ESP.ESP.ESP.'$this->onReload();');
        $this->addLine($qtdTab.ESP.ESP.ESP.'$this->frm->addMessage( _t(\'Record saved\') );');
        $this->addLine($qtdTab.ESP.ESP.ESP.'//$this->frm->clearFields();');
        $this->addLine($qtdTab.ESP.ESP.'}else{');
        $this->addLine($qtdTab.ESP.ESP.ESP.'$this->frm->addMessage($resultado);');
        $this->addLine($qtdTab.ESP.ESP.'}');
        $this->addLine($qtdTab.ESP.'}catch (Exception $e){');
        $this->addLine($qtdTab.ESP.ESP.'new TMessage(TFormDinMessage::TYPE_ERROR, $e->getMessage());');
        $this->addLine($qtdTab.ESP.'} //END TryCatch');
        $this->addLine($qtdTab.'} //END onSave');
    }
    //--------------------------------------------------------------------------------------
    public function addMethod_onExecute($qtdTab)
    {
        $this->addBlankLine();
        $this->addLine();
        $this->addLine($qtdTab.'public function onExecute($param)');
        $this->addLine($qtdTab.'{');
        $this->addLine($qtdTab.ESP.'$data = $this->form->getData();');
        $this->addLine($qtdTab.ESP.'//Função do FormDin para Debug');
        $this->addLine($qtdTab.ESP.'FormDinHelper::d($param,\'$param\');');
        $this->addLine($qtdTab.ESP.'FormDinHelper::debug($data,\'$data\');');
        $this->addLine($qtdTab.ESP.'FormDinHelper::debug($_REQUEST,\'$_REQUEST\');');
        $this->addBlankLine();
        $this->addLine($qtdTab.ESP.'try{');
        $this->addLine($qtdTab.ESP.ESP.'$this->form->validate();');        
        $this->addLine($qtdTab.ESP.ESP.'$this->form->setData($data);');
        $this->addLine($qtdTab.ESP.ESP.'$vo = new '.$this->getTableRefVO().'();');
        $this->addLine($qtdTab.ESP.ESP.'$this->frm->setVo( $vo ,$data ,$param );');
        $this->addLine($qtdTab.ESP.ESP.'$controller = new '.$this->getTableRefClass().'Controller();');
        $this->addLine($qtdTab.ESP.ESP.'$resultado = $controller->execProcedure( $vo );');
        $this->addLine($qtdTab.ESP.ESP.'if( is_int($resultado) && $resultado!=0 ) {');
        $this->addLine($qtdTab.ESP.ESP.ESP.'//$text = TFormDinMessage::messageTransform($text); //Tranform Array in Msg Adianti');
        $this->addLine($qtdTab.ESP.ESP.ESP.'$this->frm->addMessage( _t(\'Record saved\') );');
        $this->addLine($qtdTab.ESP.ESP.ESP.'//$this->frm->clearFields();');
        $this->addLine($qtdTab.ESP.ESP.'}else{');
        $this->addLine($qtdTab.ESP.ESP.ESP.'//$this->frm->addMessage($resultado);');
        $this->addLine($qtdTab.ESP.ESP.ESP.'FormDinHelper::debug($resultado,\'$resultado\');');
        $this->addLine($qtdTab.ESP.ESP.'}');
        $this->addLine($qtdTab.ESP.'}catch (Exception $e){');
        $this->addLine($qtdTab.ESP.ESP.'new TMessage(TFormDinMessage::TYPE_ERROR, $e->getMessage());');
        $this->addLine($qtdTab.ESP.'} //END TryCatch');
        $this->addLine($qtdTab.'} //END onSave');
    }    
    //--------------------------------------------------------------------------------------
    public function addMethod_onClose($qtdTab)
    {
        $this->addBlankLine();
        $this->addLine();
        $this->addLine($qtdTab.'/**');
        $this->addLine($qtdTab.' * Close right panel');
        $this->addLine($qtdTab.' */');
        $this->addLine($qtdTab.' /*');
        $this->addLine($qtdTab.'public function onClose()');
        $this->addLine($qtdTab.'{');
        $this->addLine($qtdTab.ESP.'TScript::create("Template.closeRightPanel()");');
        $this->addLine($qtdTab.'} //END onClose');
        $this->addLine($qtdTab.' */');
    }
    //--------------------------------------------------------------------------------------
    protected function addBasicViewController($qtdTab)
    {
        $this->addMethod_onClose($qtdTab);
        $this->addMethod_onClear($qtdTab);
        if ($this->getTableType() == TableInfo::TB_TYPE_TABLE) {
            $this->addMethod_onSave($qtdTab);
        }elseif ($this->getTableType() == TableInfo::TB_TYPE_PROCEDURE) {
            $this->addMethod_onExecute($qtdTab);
        }
        $this->addBlankLine();
    }
    //--------------------------------------------------------------------------------------
    public function addButtons($qtdTab)
    {
        $this->addLine($qtdTab.'//O Adianti permite a Internacionalização - A função _t(\'string\') serve');
        $this->addLine($qtdTab.'//para traduzir termos no sistema. Veja ApplicationTranslator escrevendo');
        $this->addLine($qtdTab.'//primeiro em ingles e depois traduzindo');
        if ($this->getTableType() == TableInfo::TB_TYPE_TABLE) {
            $this->addLine($qtdTab.'$frm->setAction( _t(\'Save\'), \'onSave\', null, \'fa:save\', \'green\' );');
        }elseif( $this->getTableType() == TableInfo::TB_TYPE_PROCEDURE ){
            $this->addLine($qtdTab.'$frm->setAction( _t(\'Execute\'), \'onExecute\', null, \'fa:save\', \'green\' );');
        }
        $this->addLine($qtdTab.'$frm->setActionLink( _t(\'Clear\'), \'onClear\', null, \'fa:eraser\', \'red\');');
        $this->addLine($qtdTab.'$frm->setActionLink( _t(\'Back\'), [\''.$this->getTableRef().'FormList\',\'onReload\'], null, \'fas:arrow-left\', \'#000000\');');
    }
    //--------------------------------------------------------------------------------------
    public function addVbox($qtdTab)
    {
        $this->addLine($qtdTab.'//Creates the page structure using a table');
        $this->addLine($qtdTab.'$formDinBreadCrumb = new TFormDinBreadCrumb(__CLASS__,false);');
        $this->addLine($qtdTab.'$vbox = $formDinBreadCrumb->getAdiantiObj();');
        $this->addLine($qtdTab.'$vbox->add($this->form);');
        $this->addBlankLine();
        $this->addLine($qtdTab.'//<onAfterPageCreation>');
        $this->addLine($qtdTab.'//</onAfterPageCreation>');
        $this->addBlankLine();
        $this->addLine($qtdTab.'//add the table inside the page');
        $this->addLine($qtdTab.'parent::add($vbox);');
    }
    //--------------------------------------------------------------------------------------
    public function show($print = false)
    {
        $this->setLinesArrayBranco();
        $this->addLine('<?php');
        $this->addSysGenHeaderNote();
        $this->addBlankLine();
        $this->addLine("class ".$this->getTableRefClassForm()." extends TPage");
        $this->addLine("{");
        $this->addBlankLine();
        $this->addLine(ESP.'private static $formId =\''.$this->getFormId().'\'; //Form ID');
        $this->addLine(ESP.'private static $primaryKey =\''.$this->getPrimaryKeyTable().'\';');
        $this->addLine(ESP.'protected $form; //Registration form Adianti');
        $this->addLine(ESP.'protected $frm;  //Registration component FormDin 5');
        $this->addLine(ESP.'protected $adianti_target_container;');
        $this->addLine(ESP.'protected $datagrid; //Listing');
        $this->addLine(ESP.'protected $pageNavigation;');
        $this->addBlankLine();
        if( $this->getTableType() == TableInfo::TB_TYPE_TABLE ){
            $this->addLine(ESP.'// trait com onReload, onSearch, onDelete, onClear, onEdit, show');
            $this->addLine(ESP.'use Adianti\Base\AdiantiStandardFormTrait;');
            $this->addLine(ESP.'// trait com onReload, onSearch, onDelete...');
            $this->addLine(ESP.'use Adianti\Base\AdiantiStandardListTrait;');
        }elseif( $this->getTableType() == TableInfo::TB_TYPE_VIEW ){
            $this->addLine(ESP.'// trait com onReload, onSearch, onDelete...');
            $this->addLine(ESP.'use Adianti\Base\AdiantiStandardListTrait;');
        }elseif( $this->getTableType() == TableInfo::TB_TYPE_PROCEDURE ){
            $this->addLine(ESP.'// trait com onReload, onSearch, onDelete, onClear, onEdit, show');
            $this->addLine(ESP.'use Adianti\Base\AdiantiStandardFormTrait;');
        }
        $this->addBlankLine();
        $this->addLine(ESP.'public function __construct($param = null)');
        $this->addLine(ESP.'{');
        $this->addLine(ESP.ESP.'parent::__construct();');
        $this->addLine(ESP.ESP.'$this->setDatabase(\'maindatabase\'); // define the database');
        if( $this->getTableType() != TableInfo::TB_TYPE_PROCEDURE ){
            $this->addLine(ESP.ESP.'$this->setActiveRecord(\''.$this->getTableRef().'\'); // define the Active Record');
            $this->addLine(ESP.ESP.'$this->setDefaultOrder(\''.$this->getPrimaryKeyTable().'\', \'asc\'); // define the default order');
        }
        $this->addLine(ESP.ESP.'if(!empty($param[\'target_container\'])){');
        $this->addLine(ESP.ESP.ESP.'$this->adianti_target_container = $param[\'target_container\'];');
        $this->addLine(ESP.ESP.'}');
        $this->addBlankLine();
        $this->addLine(ESP.ESP.'$this->frm = new TFormDin($this,_t(\'Register\').\' '.$this->getFormTitle().'\',null,null,self::$formId);');
        $this->addLine(ESP.ESP.'$frm = $this->frm;');
        $this->addLine(ESP.ESP.'$frm->enableCSRFProtection(); // Protection cross-site request forgery ');
        $this->addFields(ESP.ESP);
        $this->addBlankLine();
        $this->addButtons(ESP.ESP);
        $this->addBlankLine();
        $this->addLine(ESP.ESP.'$this->form = $frm->show();');
        $this->addLine(ESP.ESP.'$this->form->setData( TSession::getValue(__CLASS__.\'_filter_data\'));');
        $this->addVbox(ESP.ESP);
        $this->addLine(ESP.'}');//FIM construct
        $this->addBasicViewController(ESP);
        $this->addLine("}");//FIM class
        return $this->showContent($print);
    }
}
