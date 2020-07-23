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
 * Classe para criação de Botões 
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
class TFormDinButton {

    protected $adiantiObj;
    protected $objForm;
    protected $objAction;
    protected $label;

    /**
    * Adicionar botão no layout
    *
    * ------------------------------------------------------------------------
    * Esse é o FormDin 5, que é uma reconstrução do FormDin 4 Sobre o Adianti 7.X
    * os parâmetros do metodos foram marcados veja documentação da classe para
    * saber o que cada marca singinifica.
    * ------------------------------------------------------------------------
    *
    * Para que o botão fique alinhado na frente de um campo com labelAbove=true, basta
    * definir o parametro boolLabelAbove do botão para true tambem.
    *
    * @param object  $objForm           - 1 : FORMDIN5 Objeto do Form, é só informar $this
    * @param mixed   $mixValue          - 2 : Label do Botão ou array('Gravar', 'Limpar') com nomes
    * @param string  $strAction         - 3 : NOT_IMPLEMENTED Nome da ação, ignorando $strName $strOnClick. Se ficar null será utilizado o valor de mixValue
    * @param string  $strName           - 4 : Nome da ação com submit
    * @param string  $strOnClick        - 5 : NOT_IMPLEMENTED Nome da função javascript
    * @param string  $strConfirmMessage - 6 : NOT_IMPLEMENTED Mensagem de confirmação, para utilizar o confirme sem utilizar javaScript explicito.
    * @param boolean $boolNewLine       - 7 : Em nova linha. DEFAULT = true
    * @param boolean $boolFooter        - 8 : Mostrar o botão no rodapé do form. DEFAULT = true
    * @param string  $strImage          - 9 : Imagem no botão. Evite usar no lugar procure usar a propriedade setClass. Busca pasta imagens do base ou no caminho informado
    * @param string  $strImageDisabled  -10 : NOT_IMPLEMENTED Imagem no desativado. Evite usar no lugar procure usar a propriedade setClass. Busca pasta imagens do base ou no caminho informado
    * @param string  $strHint           -11 : Texto hint para explicar
    * @param string  $strVerticalAlign  -12 : NOT_IMPLEMENTED
    * @param boolean $boolLabelAbove    -13 : NOT_IMPLEMENTED Position text label. DEFAULT is false. NULL = false. 
    * @param string  $strLabel          -14 : NOT_IMPLEMENTED Text label 
    * @param string  $strHorizontalAlign-15 : NOT_IMPLEMENTED Text Horizontal align. DEFAULT = center. Valeus center, left, right
    * @return TButton|string|array
    */
    public function __construct($objForm
                                , $label
                                , $strAction=null
                                , $strName=null
                                , $strOnClick=null
                                , $strConfirmMessage=null
                                , $boolNewLine=null
                                , $boolFooter=null
                                , $strImage=null
                                , $strImageDisabled=null
                                , $strHint=null
                                , $strVerticalAlign=null
                                , $boolLabelAbove=null
                                , $strLabel=null
                                , $strHorizontalAlign=null)
    {
        $adiantiObj = new TButton('btn'.$strName);
        $this->setObjForm($objForm);
        $this->setAdiantiObj($adiantiObj);
        $this->setLabel($label);
        $this->setAction($strName);
        $this->setImage($strImage);
        return $this->getAdiantiObj();
    }

    public function setObjForm($objForm)
    {
        if( empty($objForm) ){
            throw new InvalidArgumentException(TFormDinMessage::ERROR_FD5_OBJ_ADI);
        }
        if( !is_object($objForm) ){
            throw new InvalidArgumentException(TFormDinMessage::ERROR_FD5_OBJ_ADI);
        }        
        return $this->objForm=$objForm;
    }
    public function getObjForm(){
        return $this->objForm;
    }

    public function setLabel($label)
    {
        if( is_array($label) ){
            $msg = 'O parametro $mixValue não recebe mais um array! Faça uma chamada por Action';
            ValidateHelper::migrarMensage($msg
                                         ,ValidateHelper::ERROR
                                         ,ValidateHelper::MSG_CHANGE
                                         ,__CLASS__,__METHOD__,__LINE__);
        }else{
            $this->label=$label;
            $this->getAdiantiObj()->setLabel($label);
        }
    }
    public function getLabel(){
        return $this->label;
    }

    public function setAdiantiObj($adiantiObj)
    {
        if( empty($adiantiObj) ){
            throw new InvalidArgumentException(TFormDinMessage::ERROR_FD5_OBJ_ADI);
        }
        if( !is_object($adiantiObj) ){
            $msg = 'o metodo addButton MUDOU! o primeiro parametro agora recebe $this! o Restando está igual ;-)';
            ValidateHelper::migrarMensage($msg
                                         ,ValidateHelper::ERROR
                                         ,ValidateHelper::MSG_CHANGE
                                         ,__CLASS__,__METHOD__,__LINE__);
        }
        return $this->adiantiObj=$adiantiObj;
    }
    public function getAdiantiObj(){
        return $this->adiantiObj;
    }

    public function setAction($strName)
    {
        if( empty($strName) ){
            throw new InvalidArgumentException(TFormDinMessage::ERROR_EMPTY_INPUT.': strName');
        }
        $objForm = $this->getObjForm();
        $action = new TAction(array($objForm, $strName));
        $label = $this->getLabel();
        $this->getAdiantiObj()->setAction($action,$label);
    }

    public function setImage($strImage)
    {
        if( !empty($strImage) ){
            $this->getAdiantiObj()->setImage($strImage);
        }
    }

}
?>