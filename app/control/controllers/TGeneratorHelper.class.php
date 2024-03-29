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
class TGeneratorHelper
{    
    const TP_SYSTEM_FORM = 'TP_SYSTEM_FORM';
    const TP_SYSTEM_REST = 'TP_SYSTEM_REST';
    const TP_SYSTEM_FORM_REST = 'TP_SYSTEM_FORM_REST';

    const GEN_SYSTEM_ACRONYM = 'GEN_SYSTEM_ACRONYM';

    const THEME_FORMDINVB='THEME_FORMDINVB';
    const THEME_THEME3='THEME_THEME3';
    const THEME_THEME3V5='THEME_THEME3_V5';
    const THEME_THEME4='THEME_THEME4';
    const THEME_THEME4V5='THEME_THEME4_V5';


    //--------------------------------------------------------------------------------------
    public static function getGenSystemAcronym()
    {
        $genSystemAcronym = TSysgenSession::getValue(TGeneratorHelper::GEN_SYSTEM_ACRONYM);
        $genSystemAcronym = strtolower ( $genSystemAcronym );
        return $genSystemAcronym;
    }
    public static function testar($extensao, $html)
    {
        if (extension_loaded($extensao)) {
            $html->add('<b>'.$extensao.'</b>: <span class="success">Instalada.</span><br>');
            $result = true;
        } else {
            $html->add('<b>'.$extensao.'</b>: <span class="failure">Não instalada</span><br>');
            $result = false;
        }
        return $result;
    }
    
    public static function validatePDO($DBMS, $html)
    {
        $result = false;
        if (self::testar('PDO', $html)) {
            $result = true;
        }
        
        if ($result == false) {
            $texto ='<span class="alert">Instale a extensão PDO. DEPOIS tente novamente</span><br>';
            $texto = $texto.'(PHP Data Objects) é uma extensão que fornece uma interface padronizada para trabalhar com diversos bancos<br>';
            $html->add($texto);
        }
        return $result;
    }
    
    public static function validateDBMS($DBMS, $html)
    {
        // https://secure.php.net/manual/pt_BR/pdo.drivers.php
        $result = false;
        if ($DBMS == TFormDinPdoConnection::DBMS_MYSQL) {
            if (self::testar('PDO_MYSQL', $html)) {
                $result = true;
            }
        } elseif ($DBMS == TFormDinPdoConnection::DBMS_SQLITE) {
            if (self::testar('PDO_SQLITE', $html)) {
                $result = true;
            }
        } elseif ($DBMS == TFormDinPdoConnection::DBMS_SQLSERVER) {
            if (self::testar('PDO_SQLSRV', $html)) {
                $result = true;
            }
        } elseif ($DBMS == TFormDinPdoConnection::DBMS_ACCESS) {
            if (self::testar('PDO_ODBC', $html)) {
                $result = true;
            }
        } elseif ($DBMS == TFormDinPdoConnection::DBMS_FIREBIRD) {
            if (self::testar('PDO_FIREBIRD', $html)) {
                $result = true;
            }
        } elseif ($DBMS == TFormDinPdoConnection::DBMS_ORACLE) {
            if (self::testar('PDO_OCI', $html)) {
                $result = true;
            }
        } elseif ($DBMS == TFormDinPdoConnection::DBMS_POSTGRES) {
            if (self::testar('PDO_PGSQL', $html)) {
                $result = true;
            }
        }
        
        if ($result == false) {
            $texto ='<br><span class="alert">Instale a extensão PDO para banco de dados: '.$DBMS.'.<br> DEPOIS tente novamente</span><br>';
            $html->add($texto);
        }
        
        return $result;
    }
    
    public static function validatePDOAndDBMS($DBMS, $html)
    {
        if (self::validatePDO($DBMS, $html) && self::validateDBMS($DBMS, $html)) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }
    
    public static function showAbaDBMS($DBMS, $DBMSAba)
    {
        if ($DBMS == $DBMSAba) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }
    
