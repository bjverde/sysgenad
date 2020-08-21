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

class EasyLabelTest extends TestCase
{	

	private $create;	
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp(): void {
		parent::setUp ();
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown(): void {
		parent::tearDown ();
	}

	public function testConvertUpper_dt_ok()
	{
	    $expected ='Data Inclusao';
	    $typeField = TCreateForm::FORMDIN_TYPE_DATE;
	    $stringLabel = 'DTINCLUSAO';
	    $result = EasyLabel::convert_dt($stringLabel, $typeField);
	    $this->assertEquals($expected, $result);
	}
	
	public function testConvertUpper_dt_NotMach()
	{
	    $expected ='xyz';
	    $typeField = TCreateForm::FORMDIN_TYPE_DATE;
	    $stringLabel = 'xyz';
	    $result = EasyLabel::convert_dt($stringLabel, $typeField);
	    $this->assertEquals($expected, $result);
	}
	
	public function testConvertUpper_dt_TypeWrong()
	{
	    $expected ='xyz';
	    $typeField = TCreateForm::FORMDIN_TYPE_TEXT;
	    $stringLabel = 'xyz';
	    $result = EasyLabel::convert_dt($stringLabel, $typeField);
	    $this->assertEquals($expected, $result);
	}

	public function testConvertUpper_nm_ok()
	{
	    $expected ='DTINCLUSAO';
	    $typeField = TCreateForm::FORMDIN_TYPE_TEXT;
	    $stringLabel = 'DTINCLUSAO';
	    $result = EasyLabel::convert_nm($stringLabel, $typeField);
	    $this->assertEquals($expected, $result);
	}
	
	public function testConvertUpper_nm_NotMach()
	{
	    $expected ='Nome Pessoa';
	    $typeField = TCreateForm::FORMDIN_TYPE_TEXT;
	    $stringLabel = 'NMPESSOA';
	    $result = EasyLabel::convert_nm($stringLabel, $typeField);
	    $this->assertEquals($expected, $result);
	}
	
	public function testConvertUpper_nm_TypeWrong()
	{
	    $expected ='xyz';
	    $typeField = TCreateForm::FORMDIN_TYPE_DATE;
	    $stringLabel = 'xyz';
	    $result = EasyLabel::convert_nm($stringLabel, $typeField);
	    $this->assertEquals($expected, $result);
	}
	
	public function testConvertLabelUpper_NotUse()
	{
	    $expected ='DSTIPO';
	    $typeField = TCreateForm::FORMDIN_TYPE_TEXT;
	    $stringLabel = 'DSTIPO';
		TSysgenSession::setValue('EASYLABEL','N');
	    $result = EasyLabel::convertLabel($stringLabel, $typeField);
	    $this->assertEquals($expected, $result);
	}
	
	public function testConvertLabelUpper_TypeDate_inclusao()
	{
	    $expected ='Data Inclusão';
	    $typeField = TCreateForm::FORMDIN_TYPE_DATE;
	    $stringLabel = 'DTINCLUSAO';
		TSysgenSession::setValue('EASYLABEL','Y');
	    $result = EasyLabel::convertLabel($stringLabel, $typeField);
	    $this->assertEquals($expected, $result);
	}
	
	public function testConvertLabelUpper_TypeDate_altercao()
	{
	    $expected ='Data Alteração';
	    $typeField = TCreateForm::FORMDIN_TYPE_DATE;
	    $stringLabel = 'DTALTERACAO';
	    TSysgenSession::setValue('EASYLABEL','Y');
	    $result = EasyLabel::convertLabel($stringLabel, $typeField);
	    $this->assertEquals($expected, $result);
	}
	
	public function testConvertLabelUpper_TypeName_inclusao()
	{
	    $expected ='Nome Pessoa';
	    $typeField = TCreateForm::FORMDIN_TYPE_TEXT;
	    $stringLabel = 'NMPESSOA';
	    TSysgenSession::setValue('EASYLABEL','Y');
	    $result = EasyLabel::convertLabel($stringLabel, $typeField);
	    $this->assertEquals($expected, $result);
	}
	
	public function testConvertLabelUpper_TypeName_altercao()
	{
	    $expected ='Nome Tipo';
	    $typeField = TCreateForm::FORMDIN_TYPE_TEXT;
	    $stringLabel = 'NMTIPO';
	    TSysgenSession::setValue('EASYLABEL','Y');
	    $result = EasyLabel::convertLabel($stringLabel, $typeField);
	    $this->assertEquals($expected, $result);
	}
	
	public function testConvertLabelUpper_TypeText_Descricao()
	{
	    $expected ='Descrição Tipo';
	    $typeField = TCreateForm::FORMDIN_TYPE_TEXT;
	    $stringLabel = 'DSTIPO';
	    TSysgenSession::setValue('EASYLABEL','Y');
	    $result = EasyLabel::convertLabel($stringLabel, $typeField);
	    $this->assertEquals($expected, $result);
	}
	
	public function testConvertLabelUpper_EndSao()
	{
	    $expected ='Data Inclusão';
	    $typeField = TCreateForm::FORMDIN_TYPE_DATE;
	    $stringLabel = 'DTINCLUSAO';
	    TSysgenSession::setValue('EASYLABEL','Y');
	    $result = EasyLabel::convertLabel($stringLabel, $typeField);
	    $this->assertEquals($expected, $result);
	}
	
	public function testConvertLabelUpper_EndCao()
	{
	    $expected ='Data Alteração';
	    $typeField = TCreateForm::FORMDIN_TYPE_DATE;
	    $stringLabel = 'DTALTERACAO';
	    TSysgenSession::setValue('EASYLABEL','Y');
	    $result = EasyLabel::convertLabel($stringLabel, $typeField);
	    $this->assertEquals($expected, $result);
	}
	
	public function testConvertLabelUpper_EndGao()
	{
	    $expected ='Orgão';
	    $typeField = TCreateForm::FORMDIN_TYPE_TEXT;
	    $stringLabel = 'ORGAO';
	    TSysgenSession::setValue('EASYLABEL','Y');
	    $result = EasyLabel::convertLabel($stringLabel, $typeField);
	    $this->assertEquals($expected, $result);
	}

	public function testConvertLabelLower_EndGao()
	{
	    $expected ='Orgão';
	    $typeField = TCreateForm::FORMDIN_TYPE_TEXT;
	    $stringLabel = 'orgao';
	    TSysgenSession::setValue('EASYLABEL','Y');
	    $result = EasyLabel::convertLabel($stringLabel, $typeField);
	    $this->assertEquals($expected, $result);
	}

	public function testConvertLabelUpper_qt()
	{
	    $expected ='Quantidade Dias';
	    $typeField = TCreateForm::FORMDIN_TYPE_NUMBER;
	    $stringLabel = 'QTDIAS';
	    TSysgenSession::setValue('EASYLABEL','Y');
	    $result = EasyLabel::convertLabel($stringLabel, $typeField);
	    $this->assertEquals($expected, $result);
	}

	public function testConvertLabelLower_qt()
	{
	    $expected ='Quantidade Dias';
	    $typeField = TCreateForm::FORMDIN_TYPE_NUMBER;
	    $stringLabel = 'qtdias';
	    TSysgenSession::setValue('EASYLABEL','Y');
	    $result = EasyLabel::convertLabel($stringLabel, $typeField);
	    $this->assertEquals($expected, $result);
	}
	
	public function testConvertLabelUpper_idPessoa()
	{
	    $expected ='Id Pessoa';
	    $typeField = TCreateForm::FORMDIN_TYPE_NUMBER;
	    $stringLabel = 'IDPESSOA';
	    TSysgenSession::setValue('EASYLABEL','Y');
	    $result = EasyLabel::convertLabel($stringLabel, $typeField);
	    $this->assertEquals($expected, $result);
	}
	
	public function testConvertLabelUpper_idAcao()
	{
	    $expected ='Id Ação';
	    $typeField = TCreateForm::FORMDIN_TYPE_NUMBER;
	    $stringLabel = 'IDACAO';
	    TSysgenSession::setValue('EASYLABEL','Y');
	    $result = EasyLabel::convertLabel($stringLabel, $typeField);
	    $this->assertEquals($expected, $result);
	}
	
	public function testConvertLabelUpper_idUnidade()
	{
	    $expected ='Id Unidade';
	    $typeField = TCreateForm::FORMDIN_TYPE_NUMBER;
	    $stringLabel = 'IDUNIDADE';
	    TSysgenSession::setValue('EASYLABEL','Y');
	    $result = EasyLabel::convertLabel($stringLabel, $typeField);
	    $this->assertEquals($expected, $result);
	}
	
	public function testConvertLabelUpper_st()
	{
	    $expected ='Status Ação';
	    $typeField = TCreateForm::FORMDIN_TYPE_TEXT;
	    $stringLabel = 'STACAO';
	    TSysgenSession::setValue('EASYLABEL','Y');
	    $result = EasyLabel::convertLabel($stringLabel, $typeField);
	    $this->assertEquals($expected, $result);
	}
	
	public function testConvertLabelUpper_nr()
	{
	    $expected ='Número Otrs';
	    $typeField = TCreateForm::FORMDIN_TYPE_NUMBER;
	    $stringLabel = 'NROTRS';
	    TSysgenSession::setValue('EASYLABEL','Y');
	    $result = EasyLabel::convertLabel($stringLabel, $typeField);
	    $this->assertEquals($expected, $result);
	}
	
	public function testRemoveUnderlineUpper_um()
	{
	    $expected ='Id Ano';
	    $typeField = TCreateForm::FORMDIN_TYPE_NUMBER;
	    $stringLabel = 'ID_ANO';
	    TSysgenSession::setValue('EASYLABEL','Y');
	    $result = EasyLabel::convertLabel($stringLabel, $typeField);
	    $this->assertEquals($expected, $result);
	}

	public function testRemoveUnderlineLower_um()
	{
	    $expected ='Id Ano';
	    $typeField = TCreateForm::FORMDIN_TYPE_NUMBER;
	    $stringLabel = 'id_ano';
	    TSysgenSession::setValue('EASYLABEL','Y');
	    $result = EasyLabel::convertLabel($stringLabel, $typeField);
	    $this->assertEquals($expected, $result);
	}

	public function testRemoveUnderlineUpper_varios()
	{
	    $expected ='Dias Do Ano';
	    $typeField = TCreateForm::FORMDIN_TYPE_NUMBER;
	    $stringLabel = 'DIAS_DO_ANO';
	    TSysgenSession::setValue('EASYLABEL','Y');
	    $result = EasyLabel::convertLabel($stringLabel, $typeField);
	    $this->assertEquals($expected, $result);
	}	

	public function testRemoveUnderlineLower_varios()
	{
	    $expected ='Dias Do Ano';
	    $typeField = TCreateForm::FORMDIN_TYPE_NUMBER;
	    $stringLabel = 'dias_do_ano';
	    TSysgenSession::setValue('EASYLABEL','Y');
	    $result = EasyLabel::convertLabel($stringLabel, $typeField);
	    $this->assertEquals($expected, $result);
	}

}