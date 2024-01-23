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

class TCreateMenu extends TCreateFileContent
{
    private $listTableNames;

    public function __construct()
    {
        $this->setFileName('menu.xml');
        $path = TGeneratorHelper::getPathNewSystem().DS;
        $this->setFilePath($path);
    }
    //--------------------------------------------------------------------------------------
    public function setListTableNames($listTableNames)
    {
        TGeneratorHelper::validateListTableNames($listTableNames);
        $this->listTableNames = $listTableNames;
    }
    public function getListTableNames()
    {
        return $this->listTableNames;
    }
    //--------------------------------------------------------------------------------------
    /**
     * Incluie o item de menu, conforme o tableType
     *
     * @param string $ESP - quantidade inicial de espaço
     * @param string $tableTypeObj - tipo de tabela que vem do TableInfo::TB_TYPE_TABLE ou TableInfo::TB_TYPE_VIEW ou TableInfo::TB_TYPE_PROCEDURE
     * @return void
     */
    public function addBasicMenuItems($ESP, $tableTypeObj)
    {
        $listTableNames = $this->listTableNames['TABLE_NAME'];
        foreach ($listTableNames as $key => $table) {
            $tableType = strtoupper($this->listTableNames['TABLE_TYPE'][$key]);
            if ($tableTypeObj == $tableType) {
                $this->addLine($ESP.ESP.'<menuitem label=\''.$table.'\'>');
                $this->addLine($ESP.ESP.ESP.'<icon>fa:book fa-fw</icon>');
                if($tableTypeObj==TableInfo::TB_TYPE_PROCEDURE){
                    $this->addLine($ESP.ESP.ESP.'<action>'.strtolower($table).'Form</action>');
                }else{
                    $this->addLine($ESP.ESP.ESP.'<action>'.strtolower($table).'FormList</action>');
                }
                $this->addLine($ESP.ESP.'</menuitem>');
            }
        }
    }
    //--------------------------------------------------------------------------------------
    public function typeTableExist($tableType){
        $listTableType = $this->listTableNames['TABLE_TYPE'];
        $result = array_search($tableType, $listTableType);
        if($result === false){
            $result = false;
        }else if($result === ''){
            $result = false;
        }else{
            $result = true;
        }
        return $result;
    }
    //--------------------------------------------------------------------------------------
    public function addBasicMenuCruds()
    {
        $tableType = TableInfo::TB_TYPE_TABLE;
        $typeTableExist = $this->typeTableExist($tableType);
        if($typeTableExist){
            $this->addLine(ESP."<menuitem label='Cruds'>");
            $this->addLine(ESP.ESP."<icon>fa:magic fa-fw #f0db4f</icon>");
            $this->addLine(ESP.ESP."<menu>");
            $this->addBasicMenuItems( ESP.ESP, $tableType );
            $this->addLine(ESP.ESP."</menu>");
            $this->addLine(ESP."</menuitem>");
        }
    }
    //--------------------------------------------------------------------------------------
    public function addBasicMenuViews()
    {
        $tableType = TableInfo::TB_TYPE_VIEW;
        $typeTableExist = $this->typeTableExist($tableType);
        if($typeTableExist){
            $this->addLine(ESP."<menuitem label='Views'>");
            $this->addLine(ESP.ESP."<icon>fa:magic fa-fw #f0db4f</icon>");
            $this->addLine(ESP.ESP."<menu>");
            $this->addBasicMenuItems( ESP.ESP, $tableType );
            $this->addLine(ESP.ESP."</menu>");
            $this->addLine(ESP."</menuitem>");
        }
    }
    //--------------------------------------------------------------------------------------
    public function addBasicMenuProcedure()
    {
        $tableType = TableInfo::TB_TYPE_PROCEDURE;
        $typeTableExist = $this->typeTableExist($tableType);
        if($typeTableExist){
            $this->addLine(ESP."<menuitem label='Procedure'>");
            $this->addLine(ESP.ESP."<icon>fa:magic fa-fw #f0db4f</icon>");
            $this->addLine(ESP.ESP."<menu>");
            $this->addBasicMenuItems( ESP.ESP, $tableType );
            $this->addLine(ESP.ESP."</menu>");
            $this->addLine(ESP."</menuitem>");
        }
    }
    //--------------------------------------------------------------------------------------
    public function show($print = false)
    {
        $this->setLinesArrayBranco();
        $this->addLine('<menu>');
        $this->addBasicMenuCruds();
        $this->addBlankLine();
        $this->addBasicMenuViews();
        $this->addBlankLine();
        $this->addBasicMenuProcedure();
        $this->addLine('</menu>');
        if ($print) {
            echo $this->getLinesString();
        } else {
            return $this->getLinesString();
        }
    }
}
