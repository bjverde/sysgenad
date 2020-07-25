<?php
/*
 * ----------------------------------------------------------------------------
 * Formdin 5 Framework
 * SourceCode https://github.com/bjverde/formDin5
 * @author Reinaldo A. Barrêto Junior
 * 
 * É uma reconstrução do FormDin 4 Sobre o Adianti 7.X
 * ----------------------------------------------------------------------------
 * This file is part of Formdin Framework.
 *
 * Formdin Framework is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public License version 3
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License version 3
 * along with this program; if not,  see <http://www.gnu.org/licenses/>
 * or write to the Free Software Foundation, Inc., 51 Franklin Street,
 * Fifth Floor, Boston, MA  02110-1301, USA.
 * ----------------------------------------------------------------------------
 * Este arquivo é parte do Framework Formdin.
 *
 * O Framework Formdin é um software livre; você pode redistribuí-lo e/ou
 * modificá-lo dentro dos termos da GNU LGPL versão 3 como publicada pela Fundação
 * do Software Livre (FSF).
 *
 * Este programa é distribuído na esperança que possa ser útil, mas SEM NENHUMA
 * GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO ou
 * APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/LGPL em português
 * para maiores detalhes.
 *
 * Você deve ter recebido uma cópia da GNU LGPL versão 3, sob o título
 * "LICENCA.txt", junto com esse programa. Se não, acesse <http://www.gnu.org/licenses/>
 * ou escreva para a Fundação do Software Livre (FSF) Inc.,
 * 51 Franklin St, Fifth Floor, Boston, MA 02111-1301, USA.
 */

/**
 * Classe para criação de Grid para apresentar os dados
 * ------------------------------------------------------------------------
 * Esse é o FormDin 5, que é uma reconstrução do FormDin 4 Sobre o Adianti 7.X
 * os parâmetros do metodos foram marcados com:
 * 
 * NOT_IMPLEMENTED = Parâmetro não implementados, talvez funcione em 
 *                   verões futuras do FormDin. Não vai fazer nada
 * DEPRECATED = Parâmetro que não vai funcionar no Adianti e foi mantido
 *              para diminuir o impacto sobre as migrações. Vai gerar um Warning
 * FORMDIN5 = Parâmetro novo disponivel apenas na nova versão
 * ------------------------------------------------------------------------
 * 
 * @author Reinaldo A. Barrêto Junior
 */
class TFormDinGrid
{

    const TYPE_SIMPLE   = 'simple';
    const TYPE_CHECKOUT = 'checkout';

    private $adiantiObj;
    private $panelGroupGrid;
    private $objForm;
    private $listColumn = array();

    protected $action;
    protected $idGrid;
    protected $title;
    protected $key;

    protected $data;


