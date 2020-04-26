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
  */
class TFormDinGenericField
{
    protected $adiantiObj;
    protected $label;
    
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
        $this->setLabel($label);
        $this->setAdiantiObj($adiantiObj);
        $this->setId($id);
        $this->setValue($value);
        $this->setRequired($boolRequired);
        $this->setExampleText($placeholder);
        return $this->getAdiantiObj();
    }

    public function setAdiantiObj($adiantiObj){
        return $this->adiantiObj=$adiantiObj;
    }
    public function getAdiantiObj(){
        return $this->adiantiObj;
    }

    public function setLabel($label){
        $this->label = $label;
    }
    public function getLabel(){
        return $this->label;
    }

    public function setId($id){
        $this->getAdiantiObj()->setId($id);
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

    public function setExampleText($placeholder){
        if(!empty($placeholder)){
            $this->getAdiantiObj()->placeholder = $placeholder;
        }
    }
}