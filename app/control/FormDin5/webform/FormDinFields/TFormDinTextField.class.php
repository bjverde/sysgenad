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

class TFormDinTextField
{
    protected $adiantiObj;
    

    /**
     * Campo de entrada de dados texto livre
     * Reconstruido FormDin 4 Sobre o Adianti 7
     *
     * @param string $id            - 1: ID do campo
     * @param string $strLabel      - 2: Label do campo, usado para validações
     * @param integer $intMaxLength - 3: Tamanho máximo de caracteres
     * @param boolean $boolRequired - 4: Obrigatorio. DEFAULT = False.
     * @param string $strValue      - 5: Texto preenchido ou valor default
     * @param string $strExampleText- 6: Texto de exemplo ou placeholder 
     * @return TEntry
     */
    public function __construct(string $id
                               ,string $strLabel
                               ,int $intMaxLength = null
                               ,$boolRequired = false
                               ,string $strValue=null
                               ,string $strExampleText =null)
    {
        $this->adiantiObj = new TEntry($id);
        $this->adiantiObj->setId($id);
        if($intMaxLength>=1){
            $this->adiantiObj->addValidation($strLabel, new TMaxLengthValidator, array($intMaxLength));
        }
        if($boolRequired){
            $strLabel = empty($strLabel)?$id:$strLabel;
            $this->adiantiObj->addValidation($strLabel, new TRequiredValidator);
        }
        if(!empty($strValue)){
            $this->adiantiObj->setValue($strValue);
        }
        $this->setExampleText($strExampleText);
        return $this->getAdiantiObj();
    }

    public function getAdiantiObj(){
        return $this->adiantiObj;
    }

    public function setExampleText($placeholder){
        if(!empty($placeholder)){
            $this->adiantiObj->placeholder = $placeholder;
        }
    }
}