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

class TFormDinGridAction
{
    const TYPE_FORMDIN = 'TYPE_FORMDIN';
    const TYPE_PHP     = 'TYPE_PHP';
    const TYPE_ADIANTI = 'TYPE_ADIANTI';

    protected $adiantiObj;
    protected $actionLabel;
    protected $actionName;
    protected $action;
    protected $image;
    
    /**
     * Acões do botão da Grid
     *
     * @param object $objForm     - 1: FORMDIN5 Objeto do Adianti da classe do Form, é só informar $this
     * @param string $actionLabel - 2: Text do Label que aparece para o usuário. 
     * @param string $actionName  - 3: Text nome da ação deve ter um metodo com o mesmo nome. 
     * @param array $parameters   - 4: parametro do mixupdate fileds
     * @param array $image        - 5: imagem que irá aparecer
     */
    public function __construct($objForm
                               ,$actionLabel
                               ,$actionName
                               ,$parameters = null
                               ,$image = null
                               )
    {
        $arrayAction = [$objForm, $actionName];
        $adiantiObj = new TDataGridAction($arrayAction, $parameters);
        $this->setAdiantiObj($adiantiObj);
        $this->setActionLabel($actionLabel);
        $this->setActionName($actionName);
        $this->setImage($image);
        return $this->getAdiantiObj();
    }
    //-------------------------------------------------------------------------
    public function setAdiantiObj($adiantiObj){
        if( empty($adiantiObj) ){
            throw new InvalidArgumentException(TFormDinMessage::ERROR_FD5_OBJ_ADI);
        }        
        return $this->adiantiObj=$adiantiObj;
    }
    public function getAdiantiObj(){
        return $this->adiantiObj;
    }
    //-------------------------------------------------------------------------
    public function getActionLabel(){
        return $this->actionLabel;
    }
    public function setActionLabel($actionLabel){
        $this->actionLabel = $actionLabel;
    }
    //-------------------------------------------------------------------------
    public function getActionName(){
        return $this->actionName;
    }
    public function setActionName($actionName){
        $this->actionName = $actionName;
    }    
    //-------------------------------------------------------------------------
    public function getImage(){
        return $this->image;
    }
    public function setImage($image){
        $this->image = $image;
    }
    //-------------------------------------------------------------------------
    private static function convertArrayParametersAdianti2FormDin($arrayData){
        $arrayData = self::convertArrayParametersAdianti2PHP($arrayData);
        $arrayData = self::convertArrayParametersPHP2FormDin($arrayData);
        return $arrayData;
    }

    private static function convertArrayParametersAdianti2PHP($arrayData){
        foreach( $arrayData as $k => $v ) {
            $v = mb_substr($v, 0, mb_strlen($v,'utf-8')-1, 'utf-8');
            $v = mb_substr($v, 1, mb_strlen($v,'utf-8'), 'utf-8');
            $arrayData[$k] = $v;
        }
        return $arrayData;
    }    

    /**
     * Converte uma string no formato FormDin
     * <campo_tabela> | <campo_formulario> , <campo_tabela> | <campo_formulario>
     * para um array Adianti ['key0'=>'{value0}','key1' => '{value1}']
     *
     * @param array $arrayData
     * @return array 
     */
    private static function convertArrayParametersFormDin2Adianti($arrayData){
        $result = array();
        $listFields = explode( ',', $arrayData );
        foreach( $listFields as $k => $field ) {
            $field = explode('|',$field);
            $result[ $field[0] ] = '{'.$field[1].'}';
        }
        return $result;
    }

    /**
     * Converte uma string no formato FormDin
     * <campo_tabela> | <campo_formulario> , <campo_tabela> | <campo_formulario>
     * para um array PHP (key0=>value0,key1=>value1)
     *
     * @param array $arrayData
     * @return array 
     */
    private static function convertArrayParametersFormDin2PHP($arrayData){
        $result = array();
        $listFields = explode( ',', $arrayData );
        foreach( $listFields as $k => $field ) {
            $field = explode('|',$field);
            $result[ $field[0] ] = $field[1];
        }
        return $result;
    }

