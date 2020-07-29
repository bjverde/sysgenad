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

class TCreateModel extends TCreateFileContent
{
    private $tableName;
    private $aColumns = array();
    private $lines;
    private $keyColumnName;
    private $path;
    private $databaseManagementSystem;
    private $tableSchema;
    private $charParam = '?';
    private $listColumnsProperties;
    private $tableType = null;


    /**
     * Create file DAO form a table info
     * @param string $pathFolder   - folder path to create file
     * @param string $tableName    - table name
     * @param array $listColumnsProperties
     */
    public function __construct($pathFolder ,$tableName ,$listColumnsProperties)
    {
        $tableName = strtolower($tableName);
        $this->setTableName($tableName);
        $this->setFileName(ucfirst($tableName).'.class.php');
        $this->setFilePath($pathFolder);
        $this->setListColumnsProperties($listColumnsProperties);
        $this->configArrayColumns();
    }
    //-----------------------------------------------------------------------------------
    public function setTableName($strTableName)
    {
        $strTableName = strtolower($strTableName);
        $this->tableName=$strTableName;
    }
    public function getTableName()
    {
        return $this->tableName;
    }
    //------------------------------------------------------------------------------------
    public function getKeyColumnName()
    {
        return $this->keyColumnName;
    }
    //------------------------------------------------------------------------------------
    public function setTableSchema($tableSchema)
    {
        return $this->tableSchema = $tableSchema;
    }
    public function getTableSchema()
    {
        return $this->tableSchema;
    }
    public function hasSchema()
    {
        $result = '';
        if (!empty($this->getTableSchema())) {
            $result = $this->getTableSchema().'.';
        }
        return $result;
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
    public function getCharParam()
    {
        return $this->charParam;
    }
    //------------------------------------------------------------------------------------
    public function addColumn($strColumnName)
    {
        if (!in_array($strColumnName, $this->aColumns)) {
            $this->aColumns[] = strtolower($strColumnName);
        }
    }
    //--------------------------------------------------------------------------------------
    public function getColumns()
    {
        return $this->aColumns;
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
        $this->keyColumnName = strtolower($listColumns[0]);
        foreach ($listColumns as $v) {
            $this->addColumn($v);
        }
    }
    //--------------------------------------------------------------------------------------
    public function getColumnPKeyPropertieFormDinType()
    {
        $PKeyName = $this->getKeyColumnName();
        $listColuns = $this->getColumns();
        $key  = ArrayHelper::array_keys2($listColuns,$PKeyName,true);
        $formDinType = null;
        if( is_array($key) && !empty($key) ){
            $formDinType = $this->getColumnsPropertieFormDinType($key[0]);
        }
        return $formDinType;
    }
    
    private function getColumnsPropertieFormDinType($key)
    {
        $result = null;
        if (ArrayHelper::has(TCreateForm::FORMDIN_TYPE_COLUMN_NAME, $this->listColumnsProperties)) {
            $result = strtoupper($this->listColumnsProperties[TCreateForm::FORMDIN_TYPE_COLUMN_NAME][$key]);
        }
        return $result;
    }
    //--------------------------------------------------------------------------------------
    public function show($print = false)
    {
        $this->lines=null;
        $this->addLine('<?php');
        $this->addSysGenHeaderNote();
        $this->addLine('class '.ucfirst($this->getTableName()).' extends TRecord');
        $this->addLine('{');
        $this->addLine(ESP.'const TABLENAME = \''.$this->tableName.'\';');
        $this->addLine(ESP.'const PRIMARYKEY= \''.$this->keyColumnName.'\';');
        $this->addLine(ESP.'const IDPOLICY  = \'serial\';');
        $this->addBlankLine();
        $this->addLine(ESP.'public function __construct($id = NULL, $callObjectLoad = TRUE)');
        $this->addLine(ESP.'{');
        $this->addLine(ESP.'}');
        $this->addBlankLine();
        $this->addLine("}");
        $this->addLine("?>");
        return $this->showContent($print);
    }
    //--------------------------------------------------------------------------------------
    /**
     * Returns the number of parameters
     *
     * @return string
     */
    public function getParams()
    {
        $cols = $this->getColumns();
        $qtd = count($cols);
        $result = '';
        for ($i = 1; $i <= $qtd; $i++) {
            if ($cols[$i-1] != strtolower($this->keyColumnName)) {
                $result .= ($result=='') ? '' : ',';
                $result.='?';
            }
        }
        return $result;
    }
    //--------------------------------------------------------------------------------------
    public function removeUnderline($txt)
    {
        $len = strlen($txt);
        for ($i = $len-1; $i >= 0; $i--) {
            if ($txt[$i] === '_') {
                $len--;
                $txt = substr_replace($txt, '', $i, 1);
                if ($i != $len) {
                    $txt[$i] = strtoupper($txt[$i]);
                }
            }
        }
        return $txt;
    }
}