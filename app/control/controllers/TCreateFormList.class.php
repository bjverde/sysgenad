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
    private $formId;
    private $formTitle;
    private $primaryKeyTable;
    private $tableRef;
    private $tableRefClass;
    private $tableRefClassForm;
    private $tableRefDAO;
    private $tableRefVO;
    private $listColumnsName;
    private $lines;
    private $gridType;
    private $listColumnsProperties;
    private $tableType = null;
    private $databaseManagementSystem  = null;
    private $dtView;
    private $dtDb;
    
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
    public function setFormTitle($formTitle)
    {
        $formTitle = ( !empty($formTitle) ) ? $formTitle : "titulo";
        $this->formTitle    = $formTitle;
    }
    //--------------------------------------------------------------------------------------
    public function getFormTitle()
    {
        return $this->formTitle;
    }
    //--------------------------------------------------------------------------------------
    public function getFormFileName()
    {
        return $this->getFileName();
    }
    //--------------------------------------------------------------------------------------
    public function setPrimaryKeyTable($primaryKeyTable)
    {
        $primaryKeyTable = ( !empty($primaryKeyTable) ) ?$primaryKeyTable : "id";
        $this->primaryKeyTable    = $primaryKeyTable;
    }
    //--------------------------------------------------------------------------------------
    public function getPrimaryKeyTable()
    {
        return strtoupper($this->primaryKeyTable);
    }
    //--------------------------------------------------------------------------------------
    public function getTableRefCC($tableRef)
    {
        $tableRef = strtolower($tableRef);
        return ucfirst($tableRef);
    }
    //--------------------------------------------------------------------------------------
    public function getTableRefClass()
    {
        return $this->tableRefClass;
    }     
    public function getTableRefClassForm()
    {
        return $this->tableRefClassForm;
    }    
    public function setTableRef($tableRef)
    {
        $this->tableRef      = strtolower($tableRef);
        $this->tableRefClass = $this->getTableRefCC($tableRef);
        $this->tableRefClassForm = strtolower($tableRef).'Form';
        $this->tableRefDAO   = $this->getTableRefCC($tableRef).'DAO';
        $this->tableRefVO    = $this->getTableRefCC($tableRef).'VO';
    }
    //--------------------------------------------------------------------------------------
    public function getFormId()
    {
        $this->formId    = 'form_'.$this->getTableRefClassForm();
        return $this->formId;
    }
    //--------------------------------------------------------------------------------------
    public function setListColunnsName($listColumnsName)
    {
        array_shift($listColumnsName);
        //$this->listColumnsName = array_map('strtoupper', $listColumnsName); //FormDin4
        $this->listColumnsName = $listColumnsName; //FormDin5, Adianti é case sensitive
    }
    //--------------------------------------------------------------------------------------
    public function validateListColumnsName()
    {
        return isset($this->listColumnsName) && !empty($this->listColumnsName);
    }
    //--------------------------------------------------------------------------------------
    public function setGridType($gridType)
    {
        $gridType = ( !empty($gridType) ) ?$gridType : FormDinHelper::GRID_SIMPLE;
        $this->gridType = $gridType;
    }
    public function getGridType()
    {
        return $this->gridType;
    }
    //--------------------------------------------------------------------------------------
    public function setListColumnsProperties($listColumnsProperties)
    {
        TGeneratorHelper::validateListColumnsProperties($listColumnsProperties);
        $this->listColumnsProperties = $listColumnsProperties;
    }
    public function getListColumnsProperties()
    {
        return $this->listColumnsProperties;
    }
    //--------------------------------------------------------------------------------------
    protected function configArrayColumns()
    {
        $listColumnsProperties = $this->getListColumnsProperties();
        $listColumns = $listColumnsProperties['COLUMN_NAME'];
        $columnPrimaryKey = $listColumns[0];
        $this->setListColunnsName($listColumns);
        $this->setPrimaryKeyTable($columnPrimaryKey);
    }
    //------------------------------------------------------------------------------------
    public function setTableType($tableType)
    {
        $this->tableType = $tableType;
    }
    public function getTableType()
    {
        return $this->tableType;
    }
    //------------------------------------------------------------------------------------
    public function setDatabaseManagementSystem($databaseManagementSystem)
    {
        return $this->databaseManagementSystem = $databaseManagementSystem;
    }
    public function getDatabaseManagementSystem()
    {
        return $this->databaseManagementSystem;
    }
    //------------------------------------------------------------------------------------
    public function setDtView($dtView)
    {
        return $this->dtView = $dtView;
    }
    public function getDtView()
    {
        $dtView = empty($this->dtView)?Message::MASK_DT_BR:$this->dtView;
        return $dtView;
    }
    //------------------------------------------------------------------------------------
    public function setDtDb($dtDb)
    {
        return $this->dtDb = $dtDb;
    }
    public function getDtDb()
    {
        $dtView = empty($this->dtDb)?Message::MASK_DT_ISO:$this->dtDb;
        return $dtView;
    }    
    //--------------------------------------------------------------------------------------
    /***
     * Create variable with string sql basica
     **/
    public static function convertDataType2FormDinType($dataType,$DBMS_TYPE=null)
    {
        $dataType = strtoupper($dataType);
        $result = 'TEXT';
        switch ($dataType) {
            case 'DATETIME':
                if($DBMS_TYPE == TFormDinPdoConnection::DBMS_MYSQL){
                    $result = self::FORMDIN_TYPE_DATETIME;
                }else{
                    $result = self::FORMDIN_TYPE_DATE;
                }
            break;
            case 'DATETIME2':
            case 'DATE':
            case 'TIMESTAMP':
                if($DBMS_TYPE == TFormDinPdoConnection::DBMS_MYSQL){
                    $result = self::FORMDIN_TYPE_DATETIME;
                }else{
                    $result = self::FORMDIN_TYPE_DATE;
                }
            break;
            case 'BIGINT':
            case 'DECIMAL':
            case 'DOUBLE':
            case 'FLOAT':
            case 'INT':
            case 'INT64':
            case 'INTEGER':
            case 'NUMERIC':
            case 'NUMBER':
            case 'REAL':
            case 'SMALLINT':
            case 'TINYINT':
                //case preg_match( '/decimal|real|float|numeric|number|int|int64|integer|double|smallint|bigint|tinyint/i', $DATA_TYPE ):
                $result = self::FORMDIN_TYPE_NUMBER;
            break;
            default:
                $result = self::FORMDIN_TYPE_TEXT;
        }
        return $result;
    }
    //--------------------------------------------------------------------------------------
    public function getMixUpdateFields($qtdTab)
    {
        if ($this->validateListColumnsName()) {
            $this->addLine($qtdTab.'$mixUpdateFields = $primaryKey.\'|\'.$primaryKey');
            foreach ($this->listColumnsName as $value) {
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
            foreach ($this->listColumnsName as $key => $value) {
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
        foreach ($this->listColumnsName as $value) {
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
        $this->addLine($qtdTab.ESP.ESP.'$vo = new '.$this->tableRefVO.'();');
        $this->addLine($qtdTab.ESP.ESP.'$this->frm->setVo( $vo ,$data ,$param );');
        $this->addLine($qtdTab.ESP.ESP.'$controller = new '.$this->tableRefClass.'Controller();');
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
            $this->addLine(ESP.ESP.'$this->setActiveRecord(\''.$this->tableRef.'\'); // define the Active Record');
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
