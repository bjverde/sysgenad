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

class TFormDinPdoConnection
{
    const DBMS_ACCESS = 'ACCESS';
    const DBMS_FIREBIRD = 'ibase';
    const DBMS_MYSQL    = 'mysql';
    const DBMS_ORACLE   = 'oracle';
    const DBMS_POSTGRES = 'pgsql';
    const DBMS_SQLITE   = 'sqlite';
    const DBMS_SQLSERVER = 'sqlsrv';

    private $database = null;
    private $fech = null;
    private $case = null;


    private $host;
    private $port;
    private $name;
    private $user;
    private $pass;
    private $type;

    public function __construct($database = null,$fech = null,$case = null)
    {
        if(!empty($database)){
            $this->setDatabase($database);
        }
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

    public function setCase($case)
    {
        if(empty($case)){
            $case = PDO::CASE_UPPER;
        }
        $this->case = $case;
    }
    public function getCase()
    {
        return $this->case;
    }

    /**
     * Retorna um array com o tipo de SGBD e descrição
     *
     * @return array
     */
    public static function getListDBMS()
    {
        $list = array();
        //$list[self::DBMS_ACCESS]='Access';
        //$list[self::DBMS_FIREBIRD]='FIREBIRD';
        $list[self::DBMS_MYSQL]='MariaDB ou MySQL';
        $list[self::DBMS_ORACLE]='Oracle';
        $list[self::DBMS_POSTGRES]='PostgreSQL';
        $list[self::DBMS_SQLITE]='SqLite';
        $list[self::DBMS_SQLSERVER]='SQL Server';
        return $list;
    }
    
    public function getHost()
    {
        return $this->host;
    }
    public function setHost($host)
    {
        $this->host = $host;
    }
    
    public function getPort()
    {
        return $this->port;
    }
    public function setPort($port)
    {
        $this->port = $port;
    }
    
    public function getName()
    {
        return $this->name;
    }
    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function getUser()
    {
        return $this->user;
    }
    public function setUser($user)
    {
        $this->user = $user;
    }
    
    public function getPass()
    {
        return $this->pass;
    }
    public function setPass($pass)
    {
        $this->pass = $pass;
    }
    
    public function getType()
    {
        return $this->type;
    }
    public function setType($type)
    {
        $listType = self::getListDBMS();
        $inArray = ArrayHelper::has($type,$listType);
        if (!$inArray) {
            throw new InvalidArgumentException('Type DBMS is not value valid');
        }
        $this->type = $type;
    }
    
    public function getConfigConnect()
    {
        $result = array();
        $databese = $this->getDatabase();
        $type = $this->getType();
        $name = $this->getName();
        $conditionArrayConnectEmpty = empty($type) || empty($name);
        if( empty($databese) && $conditionArrayConnectEmpty ){
            throw new InvalidArgumentException('Fail to configure the database! Please input correct config');
        }
        
        $db =  null;
        if(!$conditionArrayConnectEmpty){
            $db = array();
            $db['host'] = $this->getHost();
            $db['port'] = $this->getPort();
            $db['name'] = $name;
            $db['user'] = $this->getUser();
            $db['pass'] = $this->getPass();
            $db['type'] = $type;
        }
        
        $result['database'] = $databese;
        $result['db'] = $db;
        
        return $result;
    }

    /**
     * Retorna o valor Default da porta do SGBD
     *
     * @return string
     */
    public function getDefaulPort() {
        $result = null;
        switch( $this->getType() ) {
            case self::DBMS_POSTGRES:
                $result = '5432';
            break;
            case self::DBMS_MYSQL:
                $result = '3306';
            break;
            case self::DBMS_SQLSERVER:
                $$result = '1433';
            break;
            case self::DBMS_ORACLE:
                $result = '1521';
            break;
        }
		return $result;
	}

    /**
     * Executa o comando sql recebido retornando o cursor ou verdadeiro o falso
     * se a operação foi bem sucedida.
     *
     * @param string $sql
     * @param array $values
     * @return mixed
     */
    public function executeSql($sql, $values = null)
    {
        try {
            $configConnect = $this->getConfigConnect();
            $database = $configConnect['database'];
            $db = $configConnect['db'];
            $case     = $this->getCase();
            $fech     = $this->getFech();
            
            TTransaction::open($database,$db); // abre uma transação
            $conn = TTransaction::get();   // obtém a conexão  
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_CASE, $case);
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
        $resultList = $this->executeSql($sql, $values);
        $result = self::getArrayKeyValue($colunaChave,$colunaValor,$resultList);
        return $result;
    }

    public function selectByTCriteria(TCriteria $criteria, $repositoryName)
    {
        try {
            $configConnect = $this->getConfigConnect();
            $database = $configConnect['database'];
            $db = $configConnect['db'];
            
            TTransaction::open($database,$db); // abre uma transação
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
            $configConnect = $this->getConfigConnect();
            $database = $configConnect['database'];
            $db = $configConnect['db'];
            
            TTransaction::open($database,$db); // abre uma transação
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
