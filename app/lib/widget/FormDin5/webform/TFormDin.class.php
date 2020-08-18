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
 * Classe para criação de formulários web para entrada de dados
 * ------------------------------------------------------------------------
 * Esse é o FormDin 5, que é uma reconstrução do FormDin 4 Sobre o Adianti 7.X
 * os parâmetros do metodos foram marcados com:
 * 
 * NOT_IMPLEMENTED = Parâmetro não implementados, talvez funcione em 
 *                   verões futuras do FormDin. Não vai fazer nada
 * DEPRECATED = Parâmetro que não vai funcionar no Adianti e foi mantido
 *              para o impacto sobre as migrações. Vai gerar um Warning
 * FORMDIN5 = Parâmetro novo disponivel apenas na nova versão
 * ------------------------------------------------------------------------
 * 
 * @author Reinaldo A. Barrêto Junior
 */
class TFormDin
{
    const TYPE_FIELD  = 'feild';
    const TYPE_LAYOUT = 'layout';
    const TYPE_HIDDEN = 'hidden';
    const TYPE_ADIANTI_FIELD_NATIVE  = 'adianti_field_native';
    const TYPE_ADIANTI_LAYOUT_NATIVE = 'adianti_layout_native';
    
    private $objForm;
    protected $adiantiObj;
    private $listFormElements = array();

    /**
     * Método construtor da classe do Formulario Padronizado em BoorStrap
     * ------------------------------------------------------------------------
     * Esse é o FormDin 5, que é uma reconstrução do FormDin 4 Sobre o Adianti 7.X
     * os parâmetros do metodos foram marcados veja documentação da classe para
     * saber o que cada marca singinifica.
     * ------------------------------------------------------------------------
     * <code>
     * 	$frm = new TFormDin('Título do Formuláio');
     * 	$frm->show();
     * </code>
     *
     * @param object $objForm   - 01: FORMDIN5 Objeto do Adianti da classe do Form, é só informar $this
     * @param string $strName   - 02: Titulo que irá aparecer no Form
     * @param string $strHeight - 03: DEPRECATED: INFORME NULL para remover o Warning
     * @param string $strWidth  - 04: DEPRECATED: INFORME NULL para remover o Warning
     * @param bool $strFormName - 05: ID nome do formulario para criação da tag form. Padrão=formdin
     * @param string $strMethod - 06: NOT_IMPLEMENTED: metodo GET ou POST, utilizado pelo formulario para submeter as informações. padrão=POST
     * @param string $strAction - 07: NOT_IMPLEMENTED: página/url para onde os dados serão enviados. Padrão = propria página
     * @param boolean $boolPublicMode - 08: NOT_IMPLEMENTED: ignorar mensagem fwSession_exprired da aplicação e não chamar atela de login
     * @param boolean $boolRequired   - 09: FORMDIN5: Se vai fazer validação no Cliente (Navegador)
     *
     * @return BootstrapFormBuilder
     */    
    public function __construct($objForm
                               ,string $strTitle
                               ,$strHeigh = null
                               ,$strWidth = null
                               ,string $strName = 'formdin'
                               ,$strMethod = null
                               ,$strAction  = null
                               ,$boolPublicMode  = null
                               ,$boolClientValidation = true)
    {

        if( !is_object($objForm) ){
            $track = debug_backtrace();
            $msg = TFormDinMessage::ERROR_FD5_FORM_MIGRAT;
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

            $this->validateDeprecated($strHeigh,$strWidth);
            $bootForm = new BootstrapFormBuilder($strName);
            $this->setAdiantiObj( $bootForm, $strName,$strTitle, $boolClientValidation);
            return $this->getAdiantiObj();
        }
    }

