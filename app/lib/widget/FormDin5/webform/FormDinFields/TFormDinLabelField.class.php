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

class TFormDinLabelField
{
    protected $adiantiObj;
    private $class = array();
    

    /**
     * Label do campo de entrada
     * Reconstruido FormDin 4 Sobre o Adianti 7
     *
     * @param string $strLabel      - 1: Label do campo, usado para validações
     * @param boolean $boolRequired - 2: Obrigatorio. DEFAULT = False.
     * @return TEntry
     */
    public function __construct(string $strLabel
                               ,$boolRequired = false)
    {
        $adiantiObj = null;
        if($boolRequired){
            $adiantiObj = new TLabel($strLabel, 'red');
        }else{
            $adiantiObj = new TLabel($strLabel);
        }
        $this->setAdiantiObj($adiantiObj);
        $this->setId($strLabel);
    }

    public function setAdiantiObj($adiantiObj)
    {
        if( empty($adiantiObj) ){
            throw new InvalidArgumentException(TFormDinMessage::ERROR_FD5_OBJ_ADI);
        }
        return $this->adiantiObj=$adiantiObj;
    }
    public function getAdiantiObj(){
        return $this->adiantiObj;
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
    public function setId($id)
	{
        $id = StringHelper::string2SnakeCase($id);
        $id = 'tlabel_'.$id.'_'.mt_rand(1000000000, 1999999999);
        $this->getAdiantiObj()->setId($id);
	}
	public function getId()
	{
		return $this->getAdiantiObj()->getId();
    }
}