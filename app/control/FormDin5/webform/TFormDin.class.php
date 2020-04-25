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
 * Esse é o FormDin 5, que é uma reconstrução do FormDin 4 Sobre o Adianti 7.1
 * 
 * FormDin 5 - Alguns parametros estão marcados como DEPRECATED por não 
 * funcionar no Adianti foram mantidos para diminuir o impacto sobre
 * as migrações.
 *
 * FORMDIN5 = Parametro novo disponivel apenas na nova versão
 * NOT_IMPLEMENTED = parametros que serão implementados em versões futuras
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
     * os parâmetros do metodos foram marcados com:
     * 
     * NOT_IMPLEMENTED = Parâmetro não implementados, talvez funcione em 
     *                   verões futuras do FormDin. Não vai fazer nada
     * DEPRECATED = Parâmetro que não vai funcionar no Adianti e foi mantido
     *              para o impacto sobre as migrações. Vai gerar um Warning
     * FORMDIN5 = Parâmetro novo disponivel apenas na nova versão

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

    /**
     * Inclusão 
     * @param array $label - label que será incluido com o campo
     * @param array $campo - campo que será incluido
     */
    public function addFields(array $label, array $campo)
    {
        $this->adiantiObj->addFields($label, $campo);
    }

    protected function getLabelField($strLabel,$boolRequired)
    {
        $formDinLabelField = new TFormDinLabelField($strLabel,$boolRequired);
        $label = $formDinLabelField->getAdiantiObj();
        return $label;
    }

    /**
    * Adiciona um campo oculto ao layout
    * 
    * FormDin 5 - Alguns parametros foram marcados com NOT_IMPLEMENTED
    * por não funcionar no Adianti 7.X e foram mantidos
    * para diminuir o impacto sobre a migração
    *
    * @param string $strName       - 1: Id do Campo
    * @param string $strValue      - 2: Valor inicial
    * @param boolean $boolRequired - 3: True = Obrigatorio; False (Defalt) = Não Obrigatorio  
    * @return THidden
    */
    public function addHiddenField(string $id
                                ,string $strValue=null
                                ,$boolRequired = false)
    {
        $formField = new TFormDinHiddenField($id,$strValue,$boolRequired);
        $objField = $formField->getAdiantiObj();
        $this->adiantiObj->addFields([$objField]);
        return $objField;
    }

    /**
     * Adicionar campo entrada de dados texto livre.
     * 
     * FormDin 5 - Alguns parametros marcados com NOT_IMPLEMENTED
     * por não funcionar no Adianti 7.X e foram mantidos
     * para diminuir o impacto sobre a migração
     *
     * @param string $id              -  1: ID do campo
     * @param string $strLabel        -  2: Label do campo
     * @param integer $intMaxLength   -  3: tamanho máximo de caracteres
     * @param boolean $boolRequired   -  4: Obrigatorio ou não. DEFAULT = False.
     * @param integer $intSize        -  5: DESATIVADO quantidade de caracteres visíveis
     * @param string $strValue        -  6: texto preenchido
     * @param boolean $boolNewLine    -  7: DESATIVADO Nova linha
     * @param string $strHint         -  9: DESATIVADO
     * @param string $strExampleText  -  9: PlaceHolder é um Texto de exemplo
     * @param boolean $boolLabelAbove - 10: DESATIVADO - Label sobre
     * @param boolean $boolNoWrapLabel- 11: DESATIVADO
     * @return TEntry
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
                                ,$boolLabelAbove=null
                                ,$boolNoWrapLabel = null)
    {
        $formDinTextField = new TFormDinTextField($id
                                                 ,$strLabel
                                                 ,$intMaxLength
                                                 ,$boolRequired
                                                 ,$strValue);
        $formDinTextField->setExampleText($strExampleText);
        $objField = $formDinTextField->getAdiantiObj();
        $label = $this->getLabelField($strLabel,$boolRequired);
        $this->addFields([$label], [$objField]);
        return $objField;
    }

    /**
     * Cria um RadioGroup com efeito visual de Switch dp BootStrap
     * 
     * FormDin 5 - Alguns parametros foram DESATIVADO
     * por não funcionar no Adianti 7.1 e foram mantidos
     * para diminuir o impacto sobre a migração
     * 
     * @param string $id            - 1: ID do campo
     * @param string $strLabel      - 2: Label do campo
     * @param boolean $boolRequired - 3: Obrigatorio
     * @param array $itens          - 4: Informe um array do tipo "chave=>valor", com maximo de 2 elementos
     * @return TRadioGroup
     */
    public function addSwitchField(string $id
                                  ,string $strLabel
                                  ,$boolRequired = false
                                  ,array $itens= null)
    {
        $formDinSwitch = new TFormDinSwitch($id,$strLabel,$boolRequired,$itens);
        $objField = $formDinSwitch->getAdiantiObj();
        $label = $this->getLabelField($strLabel,$boolRequired);
        $this->addFields([$label], [$objField]);
        return $objField;
    }
 
    /**
     * Adicionar campo entrada de dados texto com mascara
     * 
     * FormDin 5 - Alguns parametros foram DESATIVADO
     * por não funcionar no Adianti 7.1 e foram mantidos
     * para diminuir o impacto sobre a migração
     * 
     * S - Represents an alpha character (A-Z,a-z)
     * 9 - Represents a numeric character (0-9)
     * A - Represents an alphanumeric character (A-Z,a-z,0-9)
     *
     * @param string $id              - 1: id do campo
     * @param string $strLabel        - 2: Rotulo do campo que irá aparece na tela
     * @param boolean $boolRequired   - 3: Obrigatorio
     * @param string $strMask         - 4: A mascara
     * @param boolean $boolNewLine    - 5: DESATIVADO Nova linha
     * @param string $strValue        - 6: texto preenchido
     * @param boolean $boolLabelAbove - 7: DESATIVADO - Label sobre
     * @param boolean $boolNoWrapLabel- 8: DESATIVADO
     * @param string $strExampleText  - 9: PlaceHolder é um Texto de exemplo
     * @return void
     */
    public function addMaskField( $id, $strLabel=null, $boolRequired=false, $strMask=null, $boolNewLine=null, $strValue=null, $boolLabelAbove=null, $boolNoWrapLabel=null, $strExampleText=null )
    {
        $formDinSwitch = new TFormDinMaskField($id,$strLabel,$boolRequired,$itens);
        $objField = $formDinSwitch->getAdiantiObj();
        $label = $this->getLabelField($strLabel,$boolRequired);
        $this->addFields([$label], [$objField]);
        return $objField;
    }    

    /**
    * Adicionar campo tipo combobox ou menu select
    *
    * FormDin 5 - Alguns parametros foram DESATIVADO
    * por não funcionar no Adianti 7.1 e foram mantidos
    * para diminuir o impacto sobre a migração
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
    * @param boolean $boolNewLine    - 5: DESATIVADO Default TRUE = cria nova linha , FALSE = fica depois do campo anterior
    * @param boolean $boolLabelAbove - 6: DESATIVADO Default FALSE = Label mesma linha, TRUE = Label acima
    * @param mixed   $mixValue       - 7: DESATIVADO Valor DEFAULT, informe o ID do array
    * @param boolean $boolMultiSelect- 8: DESATIVADO Default FALSE = SingleSelect, TRUE = MultiSelect
    * @param integer $intSize             - 9: DESATIVADO Default 1. Num itens que irão aparecer. 
    * @param integer $intWidth           - 10: DESATIVADO Largura em Pixels
    * @param string  $strFirstOptionText - 11: DESATIVADO First Key in Display
    * @param string  $strFirstOptionValue- 12: DESATIVADO Frist Valeu in Display, use value NULL for required. Para o valor DEFAULT informe o ID do $mixOptions e $strFirstOptionText = '' e não pode ser null
    * @param string  $strKeyColumn       - 13: DESATIVADO
    * @param string  $strDisplayColumn   - 14: DESATIVADO
    * @param string  $boolNoWrapLabel    - 15: DESATIVADO
    * @param string  $strDataColumns     - 16: DESATIVADO
    * @return TCombo
    */     
    public function addSelectField(string $id
                                  ,string $strLabel
                                  ,$boolRequired = false
                                  ,array $mixOptions)
    {
        $formDinSelectField = new TFormDinSelectField($id,$strLabel,$boolRequired,$mixOptions);
        $objField = $formDinSelectField->getAdiantiObj();
        $label = $this->getLabelField($strLabel,$boolRequired);
        $this->addFields([$label], [$objField]);
        return $objField;
    }

    //----------------------------------------------------------------
    //----------------------------------------------------------------
    //----------------------------------------------------------------
    //----------------------------------------------------------------s    

    /**
     * @deprecated mantido apenas para diminir o impacto na migração do FormDin 4 para FormDin 5 sobre Adianti 7.1
     * @return void
     */
    public function setShowCloseButton(){        
    }

    /**
     * @deprecated mantido apenas para diminir o impacto na migração do FormDin 4 para FormDin 5 sobre Adianti 7.1
     * @return void
     */
    public function setFlat(){        
    }

    /**
     * @deprecated mantido apenas para diminir o impacto na migração do FormDin 4 para FormDin 5 sobre Adianti 7.1
     * @return void
     */
    public function setMaximize(){        
    }

    /**
     * @deprecated mantido apenas para diminir o impacto na migração do FormDin 4 para FormDin 5 sobre Adianti 7.1
     * @return void
     */
    public function setHelpOnLine(){        
    }
}