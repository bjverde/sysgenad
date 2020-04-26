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

class TFormDinMessage {

    const CSS_FILE_FORM_DEFAULT_FAIL = 'Arquivo de CSS para o Padrão dos Forms não existe ou não está no formato CSS';
    const MENU_FILE_FAIL = 'Arquivo do Menu não existe';

    const FORM_MIN_VERSION_INVALID_FORMAT = 'O formato da versão não é válido, informe no formato X.Y.Z';
    const FORM_MIN_VERSION_BLANK = 'Informe a versão minima do formDin';
    const FORM_MIN_VERSION_NOT = ' Para esse sistema funcionar a versão mínima necessária do formDin é: ';
    const FORM_MIN_YOU_VERSION = 'Sua versão do FormDin é : ';
    
    const ARRAY_EXPECTED = 'O atribruto deveria ser um array';
    const ARRAY_KEY_NOT_EXIST = 'Não existe a chave procurada no array FormDin';
    const ARRAY_ATTRIBUTE_NOT_EXIST = 'Não existe a atributo procurada no array FormDin';
    
    const DONOT_QUOTATION = 'Não use aspas simples ou duplas na pesquisa !';
    
    const ERROR_FIELD_ID_CANNOT_EMPTY = 'O id do campo não pode ficar em branco';

    const ERROR_HTML_COLOR_HEXA = 'Informe uma cor HTML no formato hexadecimal. Exemplo #efefef !';

    const ERROR_EMPTY_INPUT = 'O Parametro não pode ficar em branco';
    const ERROR_TYPE_NOT_INT = 'Tipo não númerico! ';
    const ERROR_TYPE_NOT_ARRAY = 'Tipo não é um array! ';
    const ERROR_TYPE_NOT_SET = 'A variable has not been defined! ';

    const ERROR_FD5_PARAM_MIGRA  = 'Falha na migração do FormDin 4 para 5.';
    const ERROR_FD5_OBJ_ADI  = 'Erro objeto Adianti Fieald não pode ficar em branco.';
    
    public function __construct() {
    }
}
?>