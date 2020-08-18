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

class TCreateFormTest extends TestCase
{	

	private $create;	
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp(): void {
		parent::setUp ();
		$listColumnsProperties  = array();
		$listColumnsProperties['COLUMN_NAME'][] = 'idTest';
		$listColumnsProperties['COLUMN_NAME'][] = 'nm_test';
		$listColumnsProperties['COLUMN_NAME'][] = 'tip_test';
		$this->create = new TCreateForm('modulos','test',$listColumnsProperties);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown(): void {
		parent::tearDown ();
		$this->create = null;
	}
	
	public function testConvertDataType2FormDinType_DATETIME(){
	    $expected = TCreateForm::FORMDIN_TYPE_DATE;
	    $result = TCreateForm::convertDataType2FormDinType('DATETIME');
	    $this->assertSame($expected, $result);
	}

	public function testConvertDataType2FormDinType_DATETIME_Mysql(){
		$DBMS['TYPE'] = TFormDinPdoConnection::DBMS_MYSQL;
		TSysgenSession::setValue('DBMS',$DBMS);
	    $expected = TCreateForm::FORMDIN_TYPE_DATETIME;
	    $result = TCreateForm::convertDataType2FormDinType('DATETIME');
	    $this->assertSame($expected, $result);
	}
	
	public function testConvertDataType2FormDinType_DATETIME2(){
	    $expected = TCreateForm::FORMDIN_TYPE_DATE;
	    $result = TCreateForm::convertDataType2FormDinType('DATETIME2');
	    $this->assertSame($expected, $result);
	}
	
	public function testConvertDataType2FormDinType_DATE(){
	    $expected = TCreateForm::FORMDIN_TYPE_DATE;
	    $result = TCreateForm::convertDataType2FormDinType('DATE');
	    $this->assertSame($expected, $result);
	}
	
	public function testConvertDataType2FormDinType_TIMESTAMP(){
	    $expected = TCreateForm::FORMDIN_TYPE_DATE;
	    $result = TCreateForm::convertDataType2FormDinType('TIMESTAMP');
	    $this->assertSame($expected, $result);
	}
	
	public function testConvertDataType2FormDinType_BIGINT(){
	    $expected = TCreateForm::FORMDIN_TYPE_NUMBER;
	    $result = TCreateForm::convertDataType2FormDinType('BIGINT');
	    $this->assertSame($expected, $result);
	}
	
	public function testConvertDataType2FormDinType_DECIMAL(){
	    $expected = TCreateForm::FORMDIN_TYPE_NUMBER;
	    $result = TCreateForm::convertDataType2FormDinType('DECIMAL');
	    $this->assertSame($expected, $result);
	}
	
	public function testConvertDataType2FormDinType_DOUBLE(){
	    $expected = TCreateForm::FORMDIN_TYPE_NUMBER;
	    $result = TCreateForm::convertDataType2FormDinType('DOUBLE');
	    $this->assertSame($expected, $result);
	}
	
	public function testConvertDataType2FormDinType_FLOAT(){
	    $expected = TCreateForm::FORMDIN_TYPE_NUMBER;
	    $result = TCreateForm::convertDataType2FormDinType('FLOAT');
	    $this->assertSame($expected, $result);
	}
	
	public function testConvertDataType2FormDinType_INT(){
	    $expected = TCreateForm::FORMDIN_TYPE_NUMBER;
	    $result = TCreateForm::convertDataType2FormDinType('INT');
	    $this->assertSame($expected, $result);
	}
	
	public function testConvertDataType2FormDinType_INT64(){
	    $expected = TCreateForm::FORMDIN_TYPE_NUMBER;
	    $result = TCreateForm::convertDataType2FormDinType('INT64');
	    $this->assertSame($expected, $result);
	}
	
	public function testConvertDataType2FormDinType_INTEGER(){
	    $expected = TCreateForm::FORMDIN_TYPE_NUMBER;
	    $result = TCreateForm::convertDataType2FormDinType('INTEGER');
	    $this->assertSame($expected, $result);
	}
	
	public function testConvertDataType2FormDinType_NUMERIC(){
	    $expected = TCreateForm::FORMDIN_TYPE_NUMBER;
	    $result = TCreateForm::convertDataType2FormDinType('NUMERIC');
	    $this->assertSame($expected, $result);
	}
	
	public function testConvertDataType2FormDinType_NUMBER(){
	    $expected = TCreateForm::FORMDIN_TYPE_NUMBER;
	    $result = TCreateForm::convertDataType2FormDinType('NUMBER');
	    $this->assertSame($expected, $result);
	}
	
	public function testConvertDataType2FormDinType_REAL(){
	    $expected = TCreateForm::FORMDIN_TYPE_NUMBER;
	    $result = TCreateForm::convertDataType2FormDinType('REAL');
	    $this->assertSame($expected, $result);
	}
	
	public function testConvertDataType2FormDinType_SMALLINT(){
	    $expected = TCreateForm::FORMDIN_TYPE_NUMBER;
	    $result = TCreateForm::convertDataType2FormDinType('SMALLINT');
	    $this->assertSame($expected, $result);
	}
	
	public function testConvertDataType2FormDinType_TINYINT(){
	    $expected = TCreateForm::FORMDIN_TYPE_NUMBER;
	    $result = TCreateForm::convertDataType2FormDinType('TINYINT');
	    $this->assertSame($expected, $result);
	}	
	
	public function testShow_Grid_Paginator(){
	    $expectedQtd = 16;
	    
	    $this->create->setGridType(FormDinHelper::GRID_SQL_PAGINATION);
	    $this->create->setTableType(TableInfo::TB_TYPE_TABLE);
	    $this->create->addGrid(null);
	    $result = $this->create->getLinesArray();
	    
	    $size = CountHelper::count($result);
	    $this->assertEquals( $expectedQtd, $size);
	}

	public function testAddMethod_onSave(){
		$expectedSize = 19;
		$qtdTab = null;
		$expected = array();
		$expected[2] = $qtdTab.'public function onSave($param)'.EOL;
		$expected[3] = $qtdTab.'{'.EOL;
	
	    $this->create->addMethod_onSave($qtdTab);
		$result = $this->create->getLinesArray();
		$size = CountHelper::count($result);
	    
		$this->assertSame($expectedSize, $size);
		$this->assertSame($expected[2], $result[2]);
		$this->assertSame($expected[3], $result[3]);
	}	

	public function testAddMethod_onClear(){
	    $expected = 10;
		$qtdTab = ESP.ESP.ESP;
	
	    $this->create->addMethod_onClear($qtdTab);
		$result = $this->create->getLinesArray();
		$size = CountHelper::count($result);
	    
	    $this->assertSame($expected, $size);
	}	

	public function testAddButtons_VIEW(){
		$qtdTab = ESP.ESP.ESP;
	    $expected = array();
	    $expected[] = $qtdTab.'// O Adianti permite a Internacionalização - A função _t(\'string\') serve'.EOL;
	    $expected[] = $qtdTab.'//para traduzir termos no sistema. Veja ApplicationTranslator escrevendo'.EOL;
		$expected[] = $qtdTab.'//primeiro em ingles e depois traduzindo'.EOL;
		$expected[] = $qtdTab.'$frm->setActionLink( _t(\'Clear\'), \'onClear\', null, \'fa:eraser\', \'red\');'.EOL;
	
		$this->create->setTableType(TableInfo::TB_TYPE_VIEW);
	    $this->create->addButtons($qtdTab);
	    $result = $this->create->getLinesArray();
	    
	    $this->assertSame($expected[0], $result[0]);
	    $this->assertSame($expected[1], $result[1]);
		$this->assertSame($expected[2], $result[2]);
		$this->assertSame($expected[3], $result[3]);
	}

	public function testAddButtons_TABLE(){
		$qtdTab = ESP.ESP.ESP;
	    $expected = array();
	    $expected[] = $qtdTab.'// O Adianti permite a Internacionalização - A função _t(\'string\') serve'.EOL;
	    $expected[] = $qtdTab.'//para traduzir termos no sistema. Veja ApplicationTranslator escrevendo'.EOL;
		$expected[] = $qtdTab.'//primeiro em ingles e depois traduzindo'.EOL;
		$expected[] = $qtdTab.'$frm->setAction( _t(\'Save\'), \'onSave\', null, \'fa:save\', \'green\' );'.EOL;
		$expected[] = $qtdTab.'$frm->setActionLink( _t(\'Clear\'), \'onClear\', null, \'fa:eraser\', \'red\');'.EOL;
	
		$this->create->setTableType(TableInfo::TB_TYPE_TABLE);
	    $this->create->addButtons($qtdTab);
	    $result = $this->create->getLinesArray();
	    
	    $this->assertSame($expected[0], $result[0]);
	    $this->assertSame($expected[1], $result[1]);
		$this->assertSame($expected[2], $result[2]);
		$this->assertSame($expected[3], $result[3]);
		$this->assertSame($expected[4], $result[4]);
	}
	
	public function testShow_VIEW(){
		$expectedQtd = 92;

	    $expected = array();
		$expected[12] = 'class testForm extends TPage'.EOL;
		$expected[15] = ESP.'protected $form; // registration form'.EOL;
		$expected[16] = ESP.'protected $datagrid; // listing'.EOL;
		$expected[17] = ESP.'protected $pageNavigation;'.EOL;
	    
		$this->create->setTableType(TableInfo::TB_TYPE_VIEW);
		$resultArray = $this->create->show('array');
		$size = CountHelper::count($resultArray);
		
		$this->assertEquals( $expectedQtd, $size );
		$this->assertSame($expected[12], $resultArray[12]);
		$this->assertSame($expected[15], $resultArray[15]);
		$this->assertSame($expected[16], $resultArray[16]);
		$this->assertSame($expected[17], $resultArray[17]);
	}
	
	public function testShow_TABLE(){
		$expectedQtd = 82;

	    $expected = array();
		$expected[12] = 'class testForm extends TPage'.EOL;
		$expected[15] = ESP.'protected $form; // registration form'.EOL;
		$expected[16] = ESP.'protected $datagrid; // listing'.EOL;
		$expected[17] = ESP.'protected $pageNavigation;'.EOL;
		
		$this->create->setTableType(TableInfo::TB_TYPE_TABLE);
		$resultArray = $this->create->show('array');
		$size = CountHelper::count($resultArray);
		
		$this->assertEquals( $expectedQtd, $size );
		$this->assertSame($expected[12], $resultArray[12]);
		$this->assertSame($expected[15], $resultArray[15]);
		$this->assertSame($expected[16], $resultArray[16]);
		$this->assertSame($expected[17], $resultArray[17]);
	}
}