    /**
     * Converte um array comum PHP (key0=>value0,key1=>value1) para um
     * string no formato FormDin Grid Actiion Parameters 
     * <campo_tabela> | <campo_formulario> , <campo_tabela> | <campo_formulario>
     *
     * @param array $arrayData
     * @return array 
     */
    private static function convertArrayParametersPHP2FormDin($arrayData){
        $result = null;
        foreach( $arrayData as $k => $v ) {
            $result = $result.','.$k.'|'.$v;
        }
        $result  = mb_substr($result, 1, mb_strlen($result,'utf-8'), 'utf-8');
        return $result;
    }

    /**
     * Converte um array comum PHP (key0=>value0,key1=>value1) para um
     * array no formato Adianti Grid Actiion Parameters 
     * ['key0'=>'{value0}','key1' => '{value1}']
     *
     * @param array $arrayData
     * @return array 
     */
    private static function convertArrayParametersPHP2Adianit($arrayData){
        foreach( $arrayData as $k => $v ) {
            $arrayData[$k] = '{'.$v.'}';
        }
        return $arrayData;
    }

    /**
     * Detecta o tipo de array do MixUpdateFields e converte para o formato
     * de saída informado
     * @param array $arrayData
     * @param const $outputFormat
     * @return array
     */
    public static function convertArray2OutputFormat($arrayData,$outputFormat = TFormDinGridAction::TYPE_ADIANTI){
        $inputFormt = self::getTypeArrayMixUpdateFields($arrayData);
        if($inputFormt===false){
            throw new InvalidArgumentException(TFormDinMessage::ERROR_OBJ_TYPE_WRONG);
        }
        $result = $arrayData;
        
        if($inputFormt == TFormDinGridAction::TYPE_PHP){
            if($outputFormat == TFormDinGridAction::TYPE_FORMDIN){
                $result = self::convertArrayParametersPHP2FormDin($arrayData);
            }elseif($outputFormat == TFormDinGridAction::TYPE_ADIANTI){
                $result = self::convertArrayParametersPHP2Adianit($arrayData);
            }
        }elseif($inputFormt == TFormDinGridAction::TYPE_FORMDIN){
            if($outputFormat == TFormDinGridAction::TYPE_PHP){
                $result = self::convertArrayParametersFormDin2PHP($arrayData);
            }elseif($outputFormat == TFormDinGridAction::TYPE_ADIANTI){
                $result = self::convertArrayParametersFormDin2Adianti($arrayData);
            }
        }else{
            if($outputFormat == TFormDinGridAction::TYPE_PHP){
                $result = self::convertArrayParametersAdianti2PHP($arrayData);
            }elseif($outputFormat == TFormDinGridAction::TYPE_FORMDIN){
                $result = self::convertArrayParametersAdianti2FormDin($arrayData);
            }
        }
        return $result;
    }

    /**
     * Detecta o tipo de array de para o MixUpdateFields e retorna o tipo
     * conforme as constantes de classe
     * @param array $arrayData
     * @return mix
     */
    public static function getTypeArrayMixUpdateFields($arrayData){
        $result = null;
        if( empty($arrayData) ){
            $result = null;
        }elseif( ArrayHelper::isArrayNotEmpty($arrayData) ){
            $lastElement = end($arrayData);;
            $fristChar = mb_substr($lastElement, 0, 1, 'utf-8');
            $lastChar  = mb_substr($lastElement, -1, 1, 'utf-8');
            if( ($fristChar=='{') && ($lastChar=='}') ){
                $result = self::TYPE_ADIANTI;
            }else{
                $result = self::TYPE_PHP;
            }
        }elseif( is_string($arrayData) && (strpos( $arrayData,'|')!== false) ){
            $result = self::TYPE_FORMDIN;
        }else{
            $result = false;
        }
        return $result;
    }
}