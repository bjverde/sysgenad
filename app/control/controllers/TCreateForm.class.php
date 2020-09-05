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

class TCreateForm extends TCreateFileContent
{
    private $formTitle;
    private $primaryKeyTable;
    private $tableRef;
    private $tableRefClass;
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
        return $this->primaryKeyTable;
    }
    //--------------------------------------------------------------------------------------
    public function getTableRefCC($tableRef)
    {
        $tableRef = strtolower($tableRef);
        return ucfirst($tableRef);
    }
    //--------------------------------------------------------------------------------------
    public function setTableRef($tableRef)
    {
        $this->tableRef      = strtolower($tableRef);
        $this->tableRefClass = $this->getTableRefCC($tableRef);
        $this->tableRefDAO   = $this->getTableRefCC($tableRef).'DAO';
        $this->tableRefVO    = $this->getTableRefCC($tableRef).'VO';
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
        return $this->databaseManagementSystem = strtoupper($databaseManagementSystem);
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
    public function setDtDb($dtBd)
    {
        return $this->dtBd = $dtBd;
    }
    public function getDtDb()
    {
        $dtView = empty($this->dtBd)?Message::MASK_DT_ISO:$this->dtBd;
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
    private function getColumnsPropertieRequired($key)
    {
        $result = true;
        if (ArrayHelper::has('REQUIRED', $this->listColumnsProperties)) {
            $result = $this->listColumnsProperties['REQUIRED'][$key];
        }
        return strtolower($result);
    }
    //--------------------------------------------------------------------------------------
    private function getColumnsPropertieDataType($key)
    {
        $result = null;
        if (ArrayHelper::has('DATA_TYPE', $this->listColumnsProperties)) {
            //$result = strtolower($this->listColumnsProperties['DATA_TYPE'][$key]);
            $result = strtoupper($this->listColumnsProperties['DATA_TYPE'][$key]);
        }
        return $result;
    }
    //--------------------------------------------------------------------------------------
    private function getColumnsPropertieFormDinType($key)
    {
        $result = null;
        if (ArrayHelper::has(self::FORMDIN_TYPE_COLUMN_NAME, $this->listColumnsProperties)) {
            $result = strtoupper($this->listColumnsProperties[self::FORMDIN_TYPE_COLUMN_NAME][$key]);
        }
        return $result;
    }
    //--------------------------------------------------------------------------------------
    private function addFieldTypeToolTip($qtdTab,$key, $fieldName)
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
    private function getColumnsPropertieCharMax($key)
    {
        $result = null;
        if (ArrayHelper::has('CHAR_MAX', $this->listColumnsProperties)) {
            $result = $this->listColumnsProperties['CHAR_MAX'][$key];
        }
        $result = empty($result) ? 50 : $result;
        return $result;
    }
    //--------------------------------------------------------------------------------------
    private function getColumnsPropertieNumLength($key)
    {
        $result = null;
        if (ArrayHelper::has('NUM_LENGTH', $this->listColumnsProperties)) {
            $result = $this->listColumnsProperties['NUM_LENGTH'][$key];
        }
        $result = empty($result) ? 4 : $result;
        return $result;
    }
    //--------------------------------------------------------------------------------------
    private function getColumnsPropertieNumScale($key)
    {
        $result = null;
        if (ArrayHelper::has('NUM_SCALE', $this->listColumnsProperties)) {
            $result = $this->listColumnsProperties['NUM_SCALE'][$key];
        }
        $result = empty($result) ? 0 : $result;
        return $result;
    }
    //--------------------------------------------------------------------------------------
    private function getColumnsPropertieKeyType($key)
    {
        $result = null;
        if (ArrayHelper::has('KEY_TYPE', $this->listColumnsProperties)) {
            $result = $this->listColumnsProperties['KEY_TYPE'][$key];
        }
        $result = empty($result) ? false : $result;
        return $result;
    }
    //--------------------------------------------------------------------------------------
    private function getColumnsPropertieReferencedTable($key)
    {
        $result = null;
        if (ArrayHelper::has('REFERENCED_TABLE_NAME', $this->listColumnsProperties)) {
            $result = $this->listColumnsProperties['REFERENCED_TABLE_NAME'][$key];
        }
        $result = empty($result) ? false : $result;
        return $result;
    }
    //--------------------------------------------------------------------------------------
    private function getFkTypeScreenReferenced($key)
    {        
        $result = null;
        if (ArrayHelper::has(TableInfo::FK_TYPE_SCREEN_REFERENCED, $this->listColumnsProperties)) {
            $result = $this->listColumnsProperties[TableInfo::FK_TYPE_SCREEN_REFERENCED][$key];
        }
        $result = empty($result) ? false : $result;
        return $result;
    }
    //--------------------------------------------------------------------------------------
    private function addFieldNumber($qtdTab,$key, $fieldName, $REQUIRED)
    {
        $NUM_LENGTH = $this->getColumnsPropertieNumLength($key);
        $NUM_SCALE  = $this->getColumnsPropertieNumScale($key);
        $fieldLabel = EasyLabel::convertLabel($fieldName, self::FORMDIN_TYPE_NUMBER);
        
        $this->addLine($qtdTab.'$frm->addNumberField(\''.$fieldName.'\', \''.$fieldLabel.'\','.$NUM_LENGTH.','.$REQUIRED.','.$NUM_SCALE.');');
        $this->addFieldTypeToolTip($qtdTab,$key, $fieldName);
    }
    //--------------------------------------------------------------------------------------
    private function addFieldForenAutoComplete($key, $fieldName, $REQUIRED)
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
    private function addFieldForenKeySelectField($qtdTab,$key, $fieldName, $REQUIRED)
    {
        $REFERENCED_TABLE_NAME = $this->getColumnsPropertieReferencedTable($key);
        $REFERENCED_TABLE_NAME = $this->getTableRefCC($REFERENCED_TABLE_NAME);
        
        $this->addLine('$controller'.$REFERENCED_TABLE_NAME.' = new '.$REFERENCED_TABLE_NAME.'();');
        $this->addLine('$list'.$REFERENCED_TABLE_NAME.' = $controller'.$REFERENCED_TABLE_NAME.'->selectAll();');
        $fieldLabel = EasyLabel::convertLabel($fieldName, self::FORMDIN_TYPE_NUMBER);
        $this->addLine('$frm->addSelectField(\''.$fieldName.'\', \''.$fieldLabel.'\','.$REQUIRED.',$list'.$REFERENCED_TABLE_NAME.',null,null,null,null,null,null,\' \',null);');
        $this->addFieldTypeToolTip($qtdTab,$key, $fieldName);
    }
    //--------------------------------------------------------------------------------------
    private function addFieldNumberOrForeignKey($qtdTab,$key, $fieldName, $REQUIRED)
    {        
        $KEY_TYPE   = $this->getColumnsPropertieKeyType($key);
        if ($KEY_TYPE != TableInfo::KEY_TYPE_FK) {
            $this->addFieldNumber($qtdTab,$key, $fieldName, $REQUIRED);
        } else {
            $this->addFieldNumber($qtdTab,$key, $fieldName, $REQUIRED);
            /*
            $fkTypeScreenReferenced = $this->getFkTypeScreenReferenced($key);
            switch ($fkTypeScreenReferenced) {
                case self::FORM_FKTYPE_AUTOCOMPLETE:
                    $this->addFieldForenAutoComplete($key, $fieldName, $REQUIRED);
                break;
                default:
                    $this->addFieldForenKeySelectField($qtdTab,$key, $fieldName, $REQUIRED);
            }
            */
        }
    }
    //--------------------------------------------------------------------------------------
    private function addFieldType($qtdTab,$key, $fieldName, $notPK = true)
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

        switch ($formDinType) {
            case self::FORMDIN_TYPE_DATE:
                $fieldLabel = EasyLabel::convertLabel($fieldName, $formDinType);
                $this->addLine($qtdTab.'$frm->addDateField(\''.$fieldName.'\', \''.$fieldLabel.'\','.$REQUIRED.',null,null,null,null,\''.$this->getDtView().'\',null,null,null,null,\''.$this->getDtDb().'\');');
                $this->addFieldTypeToolTip($qtdTab,$key, $fieldName);
            break;
            case self::FORMDIN_TYPE_DATETIME:
                $fieldLabel = EasyLabel::convertLabel($fieldName, $formDinType);
                $this->addLine($qtdTab.'$frm->addDateTimeField(\''.$fieldName.'\', \''.$fieldLabel.'\','.$REQUIRED.',null,null,null,null,\''.$this->getDtView().'\',null,null,null,null,\''.$this->getDtDb().'\');');
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
    private function addFields($qtdTab)
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
    private function addBasicViewController_logCatch($qtdTab)
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
    //--------------------------------------------------------------------------------------
    private function addBasicaViewController_salvar()
    {
        $this->addLine();
        $this->addLine(ESP.'case \'Salvar\':');
        $this->addLine(ESP.ESP.'try{');
        $this->addLine(ESP.ESP.ESP.'if ( $frm->validate() ) {');
        $this->addLine(ESP.ESP.ESP.ESP.'$vo = new '.$this->tableRefVO.'();');
        $this->addLine(ESP.ESP.ESP.ESP.'$frm->setVo( $vo );');
        $this->addLine(ESP.ESP.ESP.ESP.'$controller = new '.$this->tableRefClass.'();');
        $this->addLine(ESP.ESP.ESP.ESP.'$resultado = $controller->save( $vo );');
        $this->addLine(ESP.ESP.ESP.ESP.'if( is_int($resultado) && $resultado!=0 ) {');
        $this->addLine(ESP.ESP.ESP.ESP.ESP.'$frm->addMessage(Message::GENERIC_SAVE);');
        $this->addLine(ESP.ESP.ESP.ESP.ESP.'$frm->clearFields();');
        $this->addLine(ESP.ESP.ESP.ESP.'}else{');
        $this->addLine(ESP.ESP.ESP.ESP.ESP.'$frm->addMessage($resultado);');
        $this->addLine(ESP.ESP.ESP.ESP.'}');
        $this->addLine(ESP.ESP.ESP.'}');
        $this->addLine(ESP.ESP.'}');
        $this->addBasicViewController_logCatch(ESP.ESP);
        $this->addLine(ESP.'break;');
    }
    //--------------------------------------------------------------------------------------
    private function addBasicaViewController_buscar()
    {
        $this->addLine();
        $this->addLine(ESP.'case \'Buscar\':');
        $this->addGetWhereGridParametersArray(ESP.ESP);
        $this->addLine(ESP.ESP.'$whereGrid = $retorno;');
        $this->addLine(ESP.'break;');
    }
    //--------------------------------------------------------------------------------------
    private function addBasicaViewController_limpar()
    {
        $this->addLine();
        $this->addLine(ESP.'case \'Limpar\':');
        $this->addLine(ESP.ESP.'$frm->clearFields();');
        $this->addLine(ESP.'break;');
    }
    //--------------------------------------------------------------------------------------
    private function addBasicaViewController_gdExcluir()
    {
        $this->addLine();
        $this->addLine(ESP.'case \'gd_excluir\':');
        $this->addLine(ESP.ESP.'try{');
        $this->addLine(ESP.ESP.ESP.'$id = $frm->get( $primaryKey ) ;');
        $this->addLine(ESP.ESP.ESP.'$controller = new '.$this->tableRefClass.'();');
        $this->addLine(ESP.ESP.ESP.'$resultado = $controller->delete( $id );');
        $this->addLine(ESP.ESP.ESP.'if($resultado==1) {');
        $this->addLine(ESP.ESP.ESP.ESP.'$frm->addMessage(Message::GENERIC_DELETE);');
        $this->addLine(ESP.ESP.ESP.ESP.'$frm->clearFields();');
        $this->addLine(ESP.ESP.ESP.'}else{');
        $this->addLine(ESP.ESP.ESP.ESP.'$frm->addMessage($resultado);');
        $this->addLine(ESP.ESP.ESP.'}');
        $this->addLine(ESP.ESP.'}');
        $this->addBasicViewController_logCatch(ESP.ESP);
        $this->addLine(ESP.'break;');
    }
    //--------------------------------------------------------------------------------------
    private function addBasicaViewController_exec()
    {
        $this->addLine();
        $this->addLine(ESP.'case \'Executar\':');
        $this->addLine(ESP.ESP.'try{');
        $this->addLine(ESP.ESP.ESP.'if ( $frm->validate() ) {');
        $this->addLine(ESP.ESP.ESP.ESP.'$vo = new '.$this->tableRefVO.'();');
        $this->addLine(ESP.ESP.ESP.ESP.'$frm->setVo( $vo );');
        $this->addLine(ESP.ESP.ESP.ESP.'$controller = new '.$this->tableRefClass.'();');
        $this->addLine(ESP.ESP.ESP.ESP.'$resultado = $controller->execProcedure( $vo );');
        $this->addLine(ESP.ESP.ESP.ESP.'if($resultado==true) {');
        $this->addLine(ESP.ESP.ESP.ESP.ESP.'$frm->addMessage(Message::GENERIC_EXEC);');
        $this->addLine(ESP.ESP.ESP.ESP.ESP.'$frm->clearFields();');
        $this->addLine(ESP.ESP.ESP.ESP.'}else{');
        $this->addLine(ESP.ESP.ESP.ESP.ESP.'$frm->addMessage($resultado);');
        $this->addLine(ESP.ESP.ESP.ESP.'}');
        $this->addLine(ESP.ESP.ESP.'}');
        $this->addLine(ESP.ESP.'}');
        $this->addBasicViewController_logCatch(ESP.ESP);
        $this->addLine(ESP.'break;');
    }    
    //--------------------------------------------------------------------------------------
    public function getMixUpdateFields($qtdTab)
    {
        if ($this->validateListColumnsName()) {
            $this->addLine($qtdTab.'$mixUpdateFields = $primaryKey.\'|\'.$primaryKey');
            foreach ($this->listColumnsName as $value) {
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

                switch ($formDinType) {
                    case self::FORMDIN_TYPE_DATE:
                    case self::FORMDIN_TYPE_DATETIME:
                        $this->addLine($qtdTab.'$grid->addColumnFormatDate(\''.$value.'\',\''.$fieldLabel.'\',null,null,\''.$this->getDtView.'\');');
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
    public function addMethod_onSave($qtdTab)
    {
        $this->addBlankLine();
        $this->addLine();
        $this->addLine($qtdTab.'public function onSave($param)');
        $this->addLine($qtdTab.'{');
        $this->addLine($qtdTab.ESP.'try{');
        $this->addLine($qtdTab.ESP.ESP.'$this->form->validate();');
        $this->addLine($qtdTab.ESP.ESP.'$data = $this->form->getData();');
        $this->addLine($qtdTab.ESP.ESP.'$this->form->setData($data);');
        $this->addBlankLine();
        $this->addLine($qtdTab.ESP.ESP.'//Função do FormDin para Debug');
        $this->addLine($qtdTab.ESP.ESP.'FormDinHelper::d($param,\'$param\');');
        $this->addLine($qtdTab.ESP.ESP.'FormDinHelper::debug($data,\'$data\');');
        $this->addLine($qtdTab.ESP.ESP.'FormDinHelper::debug($_REQUEST,\'$_REQUEST\');');
        $this->addBlankLine();
        $this->addLine($qtdTab.ESP.ESP.'new TMessage(\'info\', _t(\'Record saved\') );');
        $this->addLine($qtdTab.ESP.'}catch (Exception $e){');
        $this->addLine($qtdTab.ESP.ESP.'new TMessage(\'error\', $e->getMessage());');
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
    private function addBasicViewController($qtdTab)
    {

        $this->addMethod_onClose($qtdTab);
        if( $this->getTableType() == TableInfo::TB_TYPE_VIEW ){
            $this->addMethod_onClear($qtdTab);
        }
        /*
        if ($this->getTableType() == TableInfo::TB_TYPE_TABLE) {
            $this->addMethod_onSave($qtdTab);
        }
        $this->addBlankLine();
        */
    }        
    //--------------------------------------------------------------------------------------
    public function addButtons($qtdTab)
    {
        $this->addLine($qtdTab.'// O Adianti permite a Internacionalização - A função _t(\'string\') serve');
        $this->addLine($qtdTab.'//para traduzir termos no sistema. Veja ApplicationTranslator escrevendo');
        $this->addLine($qtdTab.'//primeiro em ingles e depois traduzindo');
        if ($this->getTableType() == TableInfo::TB_TYPE_TABLE) {
            $this->addLine($qtdTab.'$frm->setAction( _t(\'Save\'), \'onSave\', null, \'fa:save\', \'green\' );');
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
        $this->lines=null;
        $this->addLine('<?php');
        $this->addSysGenHeaderNote();
        $this->addBlankLine();
        $this->addLine("class ".$this->tableRef."Form extends TPage");
        $this->addLine("{");
        $this->addBlankLine();
        $this->addLine(ESP.'protected $form; // registration form');
        $this->addLine(ESP.'protected $datagrid; // listing');
        $this->addLine(ESP.'protected $pageNavigation;');
        $this->addBlankLine();
        if( $this->getTableType() == TableInfo::TB_TYPE_TABLE ){
            $this->addLine(ESP.'// trait com onReload, onSearch, onDelete, onClear, onEdit, show');
            $this->addLine(ESP.'use Adianti\Base\AdiantiStandardFormListTrait;');
        }elseif( $this->getTableType() == TableInfo::TB_TYPE_VIEW ){
            $this->addLine(ESP.'// trait com onReload, onSearch, onDelete...');
            $this->addLine(ESP.'use Adianti\Base\AdiantiStandardListTrait;');
        }        
        $this->addBlankLine();
        $this->addLine(ESP.'public function __construct()');
        $this->addLine(ESP.'{');
        $this->addLine(ESP.ESP.'parent::__construct();');
        $this->addLine(ESP.ESP.'// $this->adianti_target_container = \'adianti_right_panel\';');
        $this->addBlankLine();
        $this->addLine(ESP.ESP.'$this->setDatabase(\'maindatabase\'); // define the database');
        $this->addLine(ESP.ESP.'$this->setActiveRecord(\''.$this->tableRef.'\'); // define the Active Record');
        $this->addLine(ESP.ESP.'$this->setDefaultOrder(\''.$this->getPrimaryKeyTable().'\', \'asc\'); // define the default order');
        $this->addBlankLine();
        if( $this->getTableType() != TableInfo::TB_TYPE_PROCEDURE ){
            $this->addLine(ESP.ESP.'$primaryKey = \''.$this->getPrimaryKeyTable().'\';');
        }        
        $this->addLine(ESP.ESP.'$frm = new TFormDin($this,\''.$this->getFormTitle().'\');');
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
