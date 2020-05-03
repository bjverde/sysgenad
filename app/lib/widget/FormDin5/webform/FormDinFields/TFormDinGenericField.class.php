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
  * Classe generica de campos do Adianti
  *
  * Junta parte das classes FormDin TControl e TElement
  */
class TFormDinGenericField
{
    protected $adiantiObj;
    protected $labelTxt;
    protected $labelObj;

    private $tooltip;
    private $readOnly;
    private $class = array();
    
    /**
     *
     * @param object $objAdiantiField - 1: Objeto de campo do Adianti
     * @param string $id              - 2: Id do campos
     * @param string $label           - 3: Label do campo
     * @param boolean $boolRequired   - 4: Obrigatorio ou não. DEFAULT = False.
     * @param string $strValue        - 5: Texto preenchido
     * @param string $placeholder     - 6: PlaceHolder é um Texto de exemplo
     */
    public function __construct($adiantiObj
                               ,string $id
                               ,string $label
                               ,$boolRequired = false
                               ,string $value=null
                               ,string $placeholder =null)
    {
        $this->setAdiantiObj($adiantiObj);
        $this->setLabelTxt($label);
        $this->setLabel($label,$boolRequired);
        $this->setId($id);
        $this->setValue($value);
        $this->setRequired($boolRequired);
        $this->setPlaceHolder($placeholder);
        return $this->getAdiantiObj();
    }

    public function setAdiantiObj($adiantiObj){
        if( empty($adiantiObj) ){
            throw new InvalidArgumentException(TFormDinMessage::ERROR_FD5_OBJ_ADI);
        }        
        return $this->adiantiObj=$adiantiObj;
    }
    public function getAdiantiObj(){
        return $this->adiantiObj;
    }

    protected function setLabelTxt($label){
        $this->labelTxt = $label;
    }
    protected function getLabelTxt(){
        return $this->labelTxt;
    }

    protected function setLabel($label,$boolRequired){
        $formDinLabelField = new TFormDinLabelField($label,$boolRequired);
        $label = $formDinLabelField->getAdiantiObj();
        $this->labelObj = $label;
    }
    public function getLabel(){
        return $this->labelObj;
    }

    public function setId($id){
        $this->getAdiantiObj()->id = $id;
    }

    public function setRequired($boolRequired){
        if($boolRequired){
            $strLabel = $this->getLabel();
            $this->getAdiantiObj()->addValidation($strLabel, new TRequiredValidator);
        }
    }

    public function setValue($value){
        if(!empty($value)){
            $this->getAdiantiObj()->setValue($value);
        }
    }

    public function setPlaceHolder($placeholder){
        if(!empty($placeholder)){
            $this->getAdiantiObj()->placeholder = $placeholder;
        }
    }

    public function getPlaceHolder(){
        return $this->getAdiantiObj()->placeholder;
    }

    //------------------------------------------------------------------------------
	/**
	 * Set um Toolpit em um determinado campo pode ser usado com
	 * @param string $strTitle - Titulo
	 * @param string $strText - Texto que irá aparecer
	 * @param string $strImagem
	 * @return TControl
	 */
	public function setTooltip($strTitle=null,$strText=null,$strImagem=null)
	{
        $this->tooltip = $strText;
		$this->getAdiantiObj()->setTip($strText);
	}
	public function getTooltip()
	{
		return $this->tooltip;
    }
    //------------------------------------------------------------------------------
	public function setExampleText($strNewValue=null)
	{
        $this->tooltip = $strNewValue;
		$this->getAdiantiObj()->setTip($strNewValue);
	}
	public function getExampleText()
	{
		return $this->tooltip;
    }    
	//------------------------------------------------------------------------------    
	public function setReadOnly($boolNewValue=null)
	{
        $this->readOnly = $boolNewValue;
        if($boolNewValue){
            $this->getAdiantiObj()->setEditable(FALSE);
        }else{
            $this->getAdiantiObj()->setEditable(TRUE);
        }
	}
	public function getReadOnly()
	{
		return ( $this->readOnly === true) ? true : false;
    }
	//------------------------------------------------------------------------------    
	public function setClass($className)
	{
        $this->class[]=$className;
        $className = implode(' ', $this->class);
        $this->getAdiantiObj()->setProperty('class',$className);
	}
	public function getClass()
	{
		return $this->getAdiantiObj()->getProperty('class');
    }
	//------------------------------------------------------------------------------     
}