<?php
/*
 * ----------------------------------------------------------------------------
 * Formdin 5 Framework
 * SourceCode https://github.com/bjverde/formDin5
 * @author Reinaldo A. Barrêto Junior
 * 
 * É uma reconstrução do FormDin 4 Sobre o Adianti 7
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

class TFormDinNumericField
{
    const COMMA = ',';
    const DOT = '.';

    protected $adiantiObj;
    protected $label;
    protected $decimalsSeparator;
    protected $thousandSeparator;

    /**
     * Campo de entrada de dados texto livre
     * Reconstruido FormDin 4 Sobre o Adianti 7
     *
     * @param string $id                 - 1: ID do campo
     * @param string $strLabel           - 2: Label do campo, que irá aparecer na tela do usuario
     * @param integer $intMaxLength      - 3: Quantidade maxima de digitos.
     * @param boolean $boolRequired      - 4: Obrigatorio
     * @param integer $decimalPlaces     - 5: Quantidade de casas decimais.
     * @param boolean $boolNewLine       - 6: Campo em nova linha. Default = true = inicia em nova linha, false = continua na linha anterior 
     * @param string $strValue           - 7: valor inicial do campo
     * @param string $strMinValue        - 8: valor minimo permitido. Null = não tem limite.
     * @param string $strMaxValue        - 9: valor maxima permitido. Null = não tem limite.
     * @param boolean $boolFormatInteger -10: Inteiros com ou sem ponto de separação
     * @param string $strDirection
     * @param boolean $boolAllowZero
     * @param boolean $boolAllowNull
     * @param boolean $boolLabelAbove
     * @param boolean $boolNoWrapLabel
     * @param string $strHint
     * @return TNumber
     */ 
    public function __construct(string $id
                               ,string $strLabel
                               ,int $intMaxLength = null
                               ,$boolRequired = false
                               ,int $decimalPlaces=null
                               ,$boolNewLine=null
                               ,$strValue=null
                               ,$strMinValue=null
                               ,$strMaxValue=null
                               ,$boolFormatInteger=null
                               ,$strDirection=null
                               ,$boolAllowZero=null
                               ,$boolAllowNull=null
                               ,$boolLabelAbove=null
                               ,$boolNoWrapLabel=null
                               ,$strHint=null
                               ,string $strExampleText =null)
    {
        $decimalsSeparator = $this->getDecimalsSeparator();
        $thousandSeparator = $this->getThousandSeparator();
        $this->setLabel($strLabel);
        $this->adiantiObj = new TNumeric($id, $decimalPlaces, $decimalsSeparator, $thousandSeparator, $replaceOnPost = true);
        $this->adiantiObj->setId($id);
        $this->setRequired($boolRequired);
        if(!empty($strValue)){
            $this->adiantiObj->setValue($strValue);
        }
        $this->setMaxLength($intMaxLength);
        $this->setMinValue($strMinValue);
        $this->setMaxValue($strMaxValue);
        $this->setExampleText($strExampleText);
        return $this->getAdiantiObj();
    }

    public function getAdiantiObj(){
        return $this->adiantiObj;
    }

    public function getLabel(){
        return $this->label;
    }
    public function setLabel($label){
        $this->label = $label;
    }

    public function getDecimalsSeparator(){
        $separator = null;
        if(empty($this->decimalsSeparator)){
            $separator = self::COMMA;
        }else{
            $separator = $this->decimalsSeparator;
        }
        return $separator;
    }

    public function setDecimalsSeparator($decimalsSeparator){
        $this->decimalsSeparator = $decimalsSeparator;
    }

    public function getThousandSeparator(){
        $separator = null;
        if(empty($this->thousandSeparator)){
            $separator = self::DOT;
        }else{
            $separator = $this->thousandSeparator;
        }
        return $separator;
    }

    public function setThousandSeparator($thousandSeparator){
        $this->thousandSeparator = $thousandSeparator;
    }

    public function setRequired($boolRequired){
        if($boolRequired){
            $strLabel = $this->getLabel();
            $this->adiantiObj->addValidation($strLabel, new TRequiredValidator);
        }
    }

    public function setExampleText($placeholder)
    {
        if(!empty($placeholder)){
            $this->adiantiObj->placeholder = $placeholder;
        }
    }

    public function setMaxLength($intMaxLength)
    {
        if($intMaxLength>=1){
            $strLabel = $this->getLabel();
            $this->adiantiObj->addValidation($strLabel, new TMaxLengthValidator, array($intMaxLength));
        }
    }
    public function setMinValue($strMinValue)
    {
        if(is_int($strMinValue)){
            $strLabel = $this->getLabel();
            $this->adiantiObj->addValidation($strLabel, new TMinValueValidator, array($strMinValue));
        }
    }

    public function setMaxValue($strMaxValue)
    {
        if(is_int($strMaxValue)){
            $strLabel = $this->getLabel();
            $this->adiantiObj->addValidation($strLabel, new TMaxValueValidator, array($strMaxValue));
        }
    }
}