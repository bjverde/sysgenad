<?php
/**
 * SysGen - System Generator with Formdin Framework
 * Download Formdin Framework: https://github.com/bjverde/formDin
 *
 * @author  Bjverde <bjverde@yahoo.com.br>
 * @license https://github.com/bjverde/sysgen/blob/master/LICENSE GPL-3.0
 * @link    https://github.com/bjverde/sysgen
 *
 * PHP Version 7.0
 */

$path = __DIR__.'/../../';
require_once $path.'mockDatabase.php';

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Error\Warning;

class TableInfoTest extends TestCase
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

    public function testGetPreDBMS_MySql()
    {
        $result = 'my';
        $expected = TableInfo::getPreDBMS(TFormDinPdoConnection::DBMS_MYSQL);
        $this->assertEquals($expected, $result);
    }

    public function testGetPreDBMS_SqLite()
    {
        $result = 'sq';
        $expected = TableInfo::getPreDBMS(TFormDinPdoConnection::DBMS_SQLITE);
        $this->assertEquals($expected, $result);
    }

    public function testGetPreDBMS_SqlServer()
    {
        $result = 'ss';
        $expected = TableInfo::getPreDBMS(TFormDinPdoConnection::DBMS_SQLSERVER);
        $this->assertEquals($expected, $result);
    }

    public function testGetPreDBMS_PostGreSql()
    {
        $result = 'pg';
        $expected = TableInfo::getPreDBMS(TFormDinPdoConnection::DBMS_POSTGRES);
        $this->assertEquals($expected, $result);
    }
    //--------------------------------------------------------
    public function testGetDbmsWithVersion_MySql(){
        $result = false;
        $expected = TableInfo::getDbmsWithVersion(TFormDinPdoConnection::DBMS_MYSQL);
        $this->assertEquals($expected, $result);
    }

    public function testGetDbmsWithVersion_SqLite(){
        $result = false;
        $expected = TableInfo::getDbmsWithVersion(TFormDinPdoConnection::DBMS_SQLITE);
        $this->assertEquals($expected, $result);
    }

    public function testGetDbmsWithVersion_SqlServer(){
        $result = true;
        $expected = TableInfo::getDbmsWithVersion(TFormDinPdoConnection::DBMS_SQLSERVER);
        $this->assertEquals($expected, $result);
    }
    
}