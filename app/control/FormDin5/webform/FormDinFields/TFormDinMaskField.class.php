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


/**
 * Classe para criação campo com mascará
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
class TFormDinMaskField
{
    protected $adiantiObj;
    protected $label;
    
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
     * @param boolean $boolLabelAbove - 7: NOT_IMPLEMENTED - Label sobre
     * @param boolean $boolNoWrapLabel- 8: NOT_IMPLEMENTED
     * @param string $strExampleText  - 9: PlaceHolder é um Texto de exemplo
     * @return void
     */
    public function __construct( $id
                               , $strLabel=null
                               , $boolRequired=false
                               , $strMask=null
                               , $boolNewLine=null
                               , $strValue=null
                               , $boolLabelAbove=null
                               , $boolNoWrapLabel=null
                               , $strExampleText=null )
    {
        $this->adiantiObj = new TEntry($id);
        $this->adiantiObj->setId($id);
        $this->adiantiObj->addValidation($strLabel, new TCNPJValidator);
        $this->adiantiObj->setMask('99.999.999/9999-99', $boolSendMask);
        if($boolRequired){
            $strLabel = empty($strLabel)?$id:$strLabel;
            $this->adiantiObj->addValidation($strLabel, new TRequiredValidator);
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