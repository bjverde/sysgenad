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

class TCreateFormList extends TCreateFormGeneric
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
        $this->setFileName(strtolower($tableName).'FormList.class.php');
        $this->setTableRefClassForm( strtolower($tableName).'FormList' );
    }
    //--------------------------------------------------------------------------------------
    public function getMixUpdateFields($qtdTab)
    {
        if ($this->validateListColumnsName()) {
            $this->addLine($qtdTab.'$mixUpdateFields = $primaryKey.\'|\'.$primaryKey');
            $listColumnsName = $this->getListColunnsName();
            foreach ($listColumnsName as $value) {
                $value   = strtoupper($value);
                $this->addLine($qtdTab.ESP.ESP.ESP.ESP.'.\','.$value.'|'.$value.'\'');
            }
            $this->addLine($qtdTab.ESP.ESP.ESP.ESP.';');
        }
    }
    //--------------------------------------------------------------------------------------
    public function addColumnsGrid($qtdTab)
    {
        //$this->addLine($qtdTab.'$grid->addRowNumColumn(); //Mostra Numero da linha');
        $this->addLine($qtdTab.'$grid->addColumn($primaryKey,\'id\');');
        if ($this->validateListColumnsName()) {
            $listColumnsName = $this->getListColunnsName();
            foreach ($listColumnsName as $key => $value) {
                /**
                 * Esse ajuste do $key acontece em função do setListColunnsName descarta o primeiro
                 * registro que assume ser a chave primaria.
                 */
                $keyColumns = $key+1;
                $formDinType = $this->getColumnsPropertieFormDinType($keyColumns);               
                $fieldLabel = EasyLabel::convertLabel($value, $formDinType);
                $value      = strtoupper($value);

                switch ($formDinType) {
                    case self::FORMDIN_TYPE_DATE:
                        $this->addLine($qtdTab.'$grid->addColumnFormatDate(\''.$value.'\',\''.$fieldLabel.'\',null,\'left\',\''.$this->getDtView().'\');');
                    break;
                    case self::FORMDIN_TYPE_DATETIME:
                        $this->addLine($qtdTab.'$grid->addColumnFormatDate(\''.$value.'\',\''.$fieldLabel.'\',null,\'left\',\''.$this->getDtView().' hh:ii\');');
                    break;
                    default:
                        $this->addLine($qtdTab.'$grid->addColumn(\''.$value.'\',\''.$fieldLabel.'\');');
                }
            }//end foreach
        }//end if
    }
    //--------------------------------------------------------------------------------------
    public function addGetWhereGridParameters_fied($primeira, $campo, $qtdTabs)
    {
        if ($primeira == true) {
            $this->addLine($qtdTabs.'\''.$campo.'\'=>$frm->get(\''.$campo.'\')');
        } else {
            $this->addLine($qtdTabs.',\''.$campo.'\'=>$frm->get(\''.$campo.'\')');
        }
    }
    //--------------------------------------------------------------------------------------
    public function addGetWhereGridParametersFields($qtdTabs)
    {
        $listColumnsName = $this->getListColunnsName();
        foreach ($listColumnsName as $value) {
            $this->addGetWhereGridParameters_fied(false, $value, $qtdTabs);
        }
    }
    //--------------------------------------------------------------------------------------
    public function addGrid($qtdTab)
    {   
        if( $this->getTableType() != TableInfo::TB_TYPE_PROCEDURE ){
            $this->addBlankLine();
            $this->getMixUpdateFields($qtdTab);
            $this->addLine($qtdTab.'$grid = new TFormDinGrid($this,\'gd\',\'Data Grid\');');
            $this->addLine($qtdTab.'$grid->setUpdateFields($mixUpdateFields);');
            $this->addColumnsGrid($qtdTab);
            if( $this->getTableType() == TableInfo::TB_TYPE_VIEW ){
                $this->addLine($qtdTab.'$grid->enableDefaultButtons(false); //Disable Grid Action Edit e Delete');
            }
            $this->addBlankLine();
            $this->addLine($qtdTab.'$this->datagrid = $grid->show();');
            $this->addLine($qtdTab.'$this->pageNavigation = $grid->getPageNavigation();');
            $this->addLine($qtdTab.'$panelGroupGrid = $grid->getPanelGroupGrid();');
            $this->addBlankLine();
            $this->addBlankLine();
        }
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
    public function addMethod_onClear($qtdTab)
    {
        $this->addBlankLine();
        $this->addLine();
        $this->addLine($qtdTab.'/**');
        $this->addLine($qtdTab.' * Clear filters');
        $this->addLine($qtdTab.' */');
        $this->addLine($qtdTab.'public function onClear()');
        $this->addLine($qtdTab.'{');
        $this->addLine($qtdTab.ESP.'$this->clearFilters();');
        $this->addLine($qtdTab.ESP.'$this->onReload();');
        $this->addLine($qtdTab.'} //END onClear');
    }
    //--------------------------------------------------------------------------------------
    protected function addBasicViewController($qtdTab)
    {

        $this->addMethod_onClose($qtdTab);
        if( $this->getTableType() == TableInfo::TB_TYPE_VIEW ){
            $this->addMethod_onClear($qtdTab);
        }elseif ($this->getTableType() == TableInfo::TB_TYPE_PROCEDURE) {
            $this->addMethod_onExecute($qtdTab);
        }
        $this->addBlankLine();
    }        
    //--------------------------------------------------------------------------------------
    public function addButtons($qtdTab)
    {
        $this->addLine($qtdTab.'// O Adianti permite a Internacionalização - A função _t(\'string\') serve');
        $this->addLine($qtdTab.'//para traduzir termos no sistema. Veja ApplicationTranslator escrevendo');
        $this->addLine($qtdTab.'//primeiro em ingles e depois traduzindo');
        if ($this->getTableType() == TableInfo::TB_TYPE_TABLE) {
            $this->addLine($qtdTab.'$frm->setAction( _t(\'Save\'), \'onSave\', null, \'fa:save\', \'green\' );');
        }elseif( $this->getTableType() == TableInfo::TB_TYPE_PROCEDURE ){
            $this->addLine($qtdTab.'$frm->setAction( _t(\'Execute\'), \'onExecute\', null, \'fa:save\', \'green\' );');
        }
        $this->addLine($qtdTab.'$frm->setActionLink( _t(\'Clear\'), \'onClear\', null, \'fa:eraser\', \'red\');');
    }
    //--------------------------------------------------------------------------------------
    public function addVbox($qtdTab)
    {
        $this->addLine($qtdTab.'// creates the page structure using a table');
        $this->addLine($qtdTab.'$formDinBreadCrumb = new TFormDinBreadCrumb(__CLASS__);');
        $this->addLine($qtdTab.'$vbox = $formDinBreadCrumb->getAdiantiObj();');
        $this->addLine($qtdTab.'$vbox->add($this->form);');
        if( $this->getTableType() != TableInfo::TB_TYPE_PROCEDURE ){
            $this->addLine($qtdTab.'$vbox->add($panelGroupGrid);');
        }        
        $this->addBlankLine();
        $this->addLine($qtdTab.'// add the table inside the page');
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
        $this->addBlankLine();
        $this->addLine(ESP.ESP.'$this->setDatabase(\'maindatabase\'); // define the database');
        if( $this->getTableType() != TableInfo::TB_TYPE_PROCEDURE ){
            $this->addLine(ESP.ESP.'$this->setActiveRecord(\''.$this->getTableRef().'\'); // define the Active Record');
            $this->addLine(ESP.ESP.'$this->setDefaultOrder(\''.$this->getPrimaryKeyTable().'\', \'asc\'); // define the default order');
            $this->addBlankLine();
            $this->addLine(ESP.ESP.'$primaryKey = \''.$this->getPrimaryKeyTable().'\';');
        }
        $this->addLine(ESP.ESP.'if(!empty($param[\'target_container\'])){');
        $this->addLine(ESP.ESP.ESP.'$this->adianti_target_container = $param[\'target_container\'];');
        $this->addLine(ESP.ESP.'}');
        $this->addBlankLine();
        $this->addLine(ESP.ESP.'$this->frm = new TFormDin($this,\''.$this->getFormTitle().'\',null,null,self::$formId);');
        $this->addLine(ESP.ESP.'$frm = $this->frm;');
        $this->addLine(ESP.ESP.'$frm->enableCSRFProtection(); // Protection cross-site request forgery ');
        $this->addFields(ESP.ESP);
        $this->addBlankLine();
        $this->addButtons(ESP.ESP);
        $this->addBlankLine();        
        $this->addLine(ESP.ESP.'$this->form = $frm->show();');
        $this->addLine(ESP.ESP.'$this->form->setData( TSession::getValue(__CLASS__.\'_filter_data\'));');
        $this->addGrid(ESP.ESP);
        $this->addVbox(ESP.ESP);
        $this->addLine(ESP.'}');//FIM construct
        $this->addBasicViewController(ESP);
        $this->addLine("}");//FIM class
        return $this->showContent($print);
    }
}