    /**
     * Classe para criação de grides, Padronizado em BoorStrap
     * Reconstruido FormDin 4 Sobre o Adianti 7
     * 
     * Parametros do evento onDrawHeaderCell
     * 	1) $th			- objeto TElement
     * 	2) $objColumn 	- objeto TGridColum
     * 	3) $objHeader 	- objeto TElement
     *
     * Parametros do envento onDrawRow
     * 	1) $row 		- objeto TGridRow
     * 	2) $rowNum 		- número da linha corrente
     * 	3) $aData		- o array de dados da linha ex: $res[''][n]
     *
     * Parametros do envento onDrawCell
     * 	1) $rowNum 		- número da linha corrente
     * 	2) $cell		- objeto TTableCell
     * 	3) $objColumn	- objeto TGrideColum
     * 	4) $aData		- o array de dados da linha ex: $res[''][n]
     * 	5) $edit		- o objeto campo quando a coluna for um campo de edição
     *   ex: function ondrawCell($rowNum=null,$cell=null,$objColumn=null,$aData=null,$edit=null)
     *
     * Parametros do evento onDrawActionButton
     * 	1) $rowNum 		- número da linha corrente
     * 	2) $button 		- objeto TButton
     * 	3) $objColumn	- objeto TGrideColum
     * 	4) $aData		- o array de dados da linha ex: $res[''][n]
     *   Ex: function tratarBotoes($rowNum,$button,$objColumn,$aData);
     *
     * Parametros do evento onGetAutocompleteParameters
     * 	1) $ac 			- classe TAutocomplete
     * 	2) $aData		- o array de dados da linha ex: $res[''][n]
     * 	3) $rowNum 		- número da linha corrente
     * 	3) $cell		- objeto TTableCell
     * 	4) $objColumn	- objeto TGrideColum
     *
     * @param object $objForm             - 1: FORMDIN5 Objeto do Adianti da classe do Form, é só informar $this
     * @param string $strName             - 2: ID da grid
     * @param string $strTitle            - 3: Titulo da grip
     * @param array $mixData              - 4: Array de dados. Pode ser form formato Adianti, FormDin ou PDO
     * @param mixed $strHeight            - 5: Altura 
     * @param mixed $strWidth             - 6: NOT_IMPLEMENTED Largura
     * @param mixed $strKeyField          - 7: NOT_IMPLEMENTED Chave primaria
     * @param array $mixUpdateFields      - 8: NOT_IMPLEMENTED Campos do form origem que serão atualizados ao selecionar o item desejado. Separados por virgulas seguindo o padrão <campo_tabela> | <campo_formulario> , <campo_tabela> | <campo_formulario>
     * @param mixed $intMaxRows           - 9: NOT_IMPLEMENTED Qtd Max de linhas
     * @param mixed $strRequestUrl        -10: NOT_IMPLEMENTED Url request do form
     * @param mixed $strOnDrawCell        -11: NOT_IMPLEMENTED
     * @param mixed $strOnDrawRow         -13: NOT_IMPLEMENTED
     * @param mixed $strOnDrawHeaderCell  -14: NOT_IMPLEMENTED
     * @param mixed $strOnDrawActionButton-15: NOT_IMPLEMENTED
     * @return TGrid
     */     
    public function __construct( $objForm
                               , string $strName
                               , string $strTitle = null
                               , $mixData = null
                               , $strHeight = null
                               , $strWidth = null
                               , string $strKeyField = null
                               , $mixUpdateFields = null
                               , $intMaxRows = null
                               , $strRequestUrl = null
                               , $strOnDrawCell = null
                               , $strOnDrawRow = null
                               , $strOnDrawHeaderCell = null
                               , $strOnDrawActionButton = null )
    {
        if( !is_object($objForm) ){
            $track = debug_backtrace();
            $msg = 'A classe GRID MUDOU! o primeiro parametro agora recebe $this! o Restante está igual ;-)';
            ValidateHelper::migrarMensage($msg
                                         ,ValidateHelper::ERROR
                                         ,ValidateHelper::MSG_CHANGE
                                         ,$track[0]['class']
                                         ,$track[0]['function']
                                         ,$track[0]['line']
                                         ,$track[0]['file']
                                        );
        }else{
            $this->setObjForm($objForm);

            $bootgrid = new BootstrapDatagridWrapper(new TDataGrid);
            $bootgrid->width = '100%';
            $this->setAdiantiObj($bootgrid);
            $this->setId($strName);
            $this->setHeight($strHeight);
            //$this->setWidth($strWidth);
            $this->setData($mixData);

            $panel = new TPanelGroup($strTitle);
            $this->setPanelGroupGrid($panel);
        }
    }


    public function setObjForm($objForm)
    {
        if( empty($objForm) ){
            throw new InvalidArgumentException(TFormDinMessage::ERROR_FD5_OBJ_ADI);
        }
        if( !is_object($objForm) ){
            throw new InvalidArgumentException(TFormDinMessage::ERROR_FD5_OBJ_ADI);
        }        
        return $this->objForm=$objForm;
    }
    public function getObjForm(){
        return $this->objForm;
    }

    public function setAdiantiObj( $bootgrid )
    {
        if( !($bootgrid instanceof BootstrapDatagridWrapper) ){
            throw new InvalidArgumentException(TFormDinMessage::ERROR_FD5_OBJ_BOOTGRID);
        }
        $this->adiantiObj = $bootgrid;
    }

    public function getAdiantiObj(){
        //$title = $this->getTitle();
        //$panel = new TPanelGroup($title);
        //$panel->add( $this->adiantiObj );
        return $this->adiantiObj;
    }

    public function getId(){
        return $this->idGrid;
    }

    public function setId(string $idGrid){
        if(empty($idGrid)){
            throw new InvalidArgumentException(TFormDinMessage::ERROR_EMPTY_INPUT);
        }
        $this->getAdiantiObj()->setId($idGrid);
        $this->idGrid = $idGrid;
    }

    public function getHeight(){
        return $this->getAdiantiObj()->height;
    }

    public function setHeight($height){
        if( !empty($height) ){
            $this->getAdiantiObj()->setHeight($height);
            $this->getAdiantiObj()->makeScrollable();
        }
    }


    public function getWidth()
    {
        //return $this->getAdiantiObj()->getWidth();
        return null;
    }   
    public function setWidth( $width )
    {
        //$this->getAdiantiObj()->setWidth($width);
    }

