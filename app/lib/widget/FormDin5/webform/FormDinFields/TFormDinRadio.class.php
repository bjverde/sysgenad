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
 * Classe para criação campo do tipo Radio
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
class TFormDinRadio extends TFormDinGenericField
{
    protected $adiantiObj;
    
    /**
     * Cria um RadioGroup com efeito visual de Switch
     * Reconstruido FormDin 4 Sobre o Adianti 7
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
     * @return mixed TRadioGroup
     */
    public function __construct($id
                               ,$label=null
                               ,$boolRequired=null
                               ,$arrOptions=null
                               ,$boolNewLine=null
                               ,$boolLabelAbove=null
                               ,$strValue=null
                               ,$intQtdColumns=null
                               ,$intWidth=null
                               ,$intHeight=null
                               ,$intPaddingItems=null
                               ,$boolNoWrapLabel=null
                               ,$boolNowrapText=null
                               ,$useButton = null
                               )
    {
        $adiantiObj = new TRadioGroup($id);
        parent::__construct($adiantiObj,$id,$label,$boolRequired,$strValue,null);
        $this->setUseButton($useButton);        
        $this->addItems($arrOptions);
        $this->setBreakItems($intQtdColumns);
        $this->setUseButton($useButton);
        $this->setLayout('horizontal');
        return $this->getAdiantiObj();
    }

    public function addItems($arrayItens){
        $arrayItens = ArrayHelper::convertString2Array($arrayItens);
        $this->getAdiantiObj()->addItems($arrayItens);
    }    

    public function setUseButton($useButton){
        if( !empty($useButton) ){
            $this->getAdiantiObj()->setUseButton();
        }
    }

    public function setLayout($dir)
    {
        $this->getAdiantiObj()->setLayout($dir);
    }
    public function getLayout()
    {
        return $this->getAdiantiObj()->getLayout();
    }

    public function setBreakItems($breakItems)
    {
        $this->getAdiantiObj()->setBreakItems($breakItems);
    }
    public function getItems()
    {
        return $this->getAdiantiObj()->getItems();
    }

    public function getButtons()
    {
        return $this->getAdiantiObj()->getButtons();
    }

    public function getLabels()
    {
        return $this->getAdiantiObj()->getLabels();
    }
}