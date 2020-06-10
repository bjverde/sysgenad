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
 * Classe que faz varias transformações de data e hora
 *
 * @author reinaldo.junior
 */
class TPdoAdiantiConnection
{

    private $database = null;
    private $fech = null;

    public function __construct($database,$fech = null)
    {
        $this->setDatabase($database);
        $this->setFech($fech);
    }

    public function setDatabase($database)
    {
        if( empty($database) ){
            throw new InvalidArgumentException('Database Not Object .class:');
        }
        $this->database = $database;
    }
    public function getDatabase()
    {
        return $this->database;
    }

    public function setFech($fech)
    {
        if(empty($fech)){
            $fech = PDO::FETCH_ASSOC;
        }
        $this->fech = $fech;
    }
    public function getFech()
    {
        return $this->fech;
    }

    public function executeSql($sql, $values = null)
    {
        try {
            $database = $this->getDatabase();
            $fech     = $this->getFech();
            
            TTransaction::open($database); // abre uma transação
            $conn = TTransaction::get();   // obtém a conexão  
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, $fech);
            $stmt = $conn->query($sql);    // realiza a consulta
            $result = $stmt->fetchall();
            TTransaction::close();         // fecha a transação.
            return $result;
        }
        catch (Exception $e) {
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public static function getArrayKeyValue($colunaChave,$colunaValor,$list)
    {
        $result = array();
        foreach ($list as $row) {
            $result[$row[$colunaChave]]=$row[$colunaValor];
        }
        return $result;
    }

    public function getArrayKeyValueBySql($colunaChave,$colunaValor,$sql, $values = null)
    {
        $resultList = $this->executeSql($sql, $values = null);
        $result = self::getArrayKeyValue($colunaChave,$colunaValor,$resultList);
        return $result;
    }

    public function selectByTCriteria(TCriteria $criteria, $repositoryName)
    {
        try {
            $database = $this->getDatabase();
            TTransaction::open($database); // abre uma transação
            $repository = new TRepository($repositoryName);
            $collections = $repository->load($criteria);
            TTransaction::close();         // fecha a transação.
            return $collections;
        }
        catch (Exception $e) {
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function selectCountByTCriteria(TCriteria $criteria, $repositoryName)
    {
        try {
            $database = $this->getDatabase();
            TTransaction::open($database); // abre uma transação
            $repository = new TRepository($repositoryName);
            $count = $repository->count($criteria);
            TTransaction::close();         // fecha a transação.
            return $count;
        }
        catch (Exception $e) {
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}
