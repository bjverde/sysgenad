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
 * Este programa é distribuí1do na esperança que possa ser útil, mas SEM NENHUMA
 * GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO ou
 * APLICAÇÃO EM PARTICULAR. Veja a Licen?a Pública Geral GNU/LGPL em portugu?s
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
    protected $adiantiObj;

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
     * @param string $strName   - 1: Titulo que irá aparecer no Form
     * @param string $strHeight - 2: DEPRECATED: INFORME NULL para remover o Warning
     * @param string $strWidth  - 3: DEPRECATED: INFORME NULL para remover o Warning
     * @param bool $strFormName - 4: ID nome do formulario para criação da tag form. Padrão=formdin
     * @param string $strMethod - 5: NOT_IMPLEMENTED: metodo GET ou POST, utilizado pelo formulario para submeter as informações. padrão=POST
     * @param string $strAction - 6: NOT_IMPLEMENTED: página/url para onde os dados serão enviados. Padrão = propria página
     * @param boolean $boolPublicMode - 7: NOT_IMPLEMENTED: ignorar mensagem fwSession_exprired da aplicação e não chamar atela de login
     * @param boolean $boolRequired - 8: FORMDIN5: Se vai fazer validação no Cliente (Navegador)
     *
     * @return BootstrapFormBuilder
     */    
    public function __construct(string $strTitle
                               ,$strHeigh = null
                               ,$strWidth = null
                               ,string $strName = 'formdin'
                               ,$strMethod = null
                               ,$strAction  = null
                               ,$boolPublicMode  = null
                               ,$boolClientValidation = true)
    {
        $this->validateDeprecated($strHeigh,$strWidth);
        $this->adiantiObj = new BootstrapFormBuilder($strName);
        $this->adiantiObj->setFormTitle($strTitle);
        //$this->adiantiObj->setFieldSizes('100%');
        $this->adiantiObj->setClientValidation($boolClientValidation);
        $this->adiantiObj->generateAria(); // automatic aria-label
        return $this->getAdiantiObj();
    }

    public function validateDeprecated($strHeigh,$strWidth)
    {
        ValidateHelper::validadeParam('strHeigh',$strHeigh
                                     ,ValidateHelper::TYPE_ERRO_WARNING
                                     ,ValidateHelper::TYPE_ERRO_MSG_DECREP
                                     ,__CLASS__,__METHOD__,__LINE__);

        ValidateHelper::validadeParam('strWidth',$strWidth
                                     ,ValidateHelper::TYPE_ERRO_WARNING
                                     ,ValidateHelper::TYPE_ERRO_MSG_DECREP
                                     ,__CLASS__,__METHOD__,__LINE__);                                     
    }

    public function getAdiantiObj()
    {
        return $this->adiantiObj;
    }

    public function show()
    {
        return $this->getAdiantiObj();
    }

    /**
     * Inclusão 
     * @param array $label - label que será incluido com o campo
     * @param array $campo - campo que será incluido
     * @param array $boolLabelAbove - informa se o Label é acima
     */
    public function addFields($label, $campo, $boolLabelAbove = false)
    {
        if($boolLabelAbove){
            $this->adiantiObj->addFields([$label, $campo]);
        }else{
            $this->adiantiObj->addFields([$label], [$campo]);
        }
    }

    protected function getLabelField($strLabel,$boolRequired=false)
    {
        $formDinLabelField = new TFormDinLabelField($strLabel,$boolRequired);
        $label = $formDinLabelField->getAdiantiObj();
        return $label;
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
        $this->adiantiObj->addFields([$objField]);
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
     * @param boolean $boolNewLine    -  7: NOT_IMPLEMENTED Nova linha
     * @param string $strHint         -  9: NOT_IMPLEMENTED
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
        $this->addFields($label ,$objField ,$boolLabelAbove);
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
     * @param boolean $boolNewLine     - 7: NOT_IMPLEMENTED nova linha
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
                                      , $boolRequired, $intColumns, $intRows
                                      , $boolNewLine, $boolLabelAbove
                                      , $boolShowCounter, $strValue
                                      , $boolNoWrapLabel
                                      , $placeholder 
                                      , $boolShowCountChar);
        //$objField = $formField->getFullComponent();
        $objField = $formField->getAdiantiObj();
        $label = $formField->getLabel();
        $this->addFields($label ,$objField ,$boolLabelAbove);
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
     * @param boolean $boolLabelAbove- 5: Label sobre o campo. Default FALSE = Label mesma linha, TRUE = Label acima
     * @return TRadioGroup
     */
    public function addSwitchField(string $id
                                  ,string $strLabel
                                  ,$boolRequired = false
                                  ,array $itens= null
                                  ,$boolLabelAbove=false)
    {
        $formField = new TFormDinSwitch($id,$strLabel,$boolRequired,$itens);
        $objField = $formField->getAdiantiObj();
        $label = $this->getLabelField($strLabel,$boolRequired);
        $this->addFields($label ,$objField ,$boolLabelAbove);
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
     * @param boolean $boolNewLine    - 5: NOT_IMPLEMENTED Nova linha
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
        $this->addFields($label ,$objField ,$boolLabelAbove);
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
     * @param boolean $boolNewLine    - 5: NOT_IMPLEMENTED Default TRUE = cria nova linha , FALSE = fica depois do campo anterior
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
        $this->addFields($label ,$objField ,$boolLabelAbove);
        return $formField;
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
     * @param boolean $boolNewLine   - 7: NOT_IMPLEMENTED Default TRUE = campo em nova linha, FALSE continua na linha anterior
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
        $this->addFields($label ,$objField ,$boolLabelAbove);
        return $formField;
    }
    //----------------------------------------------------------------
    //----------------------------------------------------------------
    //----------------------------------------------------------------
    //----------------------------------------------------------------s    

    /**
     * @deprecated mantido apenas para diminir o impacto na migração do FormDin 4 para FormDin 5 sobre Adianti 7.1
     * @return void
     */
    public function setShowCloseButton( $boolNewValue=null ){
        ValidateHelper::validadeParam('$boolNewValue',$boolNewValue
                                    ,ValidateHelper::TYPE_ERRO_WARNING
                                    ,ValidateHelper::TYPE_ERRO_MSG_DECREP
                                    ,__CLASS__,__METHOD__,__LINE__); 
    }

    /**
     * @deprecated mantido apenas para diminir o impacto na migração do FormDin 4 para FormDin 5 sobre Adianti 7.1
     * @return void
     */
    public function setFlat($boolNewValue=null){
        ValidateHelper::validadeParam('$boolNewValue',$boolNewValue
                                    ,ValidateHelper::TYPE_ERRO_WARNING
                                    ,ValidateHelper::TYPE_ERRO_MSG_DECREP
                                    ,__CLASS__,__METHOD__,__LINE__); 
    }

    /**
     * @deprecated mantido apenas para diminir o impacto na migração do FormDin 4 para FormDin 5 sobre Adianti 7.1
     * @return void
     */
    public function setMaximize($boolNewValue = null){
        ValidateHelper::validadeParam('$boolNewValue',$boolNewValue
                                    ,ValidateHelper::TYPE_ERRO_WARNING
                                    ,ValidateHelper::TYPE_ERRO_MSG_DECREP
                                    ,__CLASS__,__METHOD__,__LINE__); 
    }

    /**
     * @deprecated mantido apenas para diminir o impacto na migração do FormDin 4 para FormDin 5 sobre Adianti 7.1
     * @return void
     */
    public function setHelpOnLine(){        
    }
}