    public static function showMsg($type, $msg)
    {
        if ($type == 1) {
            $msg = '<span class="success">'.$msg.'</span><br>';
        } elseif ($type == 0) {
            $msg = '<span class="failure">'.$msg.'</span><br>';
        } elseif ($type == -1) {
            $msg = '<span class="alert">'.$msg.'</span><br>';
        } else {
            $msg = $msg.'<br>';
        }
        return $msg;
    }
    
    public static function validateFolderName($nome)
    {
        $nome=StringHelper::strtolower_utf8($nome);
        $is_string = is_string($nome);
        $strlen    = strlen($nome) > 50;
        $preg      = preg_match('/^(([a-z]|[0-9]|_)+|)$/', $nome, $matches);
        if (!$is_string || $strlen || !$preg) {
            throw new DomainException(Message::SYSTEM_ACRONYM_INVALID);
        }
    }
    
    public static function mkDir($path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0744, true);
        }
    }
    public static function getPathNewSystem()
    {
        return ROOT_PATH.self::getGenSystemAcronym();
    }
    
    public static function createFileConstants()
    {
        $file = new TCreateConstants();
        $file->saveFile();
    }
    
    public static function createFileConfigDataBase()
    {
        $file = new TCreateConfigDataBase();
        $file->saveFile();
    }
    
    public static function createFileAutoload()
    {
        $file = new TCreateAutoload();
        $file->saveFile();

        $file = new CreateAutoloadDAO();
        $file->saveFile();

        if( TSysgenSession::getValue(TableInfo::TP_SYSTEM) != self::TP_SYSTEM_FORM ){
            $file = new CreateAutoloadAPI();
            $file->saveFile();
        }
    }

    public static function createApiIndexAndRouter($listTable)
    {
        $file = new CreateApiIndex();
        $file->saveFile();
        
        $file = new CreateApiRoutesCall($listTable);
        $file->saveFile();
    }
    
    public static function createFileMenu($listTable)
    {
        $file = new TCreateMenu();
        $file->setListTableNames($listTable);
        $file->saveFile();
    }
    
    public static function createFileIndex()
    {
        $file = new TCreateIndex();
        $file->saveFile();
    }
    
    public static function getTDAOConect($tableName, $schema)
    {
        $DBMS = TSession::getValue('DBMS');
        $dbType   = $DBMS['TYPE'];
        $user     = $DBMS['USER'];
        $password = $DBMS['PASSWORD'];
        $dataBase = $DBMS['DATABASE'];
        $host     = $DBMS['HOST'];
        $port     = $DBMS['PORT'];
        $schema   = is_null($schema)?$DBMS['SCHEMA']:$schema;
        
        $dao = new TFormDinDaoDbms($tableName, $dbType, $user, $password, $dataBase, $host, $port, $schema);
        return $dao;
    }
    
    public static function loadTablesFromDatabase()
    {
        $listAllTables = array();
        if(ArrayHelper::has(TableInfo::LIST_TABLES_DB, $_SESSION[APPLICATION_NAME])){
            $listAllTables = $_SESSION[APPLICATION_NAME][TableInfo::LIST_TABLES_DB];
        }else{
            $dao = self::getTDAOConect(null, null);
            $listAllTables = $dao->loadTablesFromDatabase();
            if (!is_array($listAllTables)) {
                throw new InvalidArgumentException(Message::ERRO_LIST_TABLE_NOT_ARRAY);
            }
            foreach ($listAllTables['TABLE_NAME'] as $key => $value) {
                $listAllTables['idSelected'][] = $listAllTables['TABLE_SCHEMA'][$key].$value.$listAllTables['COLUMN_QTD'][$key].$listAllTables['TABLE_TYPE'][$key];
            }
            $_SESSION[APPLICATION_NAME][TableInfo::LIST_TABLES_DB] = $listAllTables;
        }
        return $listAllTables;
    }
    
    public static function getConfigGridSqlServer($DBMS)
    {
        $sessionDBMS = TSysgenSession::getValue('DBMS');
        $dbversion = $sessionDBMS['VERSION'];
        $TPGRID = FormDinHelper::GRID_SQL_PAGINATION;
        $withVersion = TableInfo::getDbmsWithVersion($DBMS);
        if( ($dbversion == TableInfo::DBMS_VERSION_SQLSERVER_2012_LT) && $withVersion ){
            $TPGRID = FormDinHelper::GRID_SCREEN_PAGINATION;
        }
        return $TPGRID;
    }
    
    public static function getConfigGridMySql($DBMS)
    {
        $dbversion = $_SESSION[APPLICATION_NAME]['DBMS']['VERSION'];
        $TPGRID = FormDinHelper::GRID_SQL_PAGINATION;
        $withVersion = TableInfo::getDbmsWithVersion($DBMS);
        if( ($dbversion == TableInfo::DBMS_VERSION_MYSQL_8_LT) && $withVersion ){
            $TPGRID = FormDinHelper::GRID_SCREEN_PAGINATION;
        }
        return $TPGRID;
    }
    
    public static function getConfigGridPostgresql($DBMS)
    {
        $dbversion = $_SESSION[APPLICATION_NAME]['DBMS']['VERSION'];
        $TPGRID = FormDinHelper::GRID_SQL_PAGINATION;
        $withVersion = TableInfo::getDbmsWithVersion($DBMS);        
        if( ($dbversion == TableInfo::DBMS_VERSION_POSTGRES_95_LT) && $withVersion ){
            $TPGRID = FormDinHelper::GRID_SCREEN_PAGINATION;
        }
        return $TPGRID;
    }

    private static function getConfigByDBMS()
    {
        $DBMS      = TSysgenSession::getValue('DBMS');
        $DBMS_TYPE = $DBMS['TYPE'];
        switch ($DBMS_TYPE) {
            case TFormDinPdoConnection::DBMS_MYSQL:
                $SCHEMA = false;
                $TPGRID = self::getConfigGridMySql($DBMS_TYPE);
            break;
            case TFormDinPdoConnection::DBMS_SQLSERVER:
                $SCHEMA = true;
                $TPGRID = self::getConfigGridSqlServer($DBMS_TYPE);
            break;
            case TFormDinPdoConnection::DBMS_POSTGRES:
                $SCHEMA = true;
                $TPGRID = self::getConfigGridPostgresql($DBMS_TYPE);
            break;
            case TFormDinPdoConnection::DBMS_SQLITE;
                $SCHEMA = false;
                $TPGRID = FormDinHelper::GRID_SQL_PAGINATION;
            break;             
            default:
                $SCHEMA = false;
                $TPGRID = FormDinHelper::GRID_SCREEN_PAGINATION;
        }
        $config = array();
        $config['SCHEMA'] = $SCHEMA;
        $config['TPGRID'] = $TPGRID;
        return $config;
    }
    
    public static function createFilesControllers($tableName, $listColumnsProperties, $tableSchema, $tableType)
    {
        $configDBMS = self::getConfigByDBMS();
        $generator  = new CreateControllers($tableName);
        $generator->setTableType($tableType);
        $generator->setListColumnsProperties($listColumnsProperties);
        $generator->setListColunnsName($listColumnsProperties['COLUMN_NAME']);
        $generator->setWithSqlPagination($configDBMS['TPGRID']);
        //$pathFolder = TGeneratorHelper::getPathNewSystem().DS.'controllers'.DS;
        $pathFolder = self::getPathNewSystem().DS.'app'.DS.'control'.DS.'maindatabase'.DS.'controllers';
        $generator->setFilePath( $pathFolder );
        $generator->saveFile();
    }
    
    public static function createFilesTests($tableName, $listColumnsProperties, $tableSchema, $tableType)
    {
        $generator  = new CreateTestsFiles($tableName, $listColumnsProperties, $tableType);
        $generator->saveFile();
    }
    
    public static function createFilesModel($tableName, $listColumnsProperties, $tableSchema, $tableType)
    {
        $folder      = self::getPathNewSystem().DS.'app'.DS.'model'.DS.'maindatabase';
               
        $generatorDao = new TCreateModel($folder,$tableName, $listColumnsProperties);
        $generatorDao->setTableType($tableType);
        $generatorDao->setTableSchema($tableSchema);
        $generatorDao->saveFile();
    }

    public static function createFilesDaoVoFromTable($tableName, $listColumnsProperties, $tableSchema, $tableType)
    {
        $DBMS = TSysgenSession::getValue('DBMS');
        $DBMS_TYPE  = $DBMS['TYPE'];
        $configDBMS  = self::getConfigByDBMS();
        $pathFolder  = self::getPathNewSystem().DS.'app'.DS.'control'.DS.'maindatabase'.DS.'dao';

        $generatorVo = new TCreateVO($pathFolder,$tableName, $listColumnsProperties,$DBMS_TYPE);
        $generatorVo->saveFile();
        
        $generatorDao = new TCreateDAO($pathFolder,$tableName, $listColumnsProperties);
        $generatorDao->setTableType($tableType);
        $generatorDao->setDatabaseManagementSystem($DBMS_TYPE);
        $generatorDao->setWithSqlPagination($configDBMS['TPGRID']);
        $generatorDao->setTableSchema($tableSchema);
        $generatorDao->saveFile();
    }
    
    public static function createFilesForms($tableName, $listColumnsProperties, $tableSchema, $tableType)
    {
        $DBMS = TSysgenSession::getValue('DBMS');
        $DBMS_TYPE  = $DBMS['TYPE'];

        $dtView = TSysgenSession::getValue('DT_VIEW');
        $dtDB   = TSysgenSession::getValue('DT_DB');
        $TP_GRIDP_FORM_LIST   = TSysgenSession::getValue(TableInfo::TP_GRID_FROM_LIST);

        $configDBMS = self::getConfigByDBMS();
        $pathFolder = self::getPathNewSystem().DS.'app'.DS.'control'.DS.'maindatabase'.DS.'views';

        if( ($tableType == TableInfo::TB_TYPE_TABLE) || ($tableType == TableInfo::TB_TYPE_VIEW) ){
            $geradorFormList= new TCreateFormList($pathFolder ,$tableName ,$listColumnsProperties);
            $geradorFormList->setDatabaseManagementSystem($DBMS_TYPE);
            $geradorFormList->setTableType($tableType);
            $geradorFormList->setGridType($configDBMS['TPGRID']);
            $geradorFormList->setGridTypeFormList($TP_GRIDP_FORM_LIST);
            $geradorFormList->setDtView($dtView);
            $geradorFormList->setDtDb($dtDB);
            $geradorFormList->saveFile();
        }

        if( ($tableType == TableInfo::TB_TYPE_TABLE) || ($tableType == TableInfo::TB_TYPE_PROCEDURE) ){
            $geradorForm= new TCreateForm($pathFolder ,$tableName ,$listColumnsProperties);
            $geradorForm->setDatabaseManagementSystem($DBMS_TYPE);
            $geradorForm->setTableType($tableType);
            $geradorForm->setGridType($configDBMS['TPGRID']);
            $geradorForm->setDtView($dtView);
            $geradorForm->setDtDb($dtDB);
            $geradorForm->saveFile();
        }
    }

    public static function createFilesControllesAndRoutesAPI($tableName, $listColumnsProperties, $tableSchema, $tableType)
    {
        $pathFolder= self::getPathNewSystem().DS.'api'.DS.'api_controllers';
        $generator = new CreateApiControllesFiles($pathFolder,$tableName, $listColumnsProperties, $tableType);
        $generator->saveFile();

        //$pathFolder= self::getPathNewSystem().DS.'api'.DS.'routes';
        //$generator = new CreateApiRoutesFiles($pathFolder,$tableName, $listColumnsProperties, $tableType);
        //$generator->saveFile();
    }
    
    public static function createFilesFormControllerModelFromTable($tableName, $listColumnsProperties, $tableSchema, $tableType)
    {
        self::createFilesModel($tableName, $listColumnsProperties,$tableSchema,$tableType);
        self::createFilesDaoVoFromTable($tableName, $listColumnsProperties,$tableSchema,$tableType);
        self::createFilesControllers($tableName, $listColumnsProperties, $tableSchema, $tableType);
        //self::createFilesTests($tableName, $listColumnsProperties, $tableSchema, $tableType);

        if( TSysgenSession::getValue(TableInfo::TP_SYSTEM) != TGeneratorHelper::TP_SYSTEM_REST ){
            self::createFilesForms($tableName, $listColumnsProperties, $tableSchema, $tableType);
        }

        if( TSysgenSession::getValue(TableInfo::TP_SYSTEM) != TGeneratorHelper::TP_SYSTEM_FORM ){
            self::createFilesControllesAndRoutesAPI($tableName, $listColumnsProperties, $tableSchema, $tableType);
        }
    }
    
    public static function getUrlNewSystem()
    {
        $url = ServerHelper::getCurrentUrl(true);
        if( TSysgenSession::getTpSysgen() == TSysgenSession::TP_SYSGEN_ADIANTI){
            $url = explode('engine.php', $url);
            $url = $url[0];
        }
        $dir = explode(DS, __DIR__);
        if( TSysgenSession::getTpSysgen() == TSysgenSession::TP_SYSGEN_ADIANTI){
            $dirSysGen = array_pop($dir);
            $dirSysGen = array_pop($dir);
            $dirSysGen = array_pop($dir);
            $dirSysGen = array_pop($dir);
        }else{
            $dirSysGen = array_pop($dir);
            $dirSysGen = array_pop($dir);
        }
        $url    = explode($dirSysGen, $url);
        $result = $url[0].self::getGenSystemAcronym();
        return $result;
    }
    
    public static function loadTablesSelected()
    {
        $listTablesAll   = TGeneratorHelper::loadTablesFromDatabase();
        $idTableSelected = TSysgenSession::getValue('idTableSelected');
        $listTablesSelected = array();
        foreach ($idTableSelected as $id) {
            $keyTable = array_search($id, $listTablesAll['idSelected']);
            $listTablesSelected['TABLE_SCHEMA'][] = $listTablesAll['TABLE_SCHEMA'][$keyTable];
            $listTablesSelected['TABLE_NAME'][]   = $listTablesAll['TABLE_NAME'][$keyTable];
            $listTablesSelected['TABLE_TYPE'][]   = $listTablesAll['TABLE_TYPE'][$keyTable];
        }
        return $listTablesSelected;
    }
    
    public static function removeFieldsDuplicateOnSelectedTables($listFieldsTable)
    {
        ValidateHelper::isArray($listFieldsTable, __METHOD__, __LINE__);
        $listFieldsTablePDO = ArrayHelper::convertArrayFormDin2Pdo($listFieldsTable,false);
        $listColumnName = $listFieldsTable['COLUMN_NAME'];
        $listFieldsTableResult = array();
        foreach ($listColumnName as $name) {
            $listKey = ArrayHelper::array_keys2($listColumnName,$name);
            $sizeKeyQtd = CountHelper::count($listKey);
            if($sizeKeyQtd==1){
                $keyColumnName = $listKey[0];
                $listFieldsTableResult[$keyColumnName]=$listFieldsTablePDO[$keyColumnName];
            }else{
                $listKeyTip = array();
                foreach ($listKey as $key) {
                    //$listKeyTip[$key]=$listFieldsTablePDO[$keyColumnName]['KEY_TYPE'];
                    $keyTip=$listFieldsTablePDO[$keyColumnName]['KEY_TYPE'];
                }
            }
        }
        $listFieldsTableResult = ArrayHelper::convertArrayPdo2FormDin($listFieldsTableResult);
        return $listFieldsTableResult;
    }

    public static function loadFieldsTablesSelected()
    {
        TSysgenSession::setValue('FieldsTableSelected', null);
        $FieldsTableSelected = null;
        $listTables = TGeneratorHelper::loadTablesSelected();
        foreach ($listTables['TABLE_NAME'] as $key => $table) {
            $tableSchema = $listTables['TABLE_SCHEMA'][$key];
            $tableType   = $listTables['TABLE_TYPE'][$key];
            $dao = TGeneratorHelper::getTDAOConect($table, $tableSchema);
            if($tableType == TableInfo::TB_TYPE_PROCEDURE){
                $listFieldsTable = $dao->loadFieldsOneStoredProcedureFromDatabase();
            }else{
                $listFieldsTable = $dao->loadFieldsOneTableFromDatabase();
            }
            $FieldsTableSelected[] = $listFieldsTable;
        }
        TSysgenSession::setValue('FieldsTableSelected', $FieldsTableSelected);
        return $FieldsTableSelected;
    }
    
    public static function loadFieldsTablesSelectedWithFormDin($table, $tableType, $tableSchema)
    {
        $dao = self::getTDAOConect($table, $tableSchema);
        if($tableType == TableInfo::TB_TYPE_PROCEDURE){
            $listFieldsTable = $dao->loadFieldsOneStoredProcedureFromDatabase();
        }else{
            $listFieldsTable = $dao->loadFieldsOneTableFromDatabase();
        }
        foreach ($listFieldsTable['DATA_TYPE'] as $key => $dataType) {
            $DBMS = TSysgenSession::getValue('DBMS');
            $formDinType = TCreateForm::convertDataType2FormDinType($dataType,$DBMS['TYPE']);
            $listFieldsTable[TCreateForm::FORMDIN_TYPE_COLUMN_NAME][$key] = $formDinType;
            
            if( TSysgenSession::getValue(TableInfo::TP_SYSTEM) != self::TP_SYSTEM_REST ){
                $fkTypeScreenReferenced = self::getFKTypeScreenReferencedSelected($table, $tableSchema, $listFieldsTable, $key);
                $listFieldsTable[TableInfo::FK_TYPE_SCREEN_REFERENCED][$key] = $fkTypeScreenReferenced;
            }
        }
        return $listFieldsTable;
    }
    
    public static function getFKTypeScreenReferencedSelected($table, $tableSchema, $listFieldsTable, $key)
    {
        $fkTypeScreenReferenced = null;                
        if(ArrayHelper::has(TableInfo::KEY_TYPE,$listFieldsTable) && $listFieldsTable[TableInfo::KEY_TYPE][$key] == TableInfo::KEY_TYPE_FK){
            $columnNameTarget = $listFieldsTable[TableInfo::COLUMN_NAME][$key];
            $idColumnTarger = $tableSchema.$table.$columnNameTarget;
            $FkFieldsTableSelected = TSysgenSession::getValue(TableInfo::FK_FIELDS_TABLE_SELECTED);
            $listIdColumns = $FkFieldsTableSelected[TableInfo::ID_COLUMN_FK_SRSELECTED];
            $keyFkTypeScreenReferencedSelected = array_search($idColumnTarger, $listIdColumns);
            $fkTypeScreenReferenced = $FkFieldsTableSelected[TableInfo::FK_TYPE_SCREEN_REFERENCED][$keyFkTypeScreenReferencedSelected];
        }
        return $fkTypeScreenReferenced;
    }
    
    public static function getListFKTypeScreenReferenced()
    {
        $array = array();
        $array[TCreateForm::FORM_FKTYPE_SELECT] = 'Select Field';
        $array[TCreateForm::FORM_FKTYPE_AUTOCOMPLETE] = 'Autocomplet';
        //$array[] = 'Search On-line';
        //$array[] = 'Select Field + Crud';
        return $array;
    }

    public static function getListTypeSystem()
    {
        $array = array();
        $array[self::TP_SYSTEM_FORM] = 'Somente tela FormDin';
        $array[self::TP_SYSTEM_REST] = 'Somente API REST';
        $array[self::TP_SYSTEM_FORM_REST] = 'FormDin + REST';
        return $array;
    }

    public static function listFKFieldsTablesSelected()
    {
        $FkFieldsTableSelected = null;
        $FieldsTableSelected   = self::loadFieldsTablesSelected();
        $ID_LINE = 1;
        foreach ($FieldsTableSelected as $key => $table) {
            $KEY_TYPE = ArrayHelper::get($FieldsTableSelected[$key], 'KEY_TYPE');
            $KEY_FK = ArrayHelper::array_keys2($KEY_TYPE, 'FOREIGN KEY');
            foreach ($KEY_FK as $keyFieldFkTable) {
                $refTable  = $FieldsTableSelected[$key]['REFERENCED_TABLE_NAME'][$keyFieldFkTable];
                $refColumn = $FieldsTableSelected[$key]['REFERENCED_COLUMN_NAME'][$keyFieldFkTable];
                
                $FkFieldsTableSelected['ID_LINE'][]= $ID_LINE;
                $ID_LINE = $ID_LINE + 1;
                $FkFieldsTableSelected[TableInfo::TABLE_SCHEMA][]= $FieldsTableSelected[$key][TableInfo::TABLE_SCHEMA][$keyFieldFkTable];
                $FkFieldsTableSelected[TableInfo::TABLE_NAME][]  = $FieldsTableSelected[$key][TableInfo::TABLE_NAME][$keyFieldFkTable];
                $FkFieldsTableSelected[TableInfo::COLUMN_NAME][] = $FieldsTableSelected[$key][TableInfo::COLUMN_NAME][$keyFieldFkTable];
                $FkFieldsTableSelected[TableInfo::DATA_TYPE][]   = $FieldsTableSelected[$key][TableInfo::DATA_TYPE][$keyFieldFkTable];
                $FkFieldsTableSelected['REFERENCED_TABLE_NAME'][]  = $refTable;
                $FkFieldsTableSelected['REFERENCED_COLUMN_NAME'][] = $refColumn;
                //$FkFieldsTableSelected['FK_TYPE_SCREEN_REFERENCED'][] = self::getFKTypeScreenReferenced($refTable,$refColumn);
                $FkFieldsTableSelected[TableInfo::ID_COLUMN_FK_SRSELECTED][] = $FieldsTableSelected[$key][TableInfo::TABLE_SCHEMA][$keyFieldFkTable]
                                                                              .$FieldsTableSelected[$key][TableInfo::TABLE_NAME][$keyFieldFkTable]
                                                                              .$FieldsTableSelected[$key][TableInfo::COLUMN_NAME][$keyFieldFkTable];
                $FkFieldsTableSelected[TableInfo::FK_TYPE_SCREEN_REFERENCED][] = null;
            }
        }
        TSysgenSession::setValue(TableInfo::FK_FIELDS_TABLE_SELECTED,$FkFieldsTableSelected);
        return $FkFieldsTableSelected;
    }    
    //--------------------------------------------------------------------------------------
    public static function validateListTableNames($listTableNames)
    {   
        if (empty($listTableNames)) {
            throw new InvalidArgumentException(Message::ERRO_LIST_TABLE_EMPTY);
        }
        if (!is_array($listTableNames)) {
            throw new InvalidArgumentException(Message::ERRO_LIST_TABLE_NOT_ARRAY);
        }
    }
    //--------------------------------------------------------------------------------------
    public static function validateListColumnsProperties($listColumnsProperties)
    {   
        if (empty($listColumnsProperties)) {
            throw new InvalidArgumentException(Message::ERRO_LIST_COLUMNS_EMPTY);
        }
        if (!is_array($listColumnsProperties)) {
            throw new InvalidArgumentException(Message::ERRO_LIST_COLUMNS_NOT_ARRAY);
        }
    }
    //--------------------------------------------------------------------------------------
    public static function getListTheme()
    {
        $list = array();
        $list[self::THEME_FORMDINVB]='FormDin VB - Vertical BootStrap';
        $list[self::THEME_THEME3]='Tema 3 Adianti - BootStrap';
        $list[self::THEME_THEME3V5]='Tema 3 V5 Adianti - BootStrap, com melhorias';
        $list[self::THEME_THEME4]='Tema 4 Adianti - Material';
        $list[self::THEME_THEME4V5]='Tema 4 V5 Adianti - Material, com melhorias';
        return $list;
    }
}