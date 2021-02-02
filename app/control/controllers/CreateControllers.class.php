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

class CreateControllers extends TCreateFileContent
{
    private $tableRef;
    private $tableRefDAO;
    private $tableRefVO;
    private $withSqlPagination;
    private $listColumnsName;
    private $listColumnsProperties;
    private $tableType = null;
    
    public function __construct($tableRef)
    {
        $tableRef = ucfirst(strtolower($tableRef));
        $this->tableRef   = $tableRef.'Controller';
        $this->tableRefDAO= $tableRef.'DAO';
        $this->tableRefVO = $tableRef.'VO';
        $this->setFileName($tableRef.'Controller.class.php');
    }
    //------------------------------------------------------------------------------------
    public function setWithSqlPagination($withSqlPagination)
    {
        return $this->withSqlPagination = $withSqlPagination;
    }
    public function getWithSqlPagination()
    {
        return $this->withSqlPagination;
    }
    //--------------------------------------------------------------------------------------
    public function setListColunnsName($listColumnsName)
    {
        if (!is_array($listColumnsName)) {
            throw new InvalidArgumentException('List of Columns Properties not is a array');
        }
        $this->listColumnsName = array_map('strtoupper', $listColumnsName);
    }
    public function getListColunnsName()
    {
        return $this->listColumnsName;
    }
    //--------------------------------------------------------------------------------------
    public function setListColumnsProperties($listColumnsProperties)
    {
        TGeneratorHelper::validateListColumnsProperties($listColumnsProperties);
        $this->listColumnsProperties = $listColumnsProperties;
    }
    public function getListColumnsProperties()
    {
        return $this->listColumnsProperties;
    }
    //------------------------------------------------------------------------------------
    public function setTableType($tableType)
    {
        $this->tableType = $tableType;
    }
    public function getTableType()
    {
        return $this->tableType;
    }
    //--------------------------------------------------------------------------------------
    public  function addConstruct()
    {
        $this->addLine(ESP.'private $dao = null;');
        $this->addBlankLine();
        $this->addLine(ESP.'public function __construct($tpdo = null)');
        $this->addLine(ESP.'{');
        $this->addLine(ESP.ESP.'$this->dao = new '.$this->tableRefDAO.'($tpdo);');
        $this->addLine(ESP.'}');
        $this->addLine(ESP.'public function getDao()');
        $this->addLine(ESP.'{');
        $this->addLine(ESP.ESP.'return $this->dao;');
        $this->addLine(ESP.'}');
        $this->addLine(ESP.'public function setDao($dao)');
        $this->addLine(ESP.'{');
        $this->addLine(ESP.ESP.'$this->dao = $dao;');
        $this->addLine(ESP.'}');
    }
    //--------------------------------------------------------------------------------------
    private function addSelectById()
    {
        $this->addLine();
        $this->addLine(ESP.'public function selectById( $id )');
        $this->addLine(ESP.'{');
        $this->addLine(ESP.ESP.'$result = $this->dao->selectById( $id );');
        $this->addLine(ESP.ESP.'return $result;');
        $this->addLine(ESP.'}');
    }
    //--------------------------------------------------------------------------------------
    public function addGetVoById()
    {
        $this->addLine(ESP.'public function getVoById( $id )');
        $this->addLine(ESP.'{');
        $this->addLine(ESP.ESP.'$result = $this->dao->getVoById( $id );');
        $this->addLine(ESP.ESP.'return $result;');
        $this->addLine(ESP.'}');
    }
    //--------------------------------------------------------------------------------------
    private function addSelectCount()
    {
        $this->addLine();
        $this->addLine(ESP.'public function selectCount( $where=null )');
        $this->addLine(ESP.'{');
        $this->addLine(ESP.ESP.'$result = $this->dao->selectCount( $where );');
        $this->addLine(ESP.ESP.'return $result;');
        $this->addLine(ESP.'}');
    }
    //--------------------------------------------------------------------------------------
    private function addSelectAllPagination()
    {
        $this->addLine();
        $this->addLine(ESP.'public function selectAllPagination( $orderBy=null, $where=null, $page=null,  $rowsPerPage= null)');
        $this->addLine(ESP.'{');
        $this->addLine(ESP.ESP.'$result = $this->dao->selectAllPagination( $orderBy, $where, $page,  $rowsPerPage );');
        $this->addLine(ESP.ESP.'return $result;');
        $this->addLine(ESP.'}');
    }
    //--------------------------------------------------------------------------------------
    private function addSelectAll()
    {
        $this->addLine();
        $this->addLine(ESP.'public function selectAll( $orderBy=null, $where=null )');
        $this->addLine(ESP.'{');
        $this->addLine(ESP.ESP.'$result = $this->dao->selectAll( $orderBy, $where );');
        $this->addLine(ESP.ESP.'return $result;');
        $this->addLine(ESP.'}');
    }
    //--------------------------------------------------------------------------------------
    private function addSqlSelectByTCriteria()
    {
        $this->addLine();
        $this->addLine(ESP.'/**');
        $this->addLine(ESP.' * Faz um Select usando o TCriteria');
        $this->addLine(ESP.' * @param TCriteria $criteria    - 01: Obj TCriteria');
        $this->addLine(ESP.' * @param string $repositoryName - 02: nome de classe');
        $this->addLine(ESP.' * @return array Adianti');
        $this->addLine(ESP.' */');
        $this->addLine(ESP.'public function selectByTCriteria( TCriteria $criteria=null)');
        $this->addLine(ESP.'{');
        $this->addLine(ESP.ESP.'$result = $this->dao->selectByTCriteria($criteria);');
        $this->addLine(ESP.ESP.'return $result;');
        $this->addLine(ESP.'}');
    }
    //--------------------------------------------------------------------------------------
    private function addSqlSelectByTCriteriaCount()
    {
        $this->addLine();
        $this->addLine(ESP.'/**');
        $this->addLine(ESP.' * Faz um Select Count usando o TCriteria');
        $this->addLine(ESP.' * @param TCriteria $criteria    - 01: Obj TCriteria');
        $this->addLine(ESP.' * @param string $repositoryName - 02: nome de classe');
        $this->addLine(ESP.' * @return array Adianti');
        $this->addLine(ESP.' */');
        $this->addLine(ESP.'public function selectByTCriteriaCount( TCriteria $criteria=null)');
        $this->addLine(ESP.'{');
        $this->addLine(ESP.ESP.'$result = $this->dao->selectByTCriteriaCount($criteria);');
        $this->addLine(ESP.ESP.'return $result;');
        $this->addLine(ESP.'}');
    }    
    //--------------------------------------------------------------------------------------
    private function addSave()
    {
        $this->addLine();
        $columunPK = ucfirst(strtolower($this->listColumnsName[0]));
        $this->addLine(ESP.'public function save( '.$this->tableRefVO.' $objVo )');
        $this->addLine(ESP.'{');
        $this->addLine(ESP.ESP.'$result = null;');
        $this->addLine(ESP.ESP.'if( $objVo->get'.$columunPK.'() ) {');
        $this->addLine(ESP.ESP.ESP.'$result = $this->dao->update( $objVo );');
        $this->addLine(ESP.ESP.'} else {');
        $this->addLine(ESP.ESP.ESP.'$result = $this->dao->insert( $objVo );');
        $this->addLine(ESP.ESP.'}');
        $this->addLine(ESP.ESP.'return $result;');
        $this->addLine(ESP.'}');
    }
    //--------------------------------------------------------------------------------------
    private function addDelete()
    {
        $this->addLine();
        $this->addLine(ESP.'public function delete( $id )');
        $this->addLine(ESP.'{');
        $this->addLine(ESP.ESP.'$result = $this->dao->delete( $id );');
        $this->addLine(ESP.ESP.'return $result;');
        $this->addLine(ESP.'}');
    }
    //--------------------------------------------------------------------------------------
    public function addExecProcedure()
    {
        $this->addLine();
        $this->addLine(ESP.'public function execProcedure( '.$this->tableRefVO.' $objVo )');
        $this->addLine(ESP.'{');
        $this->addLine(ESP.ESP.'$result = $this->dao->execProcedure( $objVo );');        
        $this->addLine(ESP.ESP.'return $result;');
        $this->addLine(ESP.'}');
    }
    //--------------------------------------------------------------------------------------
    public function show($print = false)
    {
        $this->lines=null;
        $this->addLine('<?php');
        $this->addSysGenHeaderNote();
        $this->addLine('class '.$this->tableRef);
        $this->addLine('{');
        $this->addBlankLine();
        $this->addBlankLine();
        $this->addConstruct();
        
        if( $this->getTableType()== TableInfo::TB_TYPE_PROCEDURE){
            $this->addExecProcedure();
        }else{            
            $this->addSelectById();
            $this->addSelectCount();
            
            if ($this->getWithSqlPagination() == FormDinHelper::GRID_SQL_PAGINATION) {
                $this->addSelectAllPagination();
            }
            
            $this->addSelectAll();
            $this->addSqlSelectByTCriteria();
            $this->addSqlSelectByTCriteriaCount();            

            if( $this->getTableType()==TableInfo::TB_TYPE_TABLE){
                $this->addSave();
                $this->addDelete();
            }
            $this->addLine();
            $this->addGetVoById();
        }
        $this->addBlankLine();        
        $this->addLine('}');
        $this->addLine('?>');
        return $this->showContent($print);
    }
}