    public function setData( $data )
    {
        $this->data = $data;
    }
    public function getData()
    {
        return $this->data;
    }

    /**
     * Adciona um Objeto Adianti na lista de objetos que compeen o Formulário.
     * 
     * @param string $type    - 1: Type column constante
     * @param string $idcolumn- 2: idcolumn
     * @param string $label   - 3: Label da coluna
     * @param string $width   - 4: 
     * @param string $align   - 5: 
     * @return void
     */
    public function addElementColumnList($type = self::TYPE_SIMPLE
                                        , string $idcolumn
                                        , string $label
                                        , string $width = NULL
                                        , string $align = 'left'                                        
                                        )
    {
        $element = array();
        $element['type']=$type;
        $element['idcolumn']=$idcolumn;
        $element['label']=$label;
        $element['align']=$align;
        $element['width']=$width;
        $this->listColumn[]=$element;
    }

    public function show()
    {
        $this->getAdiantiObj()->createModel();
        if( !empty($this->getData()) ){
            $this->getAdiantiObj()->addItems( $this->getData() );
        }
        $this->getPanelGroupGrid()->add($this->getAdiantiObj())->style = 'overflow-x:auto';
        return $this->getAdiantiObj();
    }

    public function getAction(){
        return $this->action;
    }

    public function setAction($action){
        $this->action = $action;
    }



    public function getTitle(){
        return $this->title;
    }

    public function setTitle(string $title){
        $this->getPanelGroupGrid()->setTitle($title);
        $this->title = $title;
    }

    public function getKey(){
        return $this->key;
    }

    public function setKey(string $key){
        $this->key = $key;
    }

    public function getPanelGroupGrid(){
        return $this->panelGroupGrid;
    }

    public function setPanelGroupGrid($panel){
        if( !($panel instanceof TPanelGroup) ){
            throw new InvalidArgumentException(TFormDinMessage::ERROR_OBJ_TYPE_WRONG.' use TPanelGroup');
        }
        $this->panelGroupGrid = $panel;
    }

    public function getFooter(){
        return $this->getPanelGroupGrid()->getFooter();
    }

    public function addFooter($footer){
        return $this->getPanelGroupGrid()->addFooter($footer);
    }

    public function enableDataTable(){
        $this->getAdiantiObj()->datatable = 'true';
    }

    public function disableDataTable(){
        $this->getAdiantiObj()->datatable = 'false';
    }

    /**
     * Coluna do Grid Padronizado em BoorStrap
     * Reconstruido FormDin 4 Sobre o Adianti 7.1
     *
     * @param  string $name  - 1: Name of the column in the database
     * @param  string $label - 2: Text label that will be shown in the header
     * @param  string $width - 3: Column Width (pixels)
     * @param  string $align - 4: Column align (left|right|center|justify)
     * @return TDataGridColumn
     */
    public function addColumn(string $name
                            , string $label
                            , string $width = NULL
                            , string $align='left' )
    {
        $formDinGridColumn = new TFormDinGridColumn( $name,$label,$width,$align);
        $column = $formDinGridColumn->getAdiantiObj();
        $this->getAdiantiObj()->addColumn($column);
        return $column;
    }

    //---------------------------------------------------------------------------------------
    /**
     * coluna tipo checkbox. Irá criar no gride uma coluno do tipo checkbox. Quando é feito o POST
     * será criado uma nova variavel com valor de strName
     *
     *
     * @param string $strName       - Nome do variavel no POST
     * @param string $strTitle      - Titulo que aparece no grid
     * @param string $strKeyField   - Valor que será passado no POST
     * @param string $strDescField  - Descrição do campo, valor que irá aparecer o gride
     * @param boolean $boolReadOnly
     * @param boolean $boolAllowCheckAll  - TRUE = pode selecionar todos , FALSE = não permite multiplas seleções
     * @return TGridCheckColumn
     */
    public function addCheckColumn( $strName
                                , $strTitle = null
                                , $strKeyField
                                , $strDescField = null
                                , $boolReadOnly = null
                                , $boolAllowCheckAll = null )
    {
        if ( !$strKeyField ){
            $strKeyField = strtoupper( $strName );
        }
        $this->getAdiantiObj()->disableDefaultClick(); //IMPORTANTE DESATIVAR
        $col = new TGridCheckColumn( $strName, $strTitle, $strKeyField, $strDescField, $boolReadOnly, $boolAllowCheckAll );
        $this->columns[ strtolower( $strName )] = $col;
        return $col;
    }
}