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

$path = __DIR__.'/../../';
require_once $path.'mockDatabase.php';

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Error\Warning;

class TGeneratorHelperTest extends TestCase
{
    private $mockDatabase;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp(): void {
        parent::setUp();
        $this->mockDatabase = new mockDatabase();
    }
    
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown(): void {
        parent::tearDown();
    }
    
    public function testGetConfigGridSqlServer_ScreenPagination()
    {
        $result = GRID_SCREEN_PAGINATION;
        $_SESSION[APLICATIVO]['DBMS']['VERSION'] = TableInfo::DBMS_VERSION_SQLSERVER_2012_LT;
        $expected = TGeneratorHelper::getConfigGridSqlServer(DBMS_SQLSERVER);
        $this->assertEquals($expected, $result);
    }
    
    public function testGetConfigGridSqlServer_SqlPagination()
    {
        $result = GRID_SQL_PAGINATION;
        $_SESSION[APLICATIVO]['DBMS']['VERSION'] = TableInfo::DBMS_VERSION_SQLSERVER_2012_GTE;
        $expected = TGeneratorHelper::getConfigGridSqlServer(DBMS_SQLSERVER);
        $this->assertEquals($expected, $result);
    }
    
    public function addRowFieldSelectedTable($listFieldSelectedTable
                                            ,$COLUMN_NAME
                                            ,$KEY_TYPE
                                            ,$REFERENCED_TABLE_NAME
                                            ,$REFERENCED_COLUMN_NAME
                                            )
    {
        $listFieldSelectedTable['COLUMN_NAME'][]=$COLUMN_NAME;
        $listFieldSelectedTable['KEY_TYPE'][]=$KEY_TYPE;
        $listFieldSelectedTable['REFERENCED_TABLE_NAME'][]=$REFERENCED_TABLE_NAME;
        $listFieldSelectedTable['REFERENCED_COLUMN_NAME'][]=$REFERENCED_COLUMN_NAME;
        return $listFieldSelectedTable;
    }
    
    public function testRemoveFieldsDuplicateOnSelectedTable_FailNull()
    {
        $this->expectException(InvalidArgumentException::class);
        $listFieldsTable = null;        
        $this->assertNull( TGeneratorHelper::removeFieldsDuplicateOnSelectedTables($listFieldsTable) );
    }
    
    public function testRemoveFieldsDuplicateOnSelectedTable_FailArrayNull()
    {
        $this->expectException(InvalidArgumentException::class);
        $listFieldsTable = array();
        $this->assertNull( TGeneratorHelper::removeFieldsDuplicateOnSelectedTables($listFieldsTable) );
    }
    
    public function testRemoveFieldsDuplicateOnSelectedTable_FailString()
    {
        $this->expectException(InvalidArgumentException::class);
        $listFieldsTable = 'xxx';
        $this->assertNull( TGeneratorHelper::removeFieldsDuplicateOnSelectedTables($listFieldsTable) );
    }
    
    
    public function testRemoveFieldsDuplicateOnSelectedTable_OkNoDuplicate()
    {
        $listFieldsTable = array();
        $listFieldsTable = $this->addRowFieldSelectedTable($listFieldsTable,'idCarro','PK',null,null);
        $listFieldsTable = $this->addRowFieldSelectedTable($listFieldsTable,'idMarca','FK','marca','idMarca');
        $listFieldsTable = $this->addRowFieldSelectedTable($listFieldsTable,'nmCarro',null,null,null);
        $listFieldsTable = $this->addRowFieldSelectedTable($listFieldsTable,'anoCarro',null,null,null);
        
        $expected = $listFieldsTable;
        
        $result = TGeneratorHelper::removeFieldsDuplicateOnSelectedTables($listFieldsTable);
        $sizeResult = CountHelper::count($result['COLUMN_NAME']);
        $this->assertEquals( 4, $sizeResult);
        $this->assertEquals($expected, $result);
    }
    
    /*
    public function testRemoveFieldsDuplicateOnSelectedTable_OkRemoveDuplicate()
    {
        $listFieldsTable = array();
        $listFieldsTable = $this->addRowFieldSelectedTable($listFieldsTable,'idCarro','PK',null,null);
        $listFieldsTable = $this->addRowFieldSelectedTable($listFieldsTable,'idMarca','FK','marca','idMarca');
        $listFieldsTable = $this->addRowFieldSelectedTable($listFieldsTable,'idMarca','UNIQUE','marca','idMarca');
        $listFieldsTable = $this->addRowFieldSelectedTable($listFieldsTable,'nmCarro',null,null,null);
        $listFieldsTable = $this->addRowFieldSelectedTable($listFieldsTable,'anoCarro',null,null,null);
        
        $expected = ArrayHelper::formDinDeleteRowByKeyIndex($listFieldsTable, 2);
        $expected = $expected['formarray'];
        
        $result = TGeneratorHelper::removeFieldsDuplicateOnSelectedTables($listFieldsTable);
        $sizeResult = CountHelper::count($result['COLUMN_NAME']);
        $this->assertEquals( 4, $sizeResult);
        $this->assertEquals($expected, $result);
    }
    */
    
    public function testValidateListTableNames_null()
    {
        $this->expectException(InvalidArgumentException::class);
        TGeneratorHelper::validateListTableNames(null);
    }
    
    public function testValidateListTableNames_string()
    {
        $this->expectException(InvalidArgumentException::class);
        TGeneratorHelper::validateListTableNames('x');
    }
    
    public function testValidateListColumnsProperties_null()
    {
        $this->expectException(InvalidArgumentException::class);
        TGeneratorHelper::validateListColumnsProperties(null);
    }
    
    public function testValidateListColumnsProperties_string()
    {
        $this->expectException(InvalidArgumentException::class);
        TGeneratorHelper::validateListColumnsProperties('x');
    }
}
