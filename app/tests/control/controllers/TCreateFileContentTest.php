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

/**
 * TDAOCreate test case.
 */
class TCreateFileContentTest extends TestCase
{

    /**
     * @var TCreateAutoload
     */
    private $create;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp(): void {
        TSysgenSession::setValue('GEN_SYSTEM_ACRONYM', 'test');
        parent::setUp();
        $this->create = new TCreateFileContent();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown(): void {
        $this->create = null;        
        parent::tearDown();
    }
    
    public function testGetGenSystemAcronym(){
        $expected = 'test';        
        $result = $this->create->getGenSystemAcronym();
        $this->assertEquals( $expected, $result);
    }
    
    public function testAddSysGenHeaderNote_numLines(){
        $expectedQtd = 10;

        $this->create->addSysGenHeaderNote();
        $result = $this->create->getLinesArray();
        $sizeResult = CountHelper::count($result);
        $this->assertEquals( $expectedQtd, $sizeResult);
    }
    
    public function testShowContent_getArray(){
        $expectedQtd = 10;
        
        $this->create->addSysGenHeaderNote();
        $result = $this->create->showContent('array');
        $sizeResult = CountHelper::count($result);
        $this->assertEquals( $expectedQtd, $sizeResult);
    }
    
    public function testShowContent_string(){
        $expected = true;
        
        $this->create->addSysGenHeaderNote();
        $result = $this->create->showContent();
        $result = is_string($result);
        $this->assertEquals( $expected, $result);
    }
}