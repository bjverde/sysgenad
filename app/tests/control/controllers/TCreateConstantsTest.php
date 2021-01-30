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
class TCreateConstantsTest extends TestCase
{

    /**
     * @var TCreateAutoload
     */
    private $create;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp(): void {
        TSysgenSession::setValue(TableInfo::TP_SYSTEM_THEME, 'formdin5');
        TSysgenSession::setValue(TGeneratorHelper::GEN_SYSTEM_ACRONYM, 'test');
        TSysgenSession::setValue('GEN_SYSTEM_VERSION', '1.2.3');
        TSysgenSession::setValue('GEN_SYSTEM_NAME', 'Mock Test');

        parent::setUp();
        $this->create = new TCreateConstants();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown(): void {
        $this->create = null;        
        parent::tearDown();
    }
    
    public function testAddTheme(){
        $expectedQtd = 1;

        $this->create->addTheme(null);
        $result = $this->create->getLinesArray();
        $sizeResult = CountHelper::count($result);
        $this->assertEquals( $expectedQtd, $sizeResult);
    }
    
    public function testAddGeneral(){
        $expectedQtd = 10;
        $systemAcronym = 'test';

		$expected = array();
		$expected[1] = '[general]'.EOL;
        $expected[2] = 'timezone = America/Sao_Paulo'.EOL;
        $expected[4] = 'application = '.$systemAcronym.EOL;

        $this->create->addGeneral($systemAcronym);
        $result = $this->create->getLinesArray();
        $sizeResult = CountHelper::count($result);

        $this->assertEquals( $expectedQtd, $sizeResult);

        $this->assertSame($expected[1], $result[1]);
        $this->assertSame($expected[2], $result[2]);
        $this->assertSame($expected[4], $result[4]);
    }

    public function testAddSystem(){
        $expectedQtd = 10;
        $systemAcronym = 'test';

		$expected = array();
		$expected[0] = '[system]'.EOL;
        $expected[1] = 'formdin_min_version='.FORMDIN_VERSION.EOL;
        $expected[2] = 'version=1.2.3'.EOL;
        $expected[3] = 'system_name= "Mock Test"'.EOL;

        $this->create->addSystem($systemAcronym);
        $result = $this->create->getLinesArray();
        $sizeResult = CountHelper::count($result);
        
        $this->assertEquals( $expectedQtd, $sizeResult);

        $this->assertSame($expected[0], $result[0]);
        $this->assertSame($expected[1], $result[1]);
        $this->assertSame($expected[2], $result[2]);
        $this->assertSame($expected[3], $result[3]);
    }
}