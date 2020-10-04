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
 * Classe para criação campo do tipo select
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
class TFormDinSelectField  extends TFormDinGenericField
{
    protected $adiantiObj;
    private $multiSelect;
    
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
     * @param boolean $boolMultiSelect- 8: Default FALSE = SingleSelect, TRUE = MultiSelect
     * @param integer $intSize             - 9: NOT_IMPLEMENTED Default 1. Num itens que irão aparecer. 
     * @param integer $intWidth           - 10: NOT_IMPLEMENTED Largura em Pixels
     * @param string  $strFirstOptionText - 11: NOT_IMPLEMENTED First Key in Display
     * @param string  $strFirstOptionValue- 12: Frist Valeu in Display, use value NULL for required. Para o valor DEFAULT informe o ID do $mixOptions e $strFirstOptionText = '' e não pode ser null
     * @param string  $strKeyColumn       - 13: NOT_IMPLEMENTED
     * @param string  $strDisplayColumn   - 14: NOT_IMPLEMENTED
     * @param string  $boolNoWrapLabel    - 15: NOT_IMPLEMENTED
     * @param string  $strDataColumns     - 16: NOT_IMPLEMENTED
     * @param string  $boolNoWrapLabel    - 17: FORMDIN5 - enableSearch  Default FALSE = SingleSelect, TRUE = MultiSelect
     * @return TCombo
     */
    public function __construct(string $id
                                ,string $label
                                ,$boolRequired = false
                                ,array $mixOptions
                                ,$boolNewLine = true
                                ,$boolLabelAbove = false
                                ,$mixValue = null
                                ,$boolMultiSelect = false
                                ,$intSize = null
                                ,$intWidth = null
                                ,$strFirstOptionText = null
                                ,$strFirstOptionValue = null
                                ,$strKeyColumn = null
                                ,$strDisplayColumn = null
                                ,$boolNoWrapLabel = null
                                ,$strDataColumns = null
                                )
    {
        $boolMultiSelect = is_null($boolMultiSelect)?false:$boolMultiSelect;
        $this->setMultiSelect($boolMultiSelect);

        if($this->getMultiSelect()){
            $adiantiObj = new TSelect($id);
            //$adiantiObj->tag->{'size'} = $intSize;
            //$adiantiObj->setProperty('size', $intSize);
        }else{
            $adiantiObj = new TCombo($id);            
        }
        $value = is_null($mixValue)?$strFirstOptionValue:$mixValue;
        parent::__construct($adiantiObj,$id,$label,$boolRequired,$value,null);
        $this->addItems($mixOptions);
        return $this->getAdiantiObj();
    }

    public function addItems($arrayItens){
        $this->getAdiantiObj()->addItems($arrayItens);
    }    

    public function getItems()
    {
        return $this->getAdiantiObj()->getItems();
    }
    //-----------------------------------------------------------
    public function getMultiSelect()
    {
        return $this->multiSelect;
    }
    private function setMultiSelect($boolMultiSelect)
    {
        $this->multiSelect = $boolMultiSelect;
    }

    public function enableSearch()
    {
        if($this->getMultiSelect() == false)
        {
            $this->getAdiantiObj()->enableSearch();
        }
    }
}