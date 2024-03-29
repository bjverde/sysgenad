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

/**
 * Information about the table or view. With constant names
 * @author bjverde
 *
 */
final class TableInfo
{
    
    const TB_TYPE_TABLE = 'TABLE';
    const TB_TYPE_VIEW  = 'VIEW';
    const TB_TYPE_PROCEDURE  = 'PROCEDURE';
    //-----------------------------------------------------------------------------------
    const LIST_TABLES_DB = 'LIST_TABLES_DB';
    //-----------------------------------------------------------------------------------
    const TP_SYSTEM = 'TP_SYSTEM';
    const TP_SYSTEM_THEME = 'TP_SYSTEM_THEME';
    const TP_GRID_FROM_LIST = 'TP_GRID_FROM_LIST';
    const TP_GRID_FROM_LIST_AD = 'TP_GRID_FROM_LIST_AD';
    const TP_GRID_FROM_LIST_FD5= 'TP_GRID_FROM_LIST_FD5';
    
    const TABLE_SCHEMA = 'TABLE_SCHEMA';
    const TABLE_NAME = 'TABLE_NAME';
    const KEY_TYPE = 'KEY_TYPE';
    const KEY_TYPE_PK = 'PK';
    const KEY_TYPE_FK = 'FOREIGN KEY';
    const COLUMN_NAME = 'COLUMN_NAME';
    const DATA_TYPE   = 'DATA_TYPE';
    
    const ID_COLUMN_FK_SRSELECTED   = 'ID_COLUMN_FK_SRSELECTED';
    const FK_FIELDS_TABLE_SELECTED  = 'FkFieldsTableSelected';
    const FK_TYPE_SCREEN_REFERENCED = 'FK_TYPE_SCREEN_REFERENCED';
    //-----------------------------------------------------------------------------------    
    const DBMS_VERSION_SQLSERVER_2012_GTE = 'DBMS_VERSION_SQLSERVER_2012_GTE';
    const DBMS_VERSION_SQLSERVER_2012_LT  = 'DBMS_VERSION_SQLSERVER_2012_LT';

    const DBMS_VERSION_SQLSERVER_2012_GTE_LABEL = 'SQL Server 2012 (11.0) ou superior';
    const DBMS_VERSION_SQLSERVER_2012_LT_LABEL  = 'SQL Server anteior 2012';

    const DBMS_VERSION_MYSQL_8_GTE = 'DBMS_VERSION_MYSQL_8_GTE';
    const DBMS_VERSION_MYSQL_8_LT  = 'DBMS_VERSION_MYSQL_8_LT';

    const DBMS_VERSION_MYSQL_8_GTE_LABEL = 'MySQL 8 ou superior';
    const DBMS_VERSION_MYSQL_8_LT_LABEL  = 'MySQL anterior a versão 8';

    const DBMS_VERSION_POSTGRES_96_GTE = 'DBMS_VERSION_POSTGRES_96_GTE';
    const DBMS_VERSION_POSTGRES_95_LT  = 'DBMS_VERSION_POSTGRES_95_LT';

    const DBMS_VERSION_POSTGRES_96_GTE_LABEL = 'PostgreSQL 9.6 ou superior';
    const DBMS_VERSION_POSTGRES_95_LT_LABEL  = 'PostgreSQL até a versão 9.5';

    //-----------------------------------------------------------------------------------    
    public static function getPreDBMS($dbType){
        $return = null;
        switch ($dbType) {
            //-------------------
            case TFormDinPdoConnection::DBMS_MYSQL:
                $return = 'my';
            break;
            //-------------------
            case TFormDinPdoConnection::DBMS_SQLITE:
                $return = 'sq';
            break;
            //-------------------
            case TFormDinPdoConnection::DBMS_SQLSERVER:
                $return = 'ss';
            break;
            //-------------------
            case TFormDinPdoConnection::DBMS_POSTGRES:
                $return = 'pg';
            break;
            //-------------------
            case TFormDinPdoConnection::DBMS_ORACLE:
                $return = 'ora';
            break;
        }
        return $return;
    }
    //-----------------------------------------------------------------------------------    
    public static function getDbmsWithVersion($dbType){
        $return = false;
        switch ($dbType) {
            //-------------------
            case TFormDinPdoConnection::DBMS_MYSQL:
                $return = false;
            break;
            //-------------------
            case TFormDinPdoConnection::DBMS_SQLITE:
                $return = false;
            break;
            //-------------------
            case TFormDinPdoConnection::DBMS_SQLSERVER:
                $return = true;
            break;
            //-------------------
            case TFormDinPdoConnection::DBMS_POSTGRES:
                $return = true;
            break;
            //-------------------
            case TFormDinPdoConnection::DBMS_ORACLE:
                $return = false;
            break;
        }
        return $return;
    }
    //-----------------------------------------------------------------------------------    
    public static function getListDbmsWithVersion($dbType){
        $return = false;
        switch ($dbType) {
            //-------------------
            case TFormDinPdoConnection::DBMS_SQLSERVER:
                $return = array(TableInfo::DBMS_VERSION_SQLSERVER_2012_GTE=>TableInfo::DBMS_VERSION_SQLSERVER_2012_GTE_LABEL
                               ,TableInfo::DBMS_VERSION_SQLSERVER_2012_LT =>TableInfo::DBMS_VERSION_SQLSERVER_2012_LT_LABEL
                               );
            break;
            //-------------------
            case TFormDinPdoConnection::DBMS_POSTGRES:
                $return = array(TableInfo::DBMS_VERSION_POSTGRES_96_GTE=>TableInfo::DBMS_VERSION_POSTGRES_96_GTE_LABEL
                               ,TableInfo::DBMS_VERSION_POSTGRES_95_LT  =>TableInfo::DBMS_VERSION_POSTGRES_95_LT_LABEL
                               );
            break;
        }
        return $return;
    }    
}
