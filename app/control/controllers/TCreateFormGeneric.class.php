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

class TCreateFormGeneric extends TCreateFileContent
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
    private $gridTypeFormList;
    private $listColumnsProperties;
    private $tableType = null;
    private $databaseManagementSystem  = null;
    private $dtView;
    private $dtDb;
    
    const FORMDIN_TYPE_DATE = 'DATE';
    const FORMDIN_TYPE_DATETIME = 'DATETIME';
    const FORMDIN_TYPE_TEXT = 'TEXT';
    const FORMDIN_TYPE_NUMBER = 'NUMBER';
    const FORMDIN_TYPE_COLUMN_NAME = 'FORMDIN_TYPE';    
    
    const FORM_FKTYPE_SELECT = 'SELECT';
    const FORM_FKTYPE_AUTOCOMPLETE = 'AUTOCOMPLETE';
    const FORM_FKTYPE_ONSEARCH = 'ONSEARCH';
    const FORM_FKTYPE_AUTOSEARCH = 'AUTOSEARCH';
    const FORM_FKTYPE_SELECTCRUD = 'SELECTCRUD';

    const CHAR_MAX_TEXT_FIELD = 101;

    /**
     * Create file FROM form a table info
     * @param string $pathFolder   - folder path to create file
     * @param string $tableName    - table name
     * @param array $listColumnsProperties
     */
    public function __construct($pathFolder ,$tableName ,$listColumnsProperties)
    {
        $tableName = strtolower($tableName);
        $this->setFormTitle($tableName);
        $this->setTableRef($tableName);
        $this->setFileName(strtolower($tableName).'Form.class.php');
        $this->setFilePath($pathFolder);
        $this->setListColumnsProperties($listColumnsProperties);
        $this->configArrayColumns();
        
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
    public function getTableRefClassForm()
    {
        return $this->tableRefClassForm;
    }
    public function setTableRefClassForm($tableRefClassForm)
    {
        $this->tableRefClassForm = $tableRefClassForm;
    }
    //--------------------------------------------------------------------------------------
    public function getTableRefClass()
    {
        return $this->tableRefClass;
    }
    public function getTableRefVO()
    {
        return $this->tableRefVO;
    }
    public function getTableRef()
    {
        return $this->tableRef;
    }
    public function setTableRef($tableRef)
    {
        $this->tableRef      = strtolower($tableRef);
        $this->tableRefClass = $this->getTableRefCC($tableRef);
        $this->setTableRefClassForm( strtolower($tableRef).'Form' );
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
    public function getListColunnsName()
    {
        return $this->listColumnsName;
    }
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
    public function setGridTypeFormList($gridTypeFormList)
    {
        $gridTypeFormList = ( !empty($gridTypeFormList) ) ?$gridTypeFormList : TableInfo::TP_GRID_FROM_LIST_AD;
        $this->gridTypeFormList = $gridTypeFormList;
    }
    public function getGridTypeFormList()
    {
        return $this->gridTypeFormList;
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
    protected function getColumnsPropertieRequired($key)
    {
        $result = true;
        if (ArrayHelper::has('REQUIRED', $this->listColumnsProperties)) {
            $result = $this->listColumnsProperties['REQUIRED'][$key];
        }
        return strtolower($result);
    }
    //--------------------------------------------------------------------------------------
    protected function getColumnsPropertieDataType($key)
    {
        $result = null;
        if (ArrayHelper::has('DATA_TYPE', $this->listColumnsProperties)) {
            //$result = strtolower($this->listColumnsProperties['DATA_TYPE'][$key]);
            $result = strtoupper($this->listColumnsProperties['DATA_TYPE'][$key]);
        }
        return $result;
    }
    //--------------------------------------------------------------------------------------
    protected function getColumnsPropertieFormDinType($key)
    {
        $result = null;
        if (ArrayHelper::has(self::FORMDIN_TYPE_COLUMN_NAME, $this->listColumnsProperties)) {
            $result = strtoupper($this->listColumnsProperties[self::FORMDIN_TYPE_COLUMN_NAME][$key]);
        }
        return $result;
    }
    //--------------------------------------------------------------------------------------
    protected function addFieldTypeToolTip($qtdTab,$key, $fieldName)
    {
        $COLUMN_COMMENT = null;
        if (ArrayHelper::has('COLUMN_COMMENT', $this->listColumnsProperties)) {
            $COLUMN_COMMENT = $this->listColumnsProperties['COLUMN_COMMENT'][$key];
            if (!empty($COLUMN_COMMENT)) {
                $COLUMN_COMMENT = str_replace("'","",$COLUMN_COMMENT);
                $this->addLine($qtdTab.'//$frm->getLabel(\''.$fieldName.'\')->setToolTip(\''.$COLUMN_COMMENT.'\');');
            }
        }
    }
    //--------------------------------------------------------------------------------------
    protected function getColumnsPropertieCharMax($key)
    {
        $result = null;
        if (ArrayHelper::has('CHAR_MAX', $this->listColumnsProperties)) {
            $result = $this->listColumnsProperties['CHAR_MAX'][$key];
        }
        $result = empty($result) ? 50 : $result;
        return $result;
    }
    //--------------------------------------------------------------------------------------
    protected function getColumnsPropertieNumLength($key)
    {
        $result = null;
        if (ArrayHelper::has('NUM_LENGTH', $this->listColumnsProperties)) {
            $result = $this->listColumnsProperties['NUM_LENGTH'][$key];
        }
        $result = empty($result) ? 4 : $result;
        return $result;
    }
    //--------------------------------------------------------------------------------------
    protected function getColumnsPropertieNumScale($key)
    {
        $result = null;
        if (ArrayHelper::has('NUM_SCALE', $this->listColumnsProperties)) {
            $result = $this->listColumnsProperties['NUM_SCALE'][$key];
        }
        $result = empty($result) ? 0 : $result;
        return $result;
    }
    //--------------------------------------------------------------------------------------
    protected function getColumnsPropertieKeyType($key)
    {
        $result = null;
        if (ArrayHelper::has('KEY_TYPE', $this->listColumnsProperties)) {
            $result = $this->listColumnsProperties['KEY_TYPE'][$key];
        }
        $result = empty($result) ? false : $result;
        return $result;
    }
    //--------------------------------------------------------------------------------------
    protected function getColumnsPropertieReferencedTable($key)
    {
        $result = null;
        if (ArrayHelper::has('REFERENCED_TABLE_NAME', $this->listColumnsProperties)) {
            $result = $this->listColumnsProperties['REFERENCED_TABLE_NAME'][$key];
        }
        $result = empty($result) ? false : $result;
        return $result;
    }
    //--------------------------------------------------------------------------------------
    protected function getFkTypeScreenReferenced($key)
    {        
        $result = null;
        if (ArrayHelper::has(TableInfo::FK_TYPE_SCREEN_REFERENCED, $this->listColumnsProperties)) {
            $result = $this->listColumnsProperties[TableInfo::FK_TYPE_SCREEN_REFERENCED][$key];
        }
        $result = empty($result) ? false : $result;
        return $result;
    }
    //--------------------------------------------------------------------------------------
    protected function addFieldNumber($qtdTab,$key, $fieldName, $REQUIRED)
    {
        $NUM_LENGTH = $this->getColumnsPropertieNumLength($key);
        $NUM_SCALE  = $this->getColumnsPropertieNumScale($key);
        $fieldLabel = EasyLabel::convertLabel($fieldName, self::FORMDIN_TYPE_NUMBER);
        
        $this->addLine($qtdTab.'$frm->addNumberField(\''.$fieldName.'\', \''.$fieldLabel.'\','.$NUM_LENGTH.','.$REQUIRED.','.$NUM_SCALE.');');
        $this->addFieldTypeToolTip($qtdTab,$key, $fieldName);
    }
    //--------------------------------------------------------------------------------------
    protected function addFieldForenAutoComplete($key, $fieldName, $REQUIRED)
    {
        $NUM_LENGTH = $this->getColumnsPropertieNumLength($key);
        $REFERENCED_TABLE_NAME = $this->getColumnsPropertieReferencedTable($key);
        $mixUpDatefields = $fieldName.'|'.$fieldName.','.$fieldName.'TEXT|'.$fieldName.'TEXT';
            
        $this->addLine('$frm->addGroupField(\'gpx1'.$fieldName.'\',\''.$fieldName.'\');');
        $this->addLine(ESP.'$frm->addNumberField(\''.$fieldName.'\',\''.$fieldName.'\','.$NUM_LENGTH.',true,0);');
        $this->addBlankLine();
        $this->addLine(ESP.'//ALTERE O CAMPO '.$fieldName.'TEXT para o nome correto da tabela ou view');
        $this->addLine(ESP.'$frm->addTextField(\''.$fieldName.'TEXT\',\''.$fieldName.'TEXT\',150,true,70,null,false);');
        $this->addLine(ESP.'//setAutoComplete SEMPRE deve ficar depois da definição dos campos de pesquisa e que serão carregados'); 
        $this->addLine(ESP.'$frm->setAutoComplete(\''.$fieldName.'TEXT\'  // 1: nome do campo na tela que será feita a pesquisa');
        $this->addLine(ESP.ESP.ESP.ESP.ESP.ESP.',\''.$REFERENCED_TABLE_NAME.'\' // Tabela ou View que é a fonte da pesquisa');
        $this->addLine(ESP.ESP.ESP.ESP.ESP.ESP.',\''.$fieldName.'TEXT\'	 		// campo de pesquisa');
        $this->addLine(ESP.ESP.ESP.ESP.ESP.ESP.',\''.$mixUpDatefields.'\' // 4: campos que serão atualizados ao selecionar o texto <campo_tabela> | <campo_formulario>');
        $this->addLine(ESP.ESP.ESP.ESP.ESP.ESP.',true'); 
        $this->addLine(ESP.ESP.ESP.ESP.ESP.ESP.',null 		        // 6: campo do formulário que será adicionado como filtro');
        $this->addLine(ESP.ESP.ESP.ESP.ESP.ESP.',null				// 7: função javascript de callback');
        $this->addLine(ESP.ESP.ESP.ESP.ESP.ESP.',3					// 8: Default 3, numero de caracteres minimos para disparar a pesquisa');
        $this->addLine(ESP.ESP.ESP.ESP.ESP.ESP.',500				// 9: Default 1000, tempo após a digitação para disparar a consulta');
        $this->addLine(ESP.ESP.ESP.ESP.ESP.ESP.',50					//10: máximo de registros que deverá ser retornado');
        $this->addLine(ESP.ESP.ESP.ESP.ESP.ESP.', null, null, null, null');
        $this->addLine(ESP.ESP.ESP.ESP.ESP.ESP.', true, null, null, true');
        $this->addLine(ESP.ESP.ESP.ESP.ESP.ESP.');');
        $this->addLine('$frm->closeGroup();');
    }
    //--------------------------------------------------------------------------------------
    protected function addFieldForenKeySelectField($qtdTab,$key, $fieldName, $REQUIRED)
    {
        $REFERENCED_TABLE_NAME = $this->getColumnsPropertieReferencedTable($key);
        $REFERENCED_TABLE_NAME = $this->getTableRefCC($REFERENCED_TABLE_NAME);
        
        $this->addLine($qtdTab.'$controller'.$REFERENCED_TABLE_NAME.' = new '.$REFERENCED_TABLE_NAME.'Controller();');
        $this->addLine($qtdTab.'$list'.$REFERENCED_TABLE_NAME.' = $controller'.$REFERENCED_TABLE_NAME.'->selectAll();');
        $fieldLabel = EasyLabel::convertLabel($fieldName, self::FORMDIN_TYPE_NUMBER);
        $this->addLine($qtdTab.'$'.$fieldName.' = $frm->addSelectField(\''.$fieldName.'\', \''.$fieldLabel.'\','.$REQUIRED.',$list'.$REFERENCED_TABLE_NAME.',null,null,null,null,null,null,\' \',null);');
        $this->addLine($qtdTab.'$'.$fieldName.'->enableSearch();');
        $this->addFieldTypeToolTip($qtdTab,$key, $fieldName);
    }
    //--------------------------------------------------------------------------------------
    protected function addFieldNumberOrForeignKey($qtdTab,$key, $fieldName, $REQUIRED)
    {        
        $KEY_TYPE   = $this->getColumnsPropertieKeyType($key);
        if ($KEY_TYPE != TableInfo::KEY_TYPE_FK) {
            $this->addFieldNumber($qtdTab,$key, $fieldName, $REQUIRED);
        } else {
            $fkTypeScreenReferenced = $this->getFkTypeScreenReferenced($key);
            switch ($fkTypeScreenReferenced) {
                case self::FORM_FKTYPE_AUTOCOMPLETE:
                    $this->addFieldForenAutoComplete($key, $fieldName, $REQUIRED);
                break;
                default:
                    $this->addFieldForenKeySelectField($qtdTab,$key, $fieldName, $REQUIRED);
            }
        }
    }
    //--------------------------------------------------------------------------------------
    protected function addFieldType($qtdTab,$key, $fieldName, $notPK = true)
    {
        /**
         * Esse ajuste do $key acontece em função do setListColunnsName descarta o primeiro
         * registro que assume ser a chave primaria.
         */
        if($notPK){
            $key = $key+1;
        }
        $CHAR_MAX    = $this->getColumnsPropertieCharMax($key);
        $REQUIRED    = $this->getColumnsPropertieRequired($key);
        //$DATA_TYPE   = self::getColumnsPropertieDataType($key);
        $formDinType = $this->getColumnsPropertieFormDinType($key);
        $fieldName   = strtoupper($fieldName);

        switch ($formDinType) {
            case self::FORMDIN_TYPE_DATE:
                $fieldLabel = EasyLabel::convertLabel($fieldName, $formDinType);
                $this->addLine($qtdTab.'$frm->addDateField(\''.$fieldName.'\', \''.$fieldLabel.'\','.$REQUIRED.',null,null,null,null,\''.$this->getDtView().'\',null,null,null,null,\''.$this->getDtDb().'\');');
                $this->addFieldTypeToolTip($qtdTab,$key, $fieldName);
            break;
            case self::FORMDIN_TYPE_DATETIME:
                $fieldLabel = EasyLabel::convertLabel($fieldName, $formDinType);
                $this->addLine($qtdTab.'$frm->addDateTimeField(\''.$fieldName.'\', \''.$fieldLabel.'\','.$REQUIRED.',null,null,null,null,\''.$this->getDtView().' hh:ii\',null,null,null,null,\''.$this->getDtDb().' hh:ii\');');
                $this->addFieldTypeToolTip($qtdTab,$key, $fieldName);
            break;            
            case self::FORMDIN_TYPE_NUMBER:
                $this->addFieldNumberOrForeignKey($qtdTab,$key, $fieldName, $REQUIRED);
            break;
            default:
                $fieldLabel = EasyLabel::convertLabel($fieldName, $formDinType);
                if ($CHAR_MAX < self::CHAR_MAX_TEXT_FIELD) {
                    $this->addLine($qtdTab.'$frm->addTextField(\''.$fieldName.'\', \''.$fieldLabel.'\','.$CHAR_MAX.','.$REQUIRED.','.$CHAR_MAX.');');
                } else {
                    $this->addLine($qtdTab.'$frm->addMemoField(\''.$fieldName.'\', \''.$fieldLabel.'\','.$CHAR_MAX.','.$REQUIRED.',80,3);');
                }
                $this->addFieldTypeToolTip($qtdTab,$key, $fieldName);
        }
    }
    
    //--------------------------------------------------------------------------------------
    protected function addFields($qtdTab)
    {
        if( $this->getTableType() != TableInfo::TB_TYPE_PROCEDURE ){            
            $this->addLine($qtdTab.'$frm->addHiddenField( $primaryKey );   // coluna chave da tabela');
        }else{
            $this->addFieldType($qtdTab,0, $this->getPrimaryKeyTable(),false);
        }
        if ($this->validateListColumnsName()) {
            foreach ($this->listColumnsName as $key => $value) {
                $this->addFieldType($qtdTab,$key, $value);
            }
        }
    }
    //--------------------------------------------------------------------------------------
    protected function addBasicViewController_logCatch($qtdTab)
    {
        $logType = TSysgenSession::getValue('logType');
        $logType = empty($logType)?2:$logType;;
        if ($logType == 2) {
            $this->addLine($qtdTab.'catch (DomainException $e) {');
            $this->addLine($qtdTab.ESP.'$frm->addMessage( $e->getMessage() ); //addMessage evita o problema do setMessage');
            $this->addLine($qtdTab.'}');
        }
        $this->addLine($qtdTab.'catch (Exception $e) {');
        if (($logType == 1) || ($logType == 2)) {
            $this->addLine($qtdTab.ESP.'MessageHelper::logRecord($e);');
        }
        $this->addLine($qtdTab.ESP.'$frm->addMessage( $e->getMessage() ); //addMessage evita o problema do setMessage');
        $this->addLine($qtdTab.'}');
    }
}
