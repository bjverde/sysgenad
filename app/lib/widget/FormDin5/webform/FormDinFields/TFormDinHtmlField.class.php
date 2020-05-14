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
class TFormDinHtmlField extends TFormDinGenericField
{    
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
    public function __construct( string $id
                               , $value=null
                               , $strIncludeFile=null
                               , $label=null
                               , $strHeight=null
                               , $strWidth=null
                               , $boolNewLine=null
                               , $boolLabelAbove=null
                               , $boolNoWrapLabel=null )
    {
        $adiantiObj = new TElement('div');
        $adiantiObj->id = $id;
        $adiantiObj->add($value);
        //FormDinHelper::d($label,'$label');
        $label = is_null($label)?'':$label;
        parent::__construct($adiantiObj,$id,$label,null,null,null);
        return $this->getAdiantiObj();
    }

    public function setId($id){
        $this->getAdiantiObj()->id = $id;
    }
}