    public function validateDeprecated($strHeigh,$strWidth)
    {
        ValidateHelper::validadeParam('strHeigh',$strHeigh
                                     ,ValidateHelper::WARNING
                                     ,ValidateHelper::MSG_DECREP
                                     ,__CLASS__,__METHOD__,__LINE__);

        ValidateHelper::validadeParam('strWidth',$strWidth
                                     ,ValidateHelper::WARNING
                                     ,ValidateHelper::MSG_DECREP
                                     ,__CLASS__,__METHOD__,__LINE__);                                     
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


    /**
     * Recebe a chave da posição do elemento e verifica se o proximo elemento
     * deve ficar em mesma linha ou na proxima
     * @param int $key
     * @return void
     */
    public function nextElementNewLine($key)
    {
        $result = null;
        $listFormElements = $this->getListFormElements();
        if( ArrayHelper::has($key+1,$listFormElements) ){
            $result = $listFormElements[$key+1]['boolNewLine'];
        }
        return $result;
    }

    /**
     * Recebe um elemento e retorna o array com Label do campo e Obj do campo
     *
     * @param object $element
     * @return array 
     */
    public function getArrayElementLabelAbove($element)
    {
        ValidateHelper::isArray($element,__METHOD__,__LINE__);
        $result = null;
        $label = $element['label'];
        $obj   = $element['obj'];
        if($element['boolLabelAbove']==true){
            if( empty($label) ){
                $result = array([$obj]);
            }else{
                $result = array([$label, $obj]);
            }
        }else{
            if( empty($label) ){
                $result = array([$obj]);
            }else{
                $result = array([$label], [$obj]);
            }
        }
        return $result;
    }

    public function addtElementInRow($listFormElements,$key,$row){
        $element = $listFormElements[$key];
        $label = $element['label'];
        $obj   = $element['obj'];
        if($element['boolLabelAbove']==true){
            if( empty($label) ){
                $row[]=[$obj];
            }else{
                $row[]=[$label, $obj];
            }            
        }else{
            if( empty($label) ){
                $row[]=[$obj];
            }else{
                $row[]=[$label];
                $row[]=[$obj];
            }
        }
        return $row;
    }

    /**
     * Recebe a chave da posição da posição inicial da lista de objetos do form
     * Percorrendo a lista para determinar todos objeto de uma linha do form.
     * Retorna o array de duas posição
     * $result['key'] - ultimo elemento incluido
     * $result['row'] - array com todos os alementos da lista
     * 
     * @param int $key - 1: Posição inicial da lista de objetos
     * @return array
     */
    public function addFieldsRow($key)
    {
        $result = array();
        $listFormElements = $this->getListFormElements();
        if( $this->nextElementNewLine($key)===true ){
            $result['key']=$key;
            $element = $listFormElements[$key];
            $result['row']=$this->getArrayElementLabelAbove($element);
        }else if( $this->nextElementNewLine($key)===false ){
            $row = array();
            while( $this->nextElementNewLine($key)==false && ArrayHelper::has($key,$listFormElements)) {
                $row = $this->addtElementInRow($listFormElements,$key,$row);
                /*
                $element = $listFormElements[$key];
                $label = $element['label'];
                $obj   = $element['obj'];
                if($element['boolLabelAbove']==true){
                    $row[]=[$label, $obj];
                }else{
                    $row[]=[$label];
                    $row[]=[$obj];
                }
                */
                $key = $key + 1;
            }
            if( $this->nextElementNewLine($key)==true && ArrayHelper::has($key,$listFormElements)){
                $row = $this->addtElementInRow($listFormElements,$key,$row);
                //$key = $key + 1;
            }
            $result['key']=$key;
            $result['row']=$row;
        }else{
            $result['key']=$key;
            if(!ArrayHelper::has($key,$listFormElements)){
                $result['row']=null;
            }else{
                $element = $listFormElements[$key];
                $result['row']=$this->getArrayElementLabelAbove($element);
            }            
        }
        return $result;
    }

    public function setAdiantiObj( $bootForm=null, $strName=null,$strTitle=null, $boolClientValidation=true )
    {
        if( empty($bootForm) ){
            $bootForm = new BootstrapFormBuilder($strName);
            $bootForm->setFormTitle($strTitle);
            //$this->adiantiObj->setFieldSizes('100%');
            $bootForm->setClientValidation($boolClientValidation);
            $bootForm->generateAria(); // automatic aria-label
        }else{
            if( !($bootForm instanceof BootstrapFormBuilder) ){
                throw new InvalidArgumentException(TFormDinMessage::ERROR_FD5_OBJ_BUILDER);
            }
            if( !empty($strName) ){
                $bootForm->setName($strName);
            }
            $bootForm->setFormTitle($strTitle);
            //$this->adiantiObj->setFieldSizes('100%');
            $bootForm->setClientValidation($boolClientValidation);
            $bootForm->generateAria(); // automatic aria-label
        }
        $this->adiantiObj = $bootForm;
    }

    public function getAdiantiObj2()
    {
        $adiantiObj = $this->adiantiObj;
        $listFormElements = $this->getListFormElements();
        $qtd = CountHelper::count($listFormElements);
        $key = 0;
        while ($key < $qtd) {
            $element = $listFormElements[$key];
            if($element['type']==self::TYPE_FIELD){
                $fieldsRowResult = $this->addFieldsRow($key);
                $fieldsRow = $fieldsRowResult['row'];
                call_user_func_array(array($adiantiObj, "addFields"), $fieldsRow);
                $key = $fieldsRowResult['key'];
            }elseif ($element['type']==self::TYPE_HIDDEN){
                $adiantiObj->addFields( [$element['obj']] );
            }elseif ($element['type']==self::TYPE_ADIANTI_FIELD_NATIVE){
                //$adiantiObj->addFields( $element['obj'] );
                call_user_func_array(array($adiantiObj, "addFields"), $element['obj']);
            }elseif ($element['type']==self::TYPE_ADIANTI_LAYOUT_NATIVE){
                //$adiantiObj->addContent( $element['obj'] );
                call_user_func_array(array($adiantiObj, "addContent"), $element['obj']);
            }elseif ($element['type']==self::TYPE_LAYOUT){
                $adiantiObj->addContent( [$element['obj']] );
                //call_user_func_array(array($adiantiObj, "addFields"), $fieldsRow);
                //https://www.php.net/manual/pt_BR/function.call-user-func-array.php
            }
            $key = $key + 1;
        }
        return $this->adiantiObj;
    }


    public function getAdiantiObj()
    {
        return $this->adiantiObj;
    }

    public function show()
    {
        return $this->getAdiantiObj2();
    }

    /**
     * Adciona um Objeto Adianti na lista de objetos que compeen o Formulário.
     * 
     * @param object $obj  -  1: objeto Adianti
     * @param string $type -  2: Typo confirmo constante
     * @param object $label - 3: objeto do tipo Label do $obj
     * @param boolean $boolNewLine    - 4: DEFAULT = True = campo em nova linha. FALSE = mesma linha
     * @param boolean $boolLabelAbove - 5: DEFAULT = FALSE = Label na frente do campo. TRUE = Label sobre o campo
     * @return void
     */
    public function addElementFormList($obj
                                         ,$type = self::TYPE_FIELD
                                         ,$label=null
                                         ,$boolNewLine=true
                                         ,$boolLabelAbove=false)
    {
        $element = array();
        $element['obj']=$obj;
        $element['type']=is_null($type)?self::TYPE_FIELD:$type;
        $element['label']=$label;
        $element['boolNewLine']=is_null($boolNewLine)?true:$boolNewLine;
        $element['boolLabelAbove']=is_null($boolLabelAbove)?false:$boolLabelAbove;
        $this->listFormElements[]=$element;
    }

    public function getListFormElements()
    {
        return $this->listFormElements;
    }

    /**
     * Inclusão de campos no Form, usando elementos nativos do Adianti
     */
    public function addFields()
    {
        $args = func_get_args();
        $this->addElementFormList($args,self::TYPE_ADIANTI_FIELD_NATIVE);
    }

    /**
     * Inclusão de um conteudo no Form, usando um elementos nativos do Adianti
     */
    public function addContent()
    {
        $args = func_get_args();
        $this->addElementFormList($args,self::TYPE_ADIANTI_LAYOUT_NATIVE);
    }

    protected function getLabelField($strLabel,$boolRequired=false)
    {
        $formDinLabelField = new TFormDinLabelField($strLabel,$boolRequired);
        $label = $formDinLabelField->getAdiantiObj();
        return $label;
    }


   /**
    * Define as mensagens que serão exibidas na tela via alert() em javascript
    *
    * ------------------------------------------------------------------------
    * Esse é o FormDin 5, que é uma reconstrução do FormDin 4 Sobre o Adianti 7.X
    * os parâmetros do metodos foram marcados veja documentação da classe para
    * saber o que cada marca singinifica.
    * ------------------------------------------------------------------------
    *
    * <code>
    * 	$frm->setMessage('Nova mensagem'); // limpa e define uma nova mensagem
    * 	$frm->setMessage(array('Mensagem linha 1','mensagem linha 2');
    * </code>
    *
    * @param string $message   - 1: Texto da mensagem ser HTML
    * @param string $type      - 2: FORMDIN5 Type mensagem: DEFAULT=info, error, warning. Use TFormDinMessage::TYPE_
    * @param TAction $action   - 3: FORMDIN5 Classe TAction do Adianti
    * @param string $title_msg - 4: FORMDIN5 titulo da mensagem
    */
    public function setMessage( $message
                              , $type = TFormDinMessage::TYPE_INFO
                              , TAction $action = NULL
                              , $title_msg = '' )
    {
        $formDinLabelField = new TFormDinMessage($message,$type,$action,$title_msg);
        return $formDinLabelField;
    }

   /**
    * ALIAS para setMessage
    *
    * <code>
    * 	$frm->setMessage('Nova mensagem'); // limpa e define uma nova mensagem
    * 	$frm->setMessage(array('Mensagem linha 1','mensagem linha 2');
    * </code>
    *
    * @param string $message   - 1: Texto da mensagem ser HTML
    * @param string $type      - 2: FORMDIN5 Type mensagem: DEFAULT=info, error, warning. Use TFormDinMessage::TYPE_
    * @param TAction $action   - 3: FORMDIN5 Classe TAction do Adianti
    * @param string $title_msg - 4: FORMDIN5 titulo da mensagem
    */
    public function addMessage( $message
                              , $type = TFormDinMessage::TYPE_INFO
                              , TAction $action = NULL
                              , $title_msg = '' )
    {
        $formDinLabelField = $this->setMessage($message,$type,$action,$title_msg);
        return $formDinLabelField;
    }

    /**
    * Adicionar botão no layout
    *
    * ------------------------------------------------------------------------
    * Esse é o FormDin 5, que é uma reconstrução do FormDin 4 Sobre o Adianti 7.X
    * os parâmetros do metodos foram marcados veja documentação da classe para
    * saber o que cada marca singinifica.
    * ------------------------------------------------------------------------
    *
    * Para que o botão fique alinhado na frente de um campo com labelAbove=true, basta
    * definir o parametro boolLabelAbove do botão para true tambem.
    *
    * @param string   $mixValue         - 1 : Label do Botão ou array('Gravar', 'Limpar') com nomes
    * @param string  $strAction         - 2 : NOT_IMPLEMENTED Nome da ação, ignorando $strName $strOnClick. Se ficar null será utilizado o valor de mixValue
    * @param mixed  $strName            - 3 : Nome do metodo da ação (string) no mesmo Form ou  Array [FormDestino,actionsName]
    * @param string  $strOnClick        - 4 : NOT_IMPLEMENTED Nome da função javascript
    * @param string  $strConfirmMessage - 5 : NOT_IMPLEMENTED Mensagem de confirmação, para utilizar o confirme sem utilizar javaScript explicito.
    * @param boolean $boolNewLine       - 6 : Em nova linha. DEFAULT = true
    * @param boolean $boolFooter        - 7 : Mostrar o botão no rodapé do form. DEFAULT = true
    * @param string  $strImage          - 8 : Imagem no botão. Evite usar no lugar procure usar a propriedade setClass. Busca pasta imagens do base ou no caminho informado
    * @param string  $strImageDisabled  - 9 : NOT_IMPLEMENTED Imagem no desativado. Evite usar no lugar procure usar a propriedade setClass. Busca pasta imagens do base ou no caminho informado
    * @param string  $strHint           -10 : NOT_IMPLEMENTED Texto hint para explicar
    * @param string  $strVerticalAlign  -11 : NOT_IMPLEMENTED
    * @param boolean $boolLabelAbove    -12 : NOT_IMPLEMENTED Position text label. DEFAULT is false. NULL = false. 
    * @param string  $strLabel          -13 : NOT_IMPLEMENTED Text label 
    * @param string  $strHorizontalAlign-14 : NOT_IMPLEMENTED Text Horizontal align. DEFAULT = center. Valeus center, left, right
    * @return TButton|string|array
    */
    public function addButton( $mixValue
				       		, $strAction=null
				       		, $strName=null
				       		, $strOnClick=null
				       		, $strConfirmMessage=null
				       		, $boolNewLine=null
				       		, $boolFooter=true
				       		, $strImage=null
				       		, $strImageDisabled=null
				       		, $strHint=null
				       		, $strVerticalAlign=null
				       		, $boolLabelAbove=null
				       		, $strLabel=null
                            , $strHorizontalAlign=null)
    {
        $objForm =  $this->getObjForm();
        if($boolFooter){
            return $this->setAction($mixValue,$strName,false,$strImage);
        }else{
            $formField = new TFormDinButton($objForm
                                            , $mixValue
                                            , $strAction
                                            , $strName
                                            , $strOnClick
                                            , $strConfirmMessage
                                            , $boolNewLine
                                            , $boolFooter
                                            , $strImage
                                            , $strImageDisabled
                                            , $strHint
                                            , $strVerticalAlign
                                            , $boolLabelAbove
                                            , $strLabel
                                            , $strHorizontalAlign
                                        );
            $objField = $formField->getAdiantiObj();
            $this->addElementFormList($objField,self::TYPE_FIELD,null,$boolNewLine);
            //$this->addElementFormList($objField,self::TYPE_LAYOUT,null,$boolNewLine);
            return $formField;
        } //FIM else $boolFooter
    }

   /**
    * Define os botões de ação no formulario. Pode ser passado uma acao ou um array de ações.
    * Cada ação será um botão no rodapé ou título do formulário
    *
    * ------------------------------------------------------------------------
    * Esse é o FormDin 5, que é uma reconstrução do FormDin 4 Sobre o Adianti 7.X
    * os parâmetros do metodos foram marcados veja documentação da classe para
    * saber o que cada marca singinifica.
    * ------------------------------------------------------------------------
    *
    * @param string $actionsLabel-1: Label do Botão
    * @param mixed  $actionsName- 2: FORMDIN5 Nome do metodo da ação (string) no mesmo Form ou  Array [FormDestino,actionsName]
    * @param boolean $header    - 3: FORMDIN5 mostrar ação no Título (Header). DEFAULT=false, mostra no rodapé. TRUE = mostra no Título
    * @param string $iconImagem - 4: FORMDIN5 icone ou imagem do botão.
    * @param string $color      - 5: FORMDIN5 cor do icone.
    * @param string $methodPost - 6: FORMDIN5 Metodo da ação pode ser POST ou GET. DEFAULT=true, POST. FALSE, GET
    * @return TButton
    */
    public function setAction( $actionsLabel
                             , $actionsName=null
                             , $header=false
                             , $iconImagem=null
                             , $color=null 
                             , $methodPost=true)
    {
        if( is_array($actionsLabel) ){
            $track = debug_backtrace();
            $msg = 'Não é permitido usar ARRAY no setAction, migre para chamada unica por Action';
            ValidateHelper::migrarMensage($msg
                                         ,ValidateHelper::ERROR
                                         ,ValidateHelper::MSG_CHANGE
                                         ,$track[0]['class']
                                         ,$track[0]['function']
                                         ,$track[0]['line']
                                         ,$track[0]['file']
                                        );
        }else{
            $objForm = $this->getObjForm();
            ValidateHelper::isSet($actionsName,__METHOD__,__LINE__);
            ValidateHelper::isSet($objForm,__METHOD__,__LINE__);

            if( is_array($actionsName) ){
                $action = new TAction(array($actionsName[0], $actionsName[1]));
            }else{
                $action = new TAction(array($objForm, $actionsName));
            }
            
            $icon = $iconImagem.' '.$color;
            if($header===true){
                if($methodPost){
                    return $this->getAdiantiObj()->addHeaderAction($actionsLabel,$action,$icon);
                }else{
                    return $this->getAdiantiObj()->addHeaderActionLink($actionsLabel,$action,$icon);
                }
            }else{
                if($methodPost){
                    return $this->getAdiantiObj()->addAction($actionsLabel,$action,$icon);
                }else{
                    return $this->getAdiantiObj()->addActionLink($actionsLabel,$action,$icon);
                }
            }
        }
    }

   /**
    * Define os botões de ação no Título do formulario.
    * Cada ação será um botão no rodapé ou título do formulário
    *
    * ------------------------------------------------------------------------
    * Esse é o FormDin 5, que é uma reconstrução do FormDin 4 Sobre o Adianti 7.X
    * os parâmetros do metodos foram marcados veja documentação da classe para
    * saber o que cada marca singinifica.
    * ------------------------------------------------------------------------
    *
    * @param mixed $actionsLabel- 1: Texto ações.
    * @param mixed  $actionsName- 2: FORMDIN5 Nome do metodo da ação (string) no mesmo Form ou  Array [FormDestino,actionsName]
    * @param boolean $header    - 3: FORMDIN5 mostrar ação Título. DEFAULT=TRUE, mostra no Título. false, mostra no rodapé. 
    * @param string $iconImagem - 4: FORMDIN5 icone ou imagem do botão.
    * @param string $color      - 5: FORMDIN5 cor do icone.
    * @param string $methodPost - 6: FORMDIN5 Metodo da ação fas um Post. DEFAULT=true, POST. FALSE, GET
    * @return TButton
    */
    public function setActionHeader( $actionsLabel
                                   , $actionsName=null                                   
                                   , $header=true, $iconImagem=null, $color=null
                                   , $methodPost=true)
    {
        return $this->setAction($actionsLabel, $actionsName, $header, $iconImagem, $color,$methodPost);
    }

   /**
    * Define os botões de ação no formulario, do TIPO GET.
    * Cada ação será um botão no rodapé ou título do formulário
    *
    * ------------------------------------------------------------------------
    * Esse é o FormDin 5, que é uma reconstrução do FormDin 4 Sobre o Adianti 7.X
    * os parâmetros do metodos foram marcados veja documentação da classe para
    * saber o que cada marca singinifica.
    * ------------------------------------------------------------------------
    *
    * @param mixed $actionsLabel- 1: Texto ações.
    * @param mixed  $actionsName- 2: FORMDIN5 Nome do metodo da ação (string) no mesmo Form ou  Array [FormDestino,actionsName]
    * @param boolean $header    - 3: FORMDIN5 mostrar ação Título. DEFAULT=false, mostra no rodapé. TRUE = mostra no Título
    * @param string $iconImagem - 4: FORMDIN5 icone ou imagem do botão.
    * @param string $color      - 5: FORMDIN5 cor do icone.
    * @return TButton
    */
    public function setActionLink( $actionsLabel
                                 , $actionsName=null
                                 , $header=false
                                 , $iconImagem=null
                                 , $color=null)
    {
        return $this->setAction($actionsLabel, $actionsName, $header, $iconImagem, $color,false);
    } 

   /**
    * Define os botões de ação no Título do formulario, do TIPO GET.
    *
    * ------------------------------------------------------------------------
    * Esse é o FormDin 5, que é uma reconstrução do FormDin 4 Sobre o Adianti 7.X
    * os parâmetros do metodos foram marcados veja documentação da classe para
    * saber o que cada marca singinifica.
    * ------------------------------------------------------------------------
    *
    * @param mixed $actionsLabel- 1: Texto ações.
    * @param mixed  $actionsName- 2: FORMDIN5 Nome do metodo da ação (string) no mesmo Form ou  Array [FormDestino,actionsName]
    * @param boolean $header    - 3: FORMDIN5 mostrar ação Título. DEFAULT=TRUE, mostra no Título. false, mostra no rodapé. 
    * @param string $iconImagem - 4: FORMDIN5 icone ou imagem do botão.
    * @param string $color      - 5: FORMDIN5 cor do icone.
    * @return TButton
    */
    public function setActionHeaderLink( $actionsLabel
                                       , $actionsName=null
                                       , $header=true, $iconImagem=null, $color=null)
    {
        $header = is_null($header)?true:false;
        return $this->setAction($actionsLabel, $actionsName, $header, $iconImagem, $color,false);
    }


    /**
    * Adiciona um campo oculto ao layout
    * ------------------------------------------------------------------------
    * Esse é o FormDin 5, que é uma reconstrução do FormDin 4 Sobre o Adianti 7.X
    * os parâmetros do metodos foram marcados veja documentação da classe para
    * saber o que cada marca singinifica.
    * ------------------------------------------------------------------------
    *
    * @param string $strName       - 1: Id do Campo
    * @param string $strValue      - 2: Valor inicial
    * @param boolean $boolRequired - 3: True = Obrigatorio; False (Defalt) = Não Obrigatorio  
    * @return TFormDinHiddenField
    */
    public function addHiddenField(string $id
                                ,string $strValue=null
                                ,$boolRequired = false)
    {
        $formField = new TFormDinHiddenField($id,$strValue,$boolRequired);
        $objField = $formField->getAdiantiObj();
        //$this->adiantiObj->addFields([$objField]);
        $this->addElementFormList($objField,self::TYPE_HIDDEN);
        return $formField;
    }

    /**
     * Adicionar campo entrada de dados texto livre.
     * ------------------------------------------------------------------------
     * Esse é o FormDin 5, que é uma reconstrução do FormDin 4 Sobre o Adianti 7.X
     * os parâmetros do metodos foram marcados veja documentação da classe para
     * saber o que cada marca singinifica.
     * ------------------------------------------------------------------------
     *
     * @param string $id              -  1: ID do campo
     * @param string $strLabel        -  2: Label do campo
     * @param integer $intMaxLength   -  3: tamanho máximo de caracteres
     * @param boolean $boolRequired   -  4: Obrigatorio ou não. DEFAULT = False.
     * @param integer $intSize        -  5: NOT_IMPLEMENTED quantidade de caracteres visíveis
     * @param string $strValue        -  6: texto preenchido
     * @param boolean $boolNewLine    -  7: Default TRUE = cria nova linha , FALSE = fica depois do campo anterior
     * @param string $strHint         -  8: NOT_IMPLEMENTED
     * @param string $strExampleText  -  9: PlaceHolder é um Texto de exemplo
     * @param boolean $boolLabelAbove - 10: Label sobre o campo. Default FALSE = Label mesma linha, TRUE = Label acima
     * @param boolean $boolNoWrapLabel- 11: NOT_IMPLEMENTED
     * @return TFormDinTextField
     */
    public function addTextField(string $id
                                ,string $strLabel
                                ,int $intMaxLength = null
                                ,$boolRequired = false
                                ,int $intSize=null
                                ,string $strValue=null
                                ,$boolNewLine = true
                                ,string $strHint = null
                                ,string $strExampleText =null
                                ,$boolLabelAbove=false
                                ,$boolNoWrapLabel = null)
    {
        $formField = new TFormDinTextField($id
                                    ,$strLabel
                                    ,$intMaxLength
                                    ,$boolRequired
                                    ,$intSize
                                    ,$strValue);
        $formField->setExampleText($strExampleText);
        $objField = $formField->getAdiantiObj();
        $label = $formField->getLabel();
        //$this->addFields($label ,$objField ,$boolLabelAbove);
        $this->addElementFormList($objField,self::TYPE_FIELD,$label,$boolNewLine,$boolLabelAbove);
        return $formField;
    }

    /**
     * Adicionar campo de entrada de texto com multiplas linhas ( memo ) equivalente ao html textarea
     * ------------------------------------------------------------------------
     * Esse é o FormDin 5, que é uma reconstrução do FormDin 4 Sobre o Adianti 7.X
     * os parâmetros do metodos foram marcados veja documentação da classe para
     * saber o que cada marca singinifica.
     * ------------------------------------------------------------------------
     *
     * @param string  $strName         - 1: ID do campo
     * @param string  $strLabel        - 2: Label
     * @param integer $intMaxLength    - 3: Tamanho maximos
     * @param boolean $boolRequired    - 4: Obrigatorio
     * @param integer $intColumns      - 5: Largura use px ou %, valores inteiros serão multiplicados 1.5 e apresentado em px
     * @param integer $intRows         - 6: Altura use px ou %, valores inteiros serão multiplicados 4 e apresentado em px
     * @param boolean $boolNewLine     - 7: Default TRUE = cria nova linha , FALSE = fica depois do campo anterior
     * @param boolean $boolLabelAbove  - 8: Label sobre o campo. Default FALSE = Label mesma linha, TRUE = Label acima
     * @param boolean $boolShowCounter - 9: NOT_IMPLEMENTED Contador de caracteres ! Só funciona em campos não RichText
     * @param string  $strValue       - 10: texto preenchido
     * @param string $boolNoWrapLabel - 11: NOT_IMPLEMENTED
     * @param string $placeholder     - 12: FORMDIN5 PlaceHolder é um Texto de exemplo
     * @param string $boolShowCountChar 13: FORMDIN5 Mostra o contador de caractes.  Default TRUE = mostra, FASE = não mostra
     * @return TFormDinMemoField
     */
    public function addMemoField( $strName
   		                       , $strLabel=null
   		                       , $intMaxLength
   		                       , $boolRequired=null
   		                       , $intColumns=null
   		                       , $intRows=null
   		                       , $boolNewLine=null
   		                       , $boolLabelAbove=false
   		                       , $boolShowCounter=null
   		                       , $strValue=null
                               , $boolNoWrapLabel=null
                               , $placeholder=null 
                               , $boolShowCountChar=true)
    {
        $formField = new TFormDinMemoField( $strName, $strLabel, $intMaxLength
                                      , $boolRequired
                                      , $intColumns
                                      , $intRows
                                      , $boolNewLine
                                      , $boolLabelAbove
                                      , $boolShowCounter
                                      , $strValue
                                      , $boolNoWrapLabel
                                      , $placeholder 
                                      , $boolShowCountChar
                                    );
        $objField = $formField->getFullComponent();
        //$objField = $formField->getAdiantiObj();
        $label = $formField->getLabel();
        //$this->addFields($label ,$objField ,$boolLabelAbove);
        $this->addElementFormList($objField,self::TYPE_FIELD,$label,$boolNewLine,$boolLabelAbove);
    	return $formField;
    }

    /****
     * Adicona um campo data ou mes/ano ou dia/mes de acordo com o parametro strMaxType
     * Tipo de máscara: DMY, DM, MY
     *  
     * @param string  $strName         - 1: Id do Campo
     * @param string  $strLabel        - 2: Label do Campo
     * @param boolean $boolRequired    - 3: DEFAULT = false não obrigatório
     * @param boolean $boolNewLine     - 4: Default TRUE = campo em nova linha, FALSE continua na linha anterior
     * @param string  $strValue        - 5: Valor inicial
     * @param string  $strMinValue     - 6: Menor data que o campo aceita
     * @param string  $strMaxValue     - 7: Maior data que o campo aceita
     * @param string  $strMaskType     - 8: DEFAULT = dd-mm-yyyy. Tipo de Mascara dd-mm-yyyy (dia/mês/ano), dd-mm (dia/mês), mm-yyyy (mês/ano) 
     * @param boolean $boolButtonVisible - 9: Exibe ou não o botão do calendario.
     * @param string  $strExampleText  - 10: Texto de exemplo
     * @param boolean $boolLabelAbove  - 11: DEFAULT = false. Label acima do campo = true
     * @param string  $boolNoWrapLabel - 12: NOT_IMPLEMENTED
     * @param string  $databaseMask    - 13: FORMDIN5 Mascará usada no banco de dados
     * @return TDate
     */
    public function addDateField( $strName
                            , $strLabel=null
                            , $boolRequired=false
                            , $boolNewLine=null
                            , $strValue=null
                            , $strMinValue=null
                            , $strMaxValue=null
                            , $strMaskType=null
                            , $boolButtonVisible=null
                            , $strExampleText=null
                            , $boolLabelAbove=null
                            , $boolNoWrapLabel=null
                            , $databaseMask=null
                            )
    {        
        $formField = new TFormDinDate( $strName
                                     , $strLabel
                                     , $boolRequired
                                     , $boolNewLine
                                     , $strValue
                                     , $strMinValue
                                     , $strMaxValue
                                     , $strMaskType
                                     , $boolButtonVisible
                                     , $strExampleText
                                     , $boolLabelAbove
                                     , $boolNoWrapLabel
                                     , $databaseMask
                                    );
        $objField = $formField->getAdiantiObj();
        $label = $formField->getLabel();
    	$this->addElementFormList($objField,self::TYPE_FIELD,$label,$boolNewLine,$boolLabelAbove);
    	return $formField;
    }

    /****
     * Adicona um campo Data Hora  ou mes/ano ou dia/mes de acordo com o parametro strMaxType
     * Tipo de máscara: DMY, DM, MY
     *  
     * @param string  $strName         - 1: Id do Campo
     * @param string  $strLabel        - 2: Label do Campo
     * @param boolean $boolRequired    - 3: DEFAULT = false não obrigatório
     * @param boolean $boolNewLine     - 4: Default TRUE = campo em nova linha, FALSE continua na linha anterior
     * @param string  $strValue        - 5: Valor inicial
     * @param string  $strMinValue     - 6: Menor data que o campo aceita
     * @param string  $strMaxValue     - 7: Maior data que o campo aceita
     * @param string  $strMaskType     - 8: DEFAULT = dd-mm-yyyy. Tipo de Mascara dd-mm-yyyy (dia/mês/ano), dd-mm (dia/mês), mm-yyyy (mês/ano) 
     * @param boolean $boolButtonVisible - 9: Exibe ou não o botão do calendario.
     * @param string  $strExampleText  - 10: Texto de exemplo
     * @param boolean $boolLabelAbove  - 11: DEFAULT = false. Label acima do campo = true
     * @param string  $boolNoWrapLabel - 12: NOT_IMPLEMENTED
     * @param string  $databaseMask    - 13: FORMDIN5 Mascará usada no banco de dados
     * @return TDate
     */
    public function addDateTimeField( $strName
                            , $strLabel=null
                            , $boolRequired=false
                            , $boolNewLine=null
                            , $strValue=null
                            , $strMinValue=null
                            , $strMaxValue=null
                            , $strMaskType=null
                            , $boolButtonVisible=null
                            , $strExampleText=null
                            , $boolLabelAbove=null
                            , $boolNoWrapLabel=null
                            , $databaseMask=null
                            )
    {        
        $formField = new TFormDinDateTime( $strName
                                     , $strLabel
                                     , $boolRequired
                                     , $boolNewLine
                                     , $strValue
                                     , $strMinValue
                                     , $strMaxValue
                                     , $strMaskType
                                     , $boolButtonVisible
                                     , $strExampleText
                                     , $boolLabelAbove
                                     , $boolNoWrapLabel
                                     , $databaseMask
                                    );
        $objField = $formField->getAdiantiObj();
        $label = $formField->getLabel();
    	$this->addElementFormList($objField,self::TYPE_FIELD,$label,$boolNewLine,$boolLabelAbove);
    	return $formField;
    }

    /**
     * Cria um RadioGroup com efeito visual de Switch dp BootStrap
     * ------------------------------------------------------------------------
     * Esse é o FormDin 5, que é uma reconstrução do FormDin 4 Sobre o Adianti 7.X
     * os parâmetros do metodos foram marcados veja documentação da classe para
     * saber o que cada marca singinifica.
     * ------------------------------------------------------------------------
     * 
     * @param string $id             - 1: ID do campo
     * @param string $strLabel       - 2: Label do campo
     * @param boolean $boolRequired  - 3: Obrigatorio
     * @param array $itens           - 4: Informe um array do tipo "chave=>valor", com maximo de 2 elementos
     * @param boolean $boolNewLine   - 5: Default TRUE = cria nova linha , FALSE = fica depois do campo anterior
     * @param boolean $boolLabelAbove- 6: Label sobre o campo. Default FALSE = Label mesma linha, TRUE = Label acima
     * @return TRadioGroup
     */
    public function addSwitchField(string $id
                                  ,string $strLabel
                                  ,$boolRequired = false
                                  ,array $itens=null
                                  ,$boolNewLine=null
                                  ,$boolLabelAbove=null)
    {
        $formField = new TFormDinSwitch($id,$strLabel,$boolRequired,$itens);
        $objField = $formField->getAdiantiObj();
        $label = $formField->getLabel();
        //$this->addFields($label ,$objField ,$boolLabelAbove);
        $this->addElementFormList($objField,self::TYPE_FIELD,$label,$boolNewLine,$boolLabelAbove);

        return $formField;
    }
    /**
     * Adicionar campo entrada de dados texto com mascara
     * ------------------------------------------------------------------------
     * Esse é o FormDin 5, que é uma reconstrução do FormDin 4 Sobre o Adianti 7.X
     * os parâmetros do metodos foram marcados veja documentação da classe para
     * saber o que cada marca singinifica.
     * ------------------------------------------------------------------------
     * 
     * S - Represents an alpha character (A-Z,a-z)
     * 9 - Represents a numeric character (0-9)
     * A - Represents an alphanumeric character (A-Z,a-z,0-9)
     *
     * @param string $id              - 1: id do campo
     * @param string $strLabel        - 2: Rotulo do campo que irá aparece na tela
     * @param boolean $boolRequired   - 3: Obrigatorio
     * @param string $strMask         - 4: A mascara
     * @param boolean $boolNewLine    - 5: Default TRUE = cria nova linha , FALSE = fica depois do campo anterior
     * @param string $strValue        - 6: texto preenchido
     * @param boolean $boolLabelAbove - 7: Label sobre o campo. Default FALSE = Label mesma linha, TRUE = Label acima
     * @param boolean $boolNoWrapLabel- 8: NOT_IMPLEMENTED
     * @param string $strExampleText  - 9: PlaceHolder é um Texto de exemplo
     * @param boolean $boolSendMask   -10: FORMDIN5: Se as mascara deve ser enviada ou não para o post. DEFAULT = False.
     * @return void
     */
    public function addMaskField( $id
                                , $label=null
                                , $boolRequired=false
                                , $strMask=null
                                , $boolNewLine=null
                                , $strValue=null
                                , $boolLabelAbove=false
                                , $boolNoWrapLabel=null
                                , $strExampleText=null 
                                , $boolSendMask=false)
    {
        $formField = new TFormDinMaskField($id,$label,$boolRequired
                                              ,$strMask,$boolNewLine,$strValue
                                              ,$boolLabelAbove,$boolNoWrapLabel
                                              ,$strExampleText,$boolSendMask);
        $objField = $formField->getAdiantiObj();
        $label = $formField->getLabel();
        //$this->addFields($label ,$objField ,$boolLabelAbove);
        $this->addElementFormList($objField,self::TYPE_FIELD,$label,$boolNewLine,$boolLabelAbove);
        return $formField;
    }    

    /**
     * Adicionar campo tipo combobox ou menu select
     * ------------------------------------------------------------------------
     * Esse é o FormDin 5, que é uma reconstrução do FormDin 4 Sobre o Adianti 7.X
     * os parâmetros do metodos foram marcados veja documentação da classe para
     * saber o que cada marca singinifica.
     * ------------------------------------------------------------------------
     *
     * $mixOptions = array no formato "key=>value". No FormDin 5 só permite array PHP
     * $strKeyColumn = nome da coluna que será utilizada para preencher os valores das opções
     * $strDisplayColumn = nome da coluna que será utilizada para preencher as opções que serão exibidas para o usuário
     * $strDataColumns = informações extras do banco de dados que deverão ser adicionadas na tag option do campo select
     *
     * <code>
     * 	// exemplos
     * 	$frm->addSelectField('tipo','Tipo:',false,'1=Tipo 1,2=Tipo 2');
     * 	$frm->addSelectField('tipo','Tipo:',false,'tipo');
     * 	$frm->addSelectField('tipo','Tipo:',false,'select * from tipo order by descricao');
     * 	$frm->addSelectField('tipo','Tipo:',false,'tipo|descricao like "F%"');
     *
     *  //Exemplo espcial - Campo obrigatorio e sem senhum elemento pre selecionado.
     *  $frm->addSelectField('tipo','Tipo',true,$tiposDocumentos,null,null,null,null,null,null,' ','');
     * </code>
     *
     * @param string  $strName        - 1: ID do campo
     * @param string  $strLabel       - 2: Label do campo
     * @param boolean $boolRequired   - 3: Obrigatorio. Default FALSE
     * @param mixed   $mixOptions     - 4: array dos valores. no formato "key=>value". No FormDin 5 só permite array PHP
     * @param boolean $boolNewLine    - 5: Default TRUE = cria nova linha , FALSE = fica depois do campo anterior
     * @param boolean $boolLabelAbove - 6: Label sobre o campo. Default FALSE = Label mesma linha, TRUE = Label acima
     * @param mixed   $mixValue       - 7: NOT_IMPLEMENTED Valor DEFAULT, informe o ID do array
     * @param boolean $boolMultiSelect- 8: NOT_IMPLEMENTED Default FALSE = SingleSelect, TRUE = MultiSelect
     * @param integer $intSize             - 9: NOT_IMPLEMENTED Default 1. Num itens que irão aparecer. 
     * @param integer $intWidth           - 10: NOT_IMPLEMENTED Largura em Pixels
     * @param string  $strFirstOptionText - 11: NOT_IMPLEMENTED First Key in Display
     * @param string  $strFirstOptionValue- 12: NOT_IMPLEMENTED Frist Valeu in Display, use value NULL for required. Para o valor DEFAULT informe o ID do $mixOptions e $strFirstOptionText = '' e não pode ser null
     * @param string  $strKeyColumn       - 13: NOT_IMPLEMENTED
     * @param string  $strDisplayColumn   - 14: NOT_IMPLEMENTED
     * @param string  $boolNoWrapLabel    - 15: NOT_IMPLEMENTED
     * @param string  $strDataColumns     - 16: NOT_IMPLEMENTED
     * @return TCombo
     */
    public function addSelectField(string $id
                                  ,string $strLabel
                                  ,$boolRequired = false
                                  ,array $mixOptions
                                  ,$boolNewLine = true
                                  ,$boolLabelAbove = false)
    {
        $formField = new TFormDinSelectField($id,$strLabel,$boolRequired,$mixOptions);
        $objField = $formField->getAdiantiObj();
        $label = $this->getLabelField($strLabel,$boolRequired);
        //$this->addFields($label ,$objField ,$boolLabelAbove);
        $this->addElementFormList($objField,self::TYPE_FIELD,$label,$boolNewLine,$boolLabelAbove);
        return $formField;
    }
    //-----------------------------------------------------------------------------
    /**
     * Adicicionar campo tipo radiobutton
     * ------------------------------------------------------------------------
     * Esse é o FormDin 5, que é uma reconstrução do FormDin 4 Sobre o Adianti 7.X
     * os parâmetros do metodos foram marcados veja documentação da classe para
     * saber o que cada marca singinifica.
     * ------------------------------------------------------------------------
     * 
     * @param string $strName         - 1: field ID
     * @param string $strLabel        - 2: Label field
     * @param boolean $boolRequired   - 3: TRUE = Required, FALSE = not Required
     * @param array $arrOptions       - 4: Array Options
     * @param boolean $boolNewLine    - 5: TRUE = new line, FALSE = no, DEFAULT ou NULL = FALSE
     * @param boolean $boolLabelAbove - 6: TRUE = Titulo em cima das opções, FALSE = titulo lateral
     * @param string  $strValue       - 7: Valor DEFUALT, informe do id do array
     * @param integer $intQtdColumns  - 8: Quantidade de colunas, valor DEFAULT = 1;
     * @param integer $intWidth       - 9: DEPRECATED
     * @param integer $intHeight      -10: DEPRECATED
     * @param integer $intPaddingItems-11: NOT_IMPLEMENTED
     * @param boolean $boolNoWrapLabel-12: NOT_IMPLEMENTED
     * @param boolean $boolNowrapText -13: NOT_IMPLEMENTED
     * @param boolean $useButton      -14: FORMDIN5 Default FALSE = estilo radio comum, TRUE = estilo tipo botões
     * @return TRadio
    */
    public function addRadioField($strName
    			           		, $strLabel=null
    			           		, $boolRequired=null
    			           		, $arrOptions=null
    			           		, $boolNewLine=null
    			           		, $boolLabelAbove=null
    			           		, $strValue=null
    			           		, $intQtdColumns=null
    			           		, $intWidth=null
    			           		, $intHeight=null
    			           		, $intPaddingItems=null
    			           		, $boolNoWrapLabel=null
                                , $boolNowrapText=null
                                , $useButton=null
    			           		){
        $formField = new TFormDinRadio($strName
                                     , $strLabel
                                     , $boolRequired
                                     , $arrOptions
                                     , $boolNewLine
                                     , $boolLabelAbove
                                     , $strValue
                                     , $intQtdColumns
                                     , $intWidth
                                     , $intHeight
                                     , $intPaddingItems
                                     , $boolNoWrapLabel
                                     , $boolNowrapText
                                     , $useButton
                                     );
        $objField = $formField->getAdiantiObj();
        $label = $this->getLabelField($strLabel,$boolRequired);
        $this->addElementFormList($objField,self::TYPE_FIELD,$label,$boolNewLine,$boolLabelAbove);
        return $formField;
    }

    /**
     * Adiciona campo tipo grupo com legenda na parte superior
     * ------------------------------------------------------------------------
     * Esse é o FormDin 5, que é uma reconstrução do FormDin 4 Sobre o Adianti 7.X
     * os parâmetros do metodos foram marcados veja documentação da classe para
     * saber o que cada marca singinifica.
     * ------------------------------------------------------------------------    
     * Se o parametro $intHeight for null será auto height
     * se o parametro $intWidth for null utilizado a largura do form
     *
     * <code>
     * 	// sem quebra nos rotulos quando excederem a largura da coluna definida
     *   $frm->addGroupField('gp01','Grupo Teste');
     * 	// com quebra nos rotulos quando excederem a largura da coluna definida
     *   $frm->addGroupField('gp01','Grupo Teste',null,null,null,true);
     * </code>
     *
     * @param string $strName          - 01: NOT_IMPLEMENTED
     * @param string $strLegend        - 02: Label que irá aparecer para o usuario 
     * @param integer $intHeight       - 03: NOT_IMPLEMENTED altura do grupo. NULL = auto height
     * @param integer $intWidth        - 04: NOT_IMPLEMENTED largura do grupo. NULL = largura do form
     * @param boolean $boolNewLine     - 05: NOT_IMPLEMENTED Default TRUE = campo em nova linha, FALSE continua na linha anterior
     * @param boolean $boolNoWrapLabel - 06: NOT_IMPLEMENTED
     * @param boolean $boolCloseble    - 07: NOT_IMPLEMENTED pode fechar ou não
     * @param string  $strAccordionId  - 08: NOT_IMPLEMENTED
     * @param boolean $boolOpened      - 09: NOT_IMPLEMENTED inicia aberto
     * @param string $imgOpened        - 10: NOT_IMPLEMENTED
     * @param string $imgClosed        - 11: NOT_IMPLEMENTED
     * @param boolean $boolOverflowX   - 12: NOT_IMPLEMENTED
     * @param boolean $boolOverflowY   - 13: NOT_IMPLEMENTED
     * @return TGroupBox
     */
	public function addGroupField( $strName=null
                                , $strLegend=null
                                , $strHeight=null
                                , $strWidth=null
                                , $boolNewLine=null
                                , $boolNoWrapLabel=null
                                , $boolCloseble=null
                                , $strAccordionId=null
                                , $boolOpened=null
                                , $imgOpened=null
                                , $imgClosed=null
                                , $boolOverflowX=null
                                , $boolOverflowY=null )
    {
		//$this->currentContainer[ ] = $field;
        $strLegend = empty($strLegend)?'':$strLegend;
        $objField = new TFormSeparator($strLegend);
        $this->addElementFormList($objField,self::TYPE_LAYOUT);
		return $objField;
    }
    
    /**
     * Este método fecha um campo grupo ou um campo aba para que os campos
     * seguintes fique abaixo dos mesmos e não dentro deles.
     * ------------------------------------------------------------------------
     * Esse é o FormDin 5, que é uma reconstrução do FormDin 4 Sobre o Adianti 7.X
     * os parâmetros do metodos foram marcados veja documentação da classe para
     * saber o que cada marca singinifica.
     * ------------------------------------------------------------------------  
     */
    public function closeGroup()
    {
        $this->addGroupField();
    }

    /**
     * Campo de uso geral para insersão manual de códigos html na página
     * ------------------------------------------------------------------------
     * Esse é o FormDin 5, que é uma reconstrução do FormDin 4 Sobre o Adianti 7.X
     * os parâmetros do metodos foram marcados veja documentação da classe para
     * saber o que cada marca singinifica.
     * ------------------------------------------------------------------------
     *
      * Se o label for null, não será criado o espaço referente a ele no formulário, para criar
     * um label invisível defina como "" o seu valor
     *
     * criado o espaço
     * @param string $strName        - 1: ID do campo
     * @param string $strValue       - 2: Texto HTML que irá aparece dentro
     * @param string $strIncludeFile - 3: NOT_IMPLEMENTED Arquivo que será incluido
     * @param string $strLabel       - 4: Label do campo
     * @param string $strWidth       - 5: NOT_IMPLEMENTED
     * @param string $strHeight      - 6: NOT_IMPLEMENTED
     * @param boolean $boolNewLine   - 7: Default TRUE = campo em nova linha, FALSE continua na linha anterior
     * @param boolean $boolLabelAbove  8: Label sobre o campo. Default FALSE = Label mesma linha, TRUE = Label acima
     * @return THtml Field
     */
    public function addHtmlField( string $id
                                , $strValue=null
                                , $strIncludeFile=null
                                , $strLabel=null
                                , $strHeight=null
                                , $strWidth=null
                                , $boolNewLine=null
                                , $boolLabelAbove=null
                                , $boolNoWrapLabel=null )
    {
        $formField = new TFormDinHtmlField($id,$strValue
                                          ,$strIncludeFile
                                          ,$strLabel
                                          ,$strHeight
                                          ,$strWidth,$boolNewLine,$boolNoWrapLabel);
        $objField = $formField->getAdiantiObj();
        $label = $formField->getLabel();
        //$this->addFields($label ,$objField ,$boolLabelAbove);
        $this->addElementFormList($objField,self::TYPE_FIELD,$label,$boolNewLine,$boolLabelAbove);
        return $formField;
    }

    /**
     * Adiciona campo de entrada de dados numérico
     * ------------------------------------------------------------------------
     * Esse é o FormDin 5, que é uma reconstrução do FormDin 4 Sobre o Adianti 7.X
     * os parâmetros do metodos foram marcados veja documentação da classe para
     * saber o que cada marca singinifica.
     * ------------------------------------------------------------------------
     * 
     * @param string $strName            - 1: ID do campo
     * @param string $strLabel           - 2: Label do campo, que irá aparecer na tela do usuario
     * @param integer $intMaxLength      - 3: Quantidade maxima de digitos, considerando ponto e virgula
     * @param boolean $boolRequired      - 4: Obrigatorio
     * @param integer $intDecimalPlaces  - 5: Quantidade de casas decimais.
     * @param boolean $boolNewLine       - 6: Campo em nova linha. Default = true = inicia em nova linha, false = continua na linha anterior 
     * @param string $strValue           - 7: valor inicial do campo
     * @param string $strMinValue        - 8: valor minimo permitido. Null = não tem limite.
     * @param string $strMaxValue        - 9: valor maxima permitido. Null = não tem limite.
     * @param boolean $boolFormatInteger -10: Inteiros com ou sem ponto de separação. Recebe: (virgula), (ponto), true = ponto, false = sem nada
     * @param string $strDirection       -11: NOT_IMPLEMENTED
     * @param boolean $boolAllowZero     -12: NOT_IMPLEMENTED
     * @param boolean $boolAllowNull     -13: NOT_IMPLEMENTED
     * @param boolean $boolLabelAbove    -14: Label sobre o campo. Default FALSE = Label mesma linha, TRUE = Label acima
     * @param boolean $boolNoWrapLabel   -15: NOT_IMPLEMENTED
     * @param string $tooltip            -16: Texto Tooltip
     * @param boolean $replaceOnPost     -17: FORMDIN5: TRUE: process mask when editing and saving
     * @param string $placeholder        -18: FORMDIN5: Texto do Place Holder
     * @param string $decimalsSeparator  -19: FORMDIN5: separador decimal. Não pode ser chamado por metodo
     * @return TNumber
     */       
	public function addNumberField( $strName
				           		, $strLabel=null
				           		, $intMaxLength
				           		, $boolRequired=null
				           		, $intDecimalPlaces=null
				           		, $boolNewLine=null
				           		, $strValue=null
				           		, $strMinValue=null
				           		, $strMaxValue=null
				           		, $boolFormatInteger=null
				           		, $strDirection=null
				           		, $boolAllowZero=null
				           		, $boolAllowNull=null
				           		, $boolLabelAbove=null
				           		, $boolNoWrapLabel=null
                                , $strHint=null 
                                , $replaceOnPost=true
                                , $placeholder=null
                                , $decimalsSeparator=null
                                )
	{
		$formField = new TFormDinNumericField( $strName
                                            , $strLabel
                                            , $intMaxLength
                                            , $boolRequired
                                            , $intDecimalPlaces
                                            , $boolNewLine
                                            , $strValue
                                            , $strMinValue
                                            , $strMaxValue
                                            , $boolFormatInteger
                                            , $strDirection
                                            , $boolAllowZero
                                            , $boolAllowNull
                                            , $boolLabelAbove
                                            , $boolNoWrapLabel
                                            , $strHint
                                            , $replaceOnPost
                                            , $placeholder
                                            , $decimalsSeparator
                                            );
        $objField = $formField->getAdiantiObj();
        $label = $formField->getLabel();
        $this->addElementFormList($objField,self::TYPE_FIELD,$label,$boolNewLine,$boolLabelAbove);
        return $formField;
	}



    //----------------------------------------------------------------
    //----------------------------------------------------------------
    //----------------------------------------------------------------
    //----------------------------------------------------------------

    /**
     * @deprecated mantido apenas para diminir o impacto na migração do FormDin 4 para FormDin 5 sobre Adianti 7.1
     * @return void
     */
    public function setShowCloseButton( $boolNewValue=null ){
        ValidateHelper::validadeParam('$boolNewValue',$boolNewValue
                                    ,ValidateHelper::WARNING
                                    ,ValidateHelper::MSG_DECREP
                                    ,__CLASS__,__METHOD__,__LINE__); 
    }

    /**
     * @deprecated mantido apenas para diminir o impacto na migração do FormDin 4 para FormDin 5 sobre Adianti 7.1
     * @return void
     */
    public function setFlat($boolNewValue=null){
        ValidateHelper::validadeParam('$boolNewValue',$boolNewValue
                                    ,ValidateHelper::WARNING
                                    ,ValidateHelper::MSG_DECREP
                                    ,__CLASS__,__METHOD__,__LINE__); 
    }

    /**
     * @deprecated mantido apenas para diminir o impacto na migração do FormDin 4 para FormDin 5 sobre Adianti 7.1
     * @return void
     */
    public function setMaximize($boolNewValue = null){
        ValidateHelper::validadeParam('$boolNewValue',$boolNewValue
                                    ,ValidateHelper::WARNING
                                    ,ValidateHelper::MSG_DECREP
                                    ,__CLASS__,__METHOD__,__LINE__); 
    }

    /**
     * @deprecated mantido apenas para diminir o impacto na migração do FormDin 4 para FormDin 5 sobre Adianti 7.1
     * @return void
     */
    public function setHelpOnLine(){
        ValidateHelper::validadeParam('$setHelpOnLine',null
                                ,ValidateHelper::WARNING
                                ,ValidateHelper::MSG_DECREP
                                ,__CLASS__,__METHOD__,__LINE__); 
    }
}