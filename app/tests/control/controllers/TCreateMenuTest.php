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

class TCreateMenuTest extends TestCase
{
    
    /**
     * @var TCreateMenu
     */
    private $createMenu;

    private $mockDatabase;
    
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp(): void {
        parent::setUp();
        if (!defined('ROOT_PATH')) {
            define('ROOT_PATH', "pasta");
        }
        if (!defined('APLICATIVO')) {
            define('APLICATIVO', 'PHPUnit');
        }
        $_SESSION[APLICATIVO][TGeneratorHelper::GEN_SYSTEM_ACRONYM]='test';
        
        //$listTableNames   = $this->generateTablesSelected();
        $this->createMenu = new TCreateMenu(null);
        $this->mockDatabase = new mockDatabase();
    }
    
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown(): void {
        $this->createMenu = null;
        $this->mockDatabase = null;
        parent::tearDown();
    }
    
    public function testTypeTableExist_tablesTrue()
    {
        $expected = true;
        $listTableNames   = $this->mockDatabase->generateTablesSelected3t5v2p();
        $this->createMenu->setListTableNames($listTableNames);
        $result = $this->createMenu->typeTableExist(TableInfo::TB_TYPE_TABLE);
        $this->assertEquals($expected, $result);
    }
    
    public function testTypeTableExist_tablesFalseOnlyViews()
    {
        $expected = false;
        $listTableNames   = $this->mockDatabase->generateTablesSelected5v();
        $this->createMenu->setListTableNames($listTableNames);
        $result = $this->createMenu->typeTableExist(TableInfo::TB_TYPE_TABLE);
        $this->assertEquals($expected, $result);
    }
    
    public function testTypeTableExist_tablesFalseOnlyProcedure()
    {
        $expected = false;
        $listTableNames   = $this->mockDatabase->generateTablesSelected2p();
        $this->createMenu->setListTableNames($listTableNames);
        $result = $this->createMenu->typeTableExist(TableInfo::TB_TYPE_TABLE);
        $this->assertEquals($expected, $result);
    }
    
    
    public function testTypeTableExist_viewTrue()
    {
        $expected = true;
        $listTableNames   = $this->mockDatabase->generateTablesSelected3t5v2p();
        $this->createMenu->setListTableNames($listTableNames);
        $result = $this->createMenu->typeTableExist(TableInfo::TB_TYPE_VIEW);
        $this->assertEquals($expected, $result);
    }
    
    public function testTypeTableExist_viewFalseOnlyTable()
    {
        $expected = false;
        $listTableNames   = $this->mockDatabase->generateTablesSelected3t();
        $this->createMenu->setListTableNames($listTableNames);
        $result = $this->createMenu->typeTableExist(TableInfo::TB_TYPE_VIEW);
        $this->assertEquals($expected, $result);
    }
    
    public function testTypeTableExist_viewFalseOnlyProcedure()
    {
        $expected = false;
        $listTableNames   = $this->mockDatabase->generateTablesSelected2p();
        $this->createMenu->setListTableNames($listTableNames);
        $result = $this->createMenu->typeTableExist(TableInfo::TB_TYPE_VIEW);
        $this->assertEquals($expected, $result);
    }
    
    public function testTypeTableExist_procedureTrue()
    {
        $expected = true;
        $listTableNames   = $this->mockDatabase->generateTablesSelected3t5v2p();
        $this->createMenu->setListTableNames($listTableNames);
        $result = $this->createMenu->typeTableExist(TableInfo::TB_TYPE_PROCEDURE);
        $this->assertEquals($expected, $result);
    }
    
    public function testTypeTableExist_procedureFalseOnlyView()
    {
        $expected = false;
        $listTableNames   = $this->mockDatabase->generateTablesSelected5v();
        $this->createMenu->setListTableNames($listTableNames);
        $result = $this->createMenu->typeTableExist(TableInfo::TB_TYPE_PROCEDURE);
        $this->assertEquals($expected, $result);
    }
    
    public function testTypeTableExist_procedureFalseOnlyTable()
    {
        $expected = false;
        $listTableNames   = $this->mockDatabase->generateTablesSelected3t();
        $this->createMenu->setListTableNames($listTableNames);
        $result = $this->createMenu->typeTableExist(TableInfo::TB_TYPE_PROCEDURE);
        $this->assertEquals($expected, $result);
    }
    
    public function testAddBasicMenuCruds_3tables()
    {
        $expected = 17; //3 Tables + 1 header line
        $listTableNames   = $this->mockDatabase->generateTablesSelected3t5v();
        $this->createMenu->setListTableNames($listTableNames);
        $this->createMenu->addBasicMenuCruds();
        $resultArray = $this->createMenu->getLinesArray();
        $size = CountHelper::count($resultArray);
        $this->assertEquals($expected, $size);
    }
    
    public function testAddBasicMenuCruds_1tables()
    {
        $expected = 9; //1 Tables + 1 header line
        $listTableNames   = $this->mockDatabase->generateTablesSelected1t7v();
        $this->createMenu->setListTableNames($listTableNames);
        $this->createMenu->addBasicMenuCruds();
        $resultArray = $this->createMenu->getLinesArray();
        $size = CountHelper::count($resultArray);
        $this->assertEquals($expected, $size);
    }
    
    public function testAddBasicMenuViews_5Views()
    {
        $expected = 25; //5 Views + 1 header line
        $listTableNames   = $this->mockDatabase->generateTablesSelected3t5v();
        $this->createMenu->setListTableNames($listTableNames);
        $this->createMenu->addBasicMenuViews();
        $resultArray = $this->createMenu->getLinesArray();
        $size = CountHelper::count($resultArray);
        $this->assertEquals($expected, $size);
    }
    
    public function testAddBasicMenuViews_7Views()
    {
        $expected = 33; //7 Views + 1 header line
        $listTableNames   = $this->mockDatabase->generateTablesSelected1t7v();
        $this->createMenu->setListTableNames($listTableNames);
        $this->createMenu->addBasicMenuViews();
        $resultArray = $this->createMenu->getLinesArray();
        $size = CountHelper::count($resultArray);
        $this->assertEquals($expected, $size);
    }

    public function testAddBasicMenuViews_2StoredProcedure()
    {
        $expected = 13; //2 SP + 1 header line
        $listTableNames   = $this->mockDatabase->generateTablesSelected3t5v2p();
        $this->createMenu->setListTableNames($listTableNames);
        $this->createMenu->addBasicMenuProcedure();
        $resultArray = $this->createMenu->getLinesArray();
        $size = CountHelper::count($resultArray);
        $this->assertEquals($expected, $size);
    }
}
