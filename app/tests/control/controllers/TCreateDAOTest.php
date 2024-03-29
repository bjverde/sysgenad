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
 * TCreateDAO test case.
 */
class TCreateDAOTest extends TestCase
{	

	private $create;
	
	private $mockDatabase;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp(): void {
		parent::setUp ();
		$this->mockDatabase = new mockDatabase();
		$listColumnsProperties = $this->mockDatabase->generateFieldsOneTable();
		$this->create = new TCreateDAO('xx/dao','test',$listColumnsProperties);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown(): void {
		parent::tearDown ();
		$this->create = null;
	}
	
	public function testGetKeyColumnName() {
	    $expected = 'idtest';
		$result = $this->create->getKeyColumnName();		
		$this->assertSame($expected, $result);
	}

	public function testGetColumnPKeyPropertieFormDinType() {
	    $expected = TCreateForm::FORMDIN_TYPE_NUMBER;
		$result = $this->create->getColumnPKeyPropertieFormDinType();		
		$this->assertSame($expected, $result);
	}

	public function testAddExecuteSql_Empty() {
	    $esperado = array();
	    $esperado[] = ESP.ESP.'$result = $this->tpdo->executeSql($sql);'.EOL;
	    $esperado[] = ESP.ESP.'return $result;'.EOL;
				
		$this->create->addExecuteSql();		
		$retorno = $this->create->getLinesArray();		
		$this->assertSame($esperado[0], $retorno[0]);
		$this->assertSame($esperado[1], $retorno[1]);
	}
	
	public function testAddExecuteSql_true() {
	    $esperado = array();
	    $esperado[] = ESP.ESP.'$result = $this->tpdo->executeSql($sql, $values);'.EOL;
	    $esperado[] = ESP.ESP.'return $result;'.EOL;
	    
	    $this->create->addExecuteSql(true);
	    $retorno = $this->create->getLinesArray();
	    $this->assertSame($esperado[0], $retorno[0]);
	    $this->assertSame($esperado[1], $retorno[1]);
	}
	
	public function testAddGetVoById(){
	    $expected = array();
	    $expected[] = ESP.'//--------------------------------------------------------------------------------'.EOL;
	    $expected[] = ESP.'public function getVoById( $id )'.EOL;
	    $expected[] = ESP.'{'.EOL;
	    $expected[] = ESP.ESP.'FormDinHelper::validateIdIsNumeric($id,__METHOD__,__LINE__);'.EOL;
	    $expected[] = ESP.ESP.'$result = $this->selectById( $id );'.EOL;
	    $expected[] = ESP.ESP.'$result = \ArrayHelper::convertArrayFormDin2Pdo($result,false);'.EOL;
	    $expected[] = ESP.ESP.'$result = $result[0];'.EOL;
	    $expected[] = ESP.ESP.'$vo = new TestVO();'.EOL;
	    $expected[] = ESP.ESP.'$vo = \FormDinHelper::setPropertyVo($result,$vo);'.EOL;
	    $expected[] = ESP.ESP.'return $vo;'.EOL;
	    $expected[] = ESP.'}'.EOL;
	    
	    $this->create->addGetVoById();
	    $result = $this->create->getLinesArray();
	    $this->assertSame($expected[0], $result[0]);
	    $this->assertSame($expected[1], $result[1]);
	    $this->assertSame($expected[2], $result[2]);
	    $this->assertSame($expected[3], $result[3]);
	    $this->assertSame($expected[4], $result[4]);
	    $this->assertSame($expected[5], $result[5]);
	    $this->assertSame($expected[6], $result[6]);
	    $this->assertSame($expected[7], $result[7]);
	    $this->assertSame($expected[8], $result[8]);
	    $this->assertSame($expected[9], $result[9]);
	    $this->assertSame($expected[10], $result[10]);
	}
	
	public function testAddSqlSelectAll(){
		$expected = array();
		$expected[] = ESP.'//--------------------------------------------------------------------------------'.EOL;
        $expected[] = ESP.'/**'.EOL;
        $expected[] = ESP.' * Faz um Select SQL nativo, sem paginação'.EOL;
        $expected[] = ESP.' * @param string $orderBy - 01: criterio de ordenação'.EOL;
        $expected[] = ESP.' * @param array  $where   - 02: array PHP "NOME_COLUNA1=>VALOR,NOME_COLUNA1=>VALOR" que será usado na consulta no metodo processWhereGridParameters'.EOL;
        $expected[] = ESP.' * @return array Adianti'.EOL;
        $expected[] = ESP.' */'.EOL;
	    $expected[] = ESP.'public function selectAll( $orderBy=null, $where=null )'.EOL;
	    $expected[] = ESP.'{'.EOL;
	    $expected[] = ESP.ESP.'$where = $this->processWhereGridParameters($where);'.EOL;
	    $expected[] = ESP.ESP.'$sql = self::$sqlBasicSelect'.EOL;
	    $expected[] = ESP.ESP.'.( ($where)? \' where \'.$where:\'\')'.EOL;
	    $expected[] = ESP.ESP.'.( ($orderBy) ? \' order by \'.$orderBy:\'\');'.EOL;
	    
	    $this->create->addSqlSelectAll();
	    $result = $this->create->getLinesArray();
	    $this->assertSame($expected[7], $result[7]);
	    $this->assertSame($expected[8], $result[8]);
	    $this->assertSame($expected[9], $result[9]);
	    $this->assertSame($expected[10], $result[10]);
	    $this->assertSame($expected[11], $result[11]);
	    $this->assertSame($expected[12], $result[12]);
	}
	
	public function testAddSqlInsert_numLines(){
	    $expectedQtd = 13;
	    
	    $this->create->addSqlInsert();
	    $resultArray = $this->create->getLinesArray();
	    $size = CountHelper::count($resultArray);
	    $this->assertEquals( $expectedQtd, $size);
	}
	
	public function testAddSqlInsert(){
	    $expected = array();
	    $expected[] = ESP.'public function insert( TestVO $objVo )'.EOL;
	    $expected[] = ESP.'{'.EOL;
	    $expected[] = ESP.ESP.'$values = array(';
	    $expected[] = '  $objVo->getNm_test() '.EOL;
	    $expected[] = ESP.ESP.'                , $objVo->getTip_test() '.EOL;
	    $expected[] = ESP.ESP.'                );'.EOL;
	    $expected[] = ESP.ESP.'$sql = \'insert into test('.EOL;
	    
	    $this->create->addSqlInsert();
	    $result = $this->create->getLinesArray();
	    $this->assertSame($expected[0], $result[0]);
	    $this->assertSame($expected[1], $result[1]);
	    $this->assertSame($expected[2], $result[2]);
	    $this->assertSame($expected[3], $result[3]);
	    $this->assertSame($expected[4], $result[4]);
	    $this->assertSame($expected[5], $result[5]);
	}
	
	public function testAddSqlDelete_numLines(){
	    $expectedQtd = 8;
	    
	    $this->create->addSqlDelete();
	    $resultArray = $this->create->getLinesArray();
	    $size = CountHelper::count($resultArray);
	    $this->assertEquals( $expectedQtd, $size);
	}
	
	public function testAddSqlDelete() {
	    $expected = array();
	    $expected[] = ESP.'public function delete( $id )'.EOL;
	    $expected[] = ESP.'{'.EOL;
	    $expected[] = ESP.ESP.'FormDinHelper::validateIdIsNumeric($id,__METHOD__,__LINE__);'.EOL;
	    $expected[] = ESP.ESP.'$values = array($id);'.EOL;
	    $expected[] = ESP.ESP.'$sql = \'delete from test where idtest = ?\';'.EOL;
	    $expected[] = ESP.ESP.'$result = $this->tpdo->executeSql($sql, $values);'.EOL;
	    $expected[] = ESP.ESP.'return $result;'.EOL;
	    $expected[] = ESP.'}'.EOL;
	    
	    $this->create->addSqlDelete();
	    $result = $this->create->getLinesArray();
	    $this->assertSame($expected[0], $result[0]);
	    $this->assertSame($expected[1], $result[1]);
	    $this->assertSame($expected[2], $result[2]);
	    $this->assertSame($expected[3], $result[3]);
	    $this->assertSame($expected[4], $result[4]);
	    $this->assertSame($expected[5], $result[5]);
	    $this->assertSame($expected[6], $result[6]);
	    $this->assertSame($expected[7], $result[7]);
	}

	public function testAddConstruct_numLines(){
	    $expectedQtd = 34;
		
		$this->create->addConstruct();
	    $resultArray = $this->create->getLinesArray();
	    $size = CountHelper::count($resultArray);
	    $this->assertEquals( $expectedQtd, $size);
	}

	public function testAddConstruct(){
	    $expected = array();
		$expected[] = ESP.'private $tpdo = null;'.EOL;
		$expected[] = ESP.'private $repositoryName = \'test\'; //Nome da Classe do tipo Active Record no diretorio /app/model/maindatabase'.EOL;
		$expected[] = ESP.'private $dataBaseName = null;'.EOL;
		$expected[] = EOL;
		$expected[] = ESP.'public function __construct($tpdo=null)'.EOL;
		$expected[] = ESP.'{'.EOL;
		$expected[] = ESP.ESP.'//FormDinHelper::validateObjTypeTPDOConnectionObj($tpdo,__METHOD__,__LINE__);'.EOL;
		$expected[] = ESP.ESP.'if( empty($tpdo) ){'.EOL;
		$expected[] = ESP.ESP.ESP.'//$tpdo = New TPDOConnectionObj(); //FomDin4'.EOL;
		$expected[] = ESP.ESP.ESP.'$tpdo = New TFormDinPdoConnection(\'maindatabase\');'.EOL;
		$expected[] = ESP.ESP.'}'.EOL;
		$expected[] = ESP.ESP.'$this->setTPDOConnection($tpdo);'.EOL;
		$expected[] = ESP.'}'.EOL;
		$expected[] = ESP.'public function getTPDOConnection()'.EOL;
		
		$this->create->addConstruct();
	    $resultArray = $this->create->getLinesArray();
	    $this->assertSame($expected[0], $resultArray[0]);
	    $this->assertSame($expected[1], $resultArray[1]);
	    $this->assertSame($expected[2], $resultArray[2]);
	    $this->assertSame($expected[3], $resultArray[3]);
	    $this->assertSame($expected[4], $resultArray[4]);
	    $this->assertSame($expected[5], $resultArray[5]);
	    $this->assertSame($expected[6], $resultArray[6]);
		$this->assertSame($expected[7], $resultArray[7]);
		$this->assertSame($expected[8], $resultArray[8]);
		$this->assertSame($expected[9], $resultArray[9]);
		$this->assertSame($expected[10], $resultArray[10]);
		$this->assertSame($expected[11], $resultArray[11]);
	}
	
	public function testShow_VIEW_numLines(){
	    $expectedQtd = 146;
	    
	    $this->create->setTableType(TableInfo::TB_TYPE_VIEW);
	    $resultArray = $this->create->show('array');
	    $size = CountHelper::count($resultArray);
	    $this->assertEquals( $expectedQtd, $size);
	}
	
	public function testShow_VIEW_GRID_SQL_numLines(){
	    $expectedQtd = 165;
	    
	    $this->create->setWithSqlPagination(FormDinHelper::GRID_SQL_PAGINATION);
	    $this->create->setTableType(TableInfo::TB_TYPE_VIEW);
	    $resultArray = $this->create->show('array');
	    $size = CountHelper::count($resultArray);
	    $this->assertEquals( $expectedQtd, $size);
	}
	
	public function testShow_TABLE_numLines(){
	    $expectedQtd = 183;	    
	    
	    $this->create->setTableType(TableInfo::TB_TYPE_TABLE);
	    $resultArray = $this->create->show('array');
	    $size = CountHelper::count($resultArray);
	    $this->assertEquals( $expectedQtd, $size);
	}
	
	public function testShow_TABLE_GRID_SQL_numLines(){
	    $expectedQtd = 202;
	    
	    $this->create->setTableType(TableInfo::TB_TYPE_TABLE);
	    $this->create->setWithSqlPagination(FormDinHelper::GRID_SQL_PAGINATION);
	    $resultArray = $this->create->show('array');
	    $size = CountHelper::count($resultArray);
	    $this->assertEquals( $expectedQtd, $size);
	}
	
	public function testShow(){
	    $expected = array();
	    $expected[11] = 'class TestDAO '.EOL;
	    $expected[12] = '{'.EOL;
	    
	    $resultArray = $this->create->show('array');
	    $this->assertSame($expected[11], $resultArray[11]);
	    $this->assertSame($expected[12], $resultArray[12]);
	}
}