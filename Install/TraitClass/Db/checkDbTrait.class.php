<?php

namespace TraitClass\Db;

/**
 * 数据库检测链接
 */
trait checkDbTrait
{

    protected $errorDb;
    /**
     * @var \PDO
     */
    private $pdoObj;

    private $returnArray = [];




    /**
     * 数据库 检测
     */
    protected function checkDb( array $param )
    {
        //判断是否链接成功
        $pdo = $this->getPdoObj( $param );
        if( $pdo === false ){
            return false;
        }
        //判断数据库名是否存在
        $this->checkDbName( $param[ 'dbname' ] );
        $result = $this->pdoObj->query( "SELECT @@global.sql_mode" ); // 检测 sql_mode //模式
        $result = $result->fetchAll( \PDO::FETCH_COLUMN );
        if( strpos( $result[ 0 ],'ONLY_FULL_GROUP_BY' ) !== false ){
            $this->setReturnArray( [ 'status' => 0,'msg' => '请在mysql配置文件修改ql-mode去掉ONLY_FULL_GROUP_BY' ] );
            return false;
        }
        return true;
    }

    private function checkDbName( $dbName )
    {
        if( empty( $dbName ) ){
            $this->setReturnArray( [ 'status' => 0,'msg' => '请输入需要创建的数据库名' ] );
            return false;
        }
        $dbName   = strtolower( trim( $dbName ) );
        $dbName2 = $this->pdoObj->query( 'SELECT information_schema.SCHEMATA.SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME="'.$dbName.'"' );
        $dbName2 = $dbName2 ->fetchAll(\PDO::FETCH_COLUMN);
        if( $dbName2[0] == $dbName){
            $this->setReturnArray( [ 'status' => 2,'msg' => '数据库名已存在,坚持使用将会覆盖当前数据库内容' ] );
        }
    }

    /**
     * 获取pdo 对象
     */
    protected function getPdoObj( array $param)
    {
        if( empty( $param ) ){
            $this->setReturnArray( [ 'status' => 0,'msg' => '用户名或密码不正确' ] );
            return false;
        }

        $param[ 'dbport' ] = $param[ 'dbport' ] ? $param[ 'dbport' ] : '3306';
        $dbHost            = $param[ 'dbhost' ];

        try{
            $pdo = new \PDO( 'mysql:host=' . $dbHost . ':' . $param[ 'dbport' ] . ';',$param[ 'dbuser' ],$param[ 'dbpw' ] );
        }catch( \Exception $e ){

            throw  $e;
        }

        $pdo->query( 'set character = utf8' ); // ,character_set_client=binary,sql_mode='';
        $this->pdoObj = $pdo;
        return true;
    }

    /**
     * sql文件处理
     */
    public static function sqlSplit( $sql,$tablepre )
    {
        if( $tablepre != "db_" )
            $sql = str_replace( "db_",$tablepre,$sql );

        $sql = preg_replace( "/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/","ENGINE=\\1 DEFAULT CHARSET=utf8",$sql );

        $sql          = str_replace( "\r","\n",$sql );
        $ret          = array();
        $num          = 0;
        $queriesarray = explode( ";\n",trim( $sql ) );
        unset( $sql );
        foreach( $queriesarray as $query ){
            $ret[ $num ] = '';
            $queries     = explode( "\n",trim( $query ) );
            $queries     = array_filter( $queries );
            foreach( $queries as $query ){
                $str1 = substr( $query,0,1 );
                if( $str1 != '#' && $str1 != '-' )
                    $ret[ $num ] .= $query;
            }
            $num++;
        }
        return $ret;
    }

    private function msg( $dbName )
    {
        if( ! $status = $this->pdoObj->query( 'CREATE DATABASE `' . $dbName . '` DEFAULT CHARACTER SET utf8;' ) ){
            $json[ 'msg' ] = '没权限创建数据库.'.$dbName;
            echo json_encode( $json );
            exit();
        }
    }

    /**
     * 执行SQL
     *
     * @param array $sqlFormat
     * @param int $n
     */
    private function execSql( array $sqlFormat,$n )
    {
        // 创建写入sql数据库文件到库中 结束
        $pdo = $this->pdoObj;
        /**
         * 执行SQL语句
         */
        $pdo->query( 'SET NAMES utf8' );

        $pdo->query( 'SET FOREIGN_KEY_CHECKS=0;' );
        $counts = count( $sqlFormat );
        for( $i = $n; $i < $counts; $i++ ){
            $sql = trim( $sqlFormat[ $i ] );
            if( strstr( $sql,'CREATE TABLE' ) ){
                preg_match( '/CREATE TABLE `([^ ]*)`/',$sql,$matches );
                $pdo->exec( "DROP TABLE IF EXISTS `$matches[1]" );
                $ret = $pdo->exec( $sql );
                if( $ret !== false ){
                    $message = '<li><span class="correct_span">&radic;</span>创建数据表' . $matches[ 1 ] . '，完成!<span style="float: right;">' . date( 'Y-m-d H:i:s' ) . '</span></li> ';
                }else{
                    $message = '<li><span class="correct_span error_span">&radic;</span>创建数据表' . $matches[ 1 ] . '，失败!<span style="float: right;">' . date( 'Y-m-d H:i:s' ) . '</span></li>';
                }
                $i++;
                $json = array(
                    'n'     => $i,
                    'msg'   => $message,
                    'error' => $pdo->errorCode(),
                    'ret'   => $ret
                );
                echo json_encode( $json );
                exit();
            }else{
                $ret     = $pdo->exec( $sql );
                $message = '';
                $i++;
                $json = array(
                    'n'     => $i,
                    'msg'   => $message,
                    'error' => $pdo->errorInfo(),
                    'ret'   => $ret
                );
                echo json_encode( $json );
                exit();
            }
        }

        if( $i == 999999 )
            exit();
    }

    public function insertAdmin( $dbPrefix )
    {
        $pdo = $this->pdoObj;

        $username = trim( $_POST[ 'manager' ] );
        $password = trim( $_POST[ 'manager_pwd' ] );
        $email    = trim( $_POST[ 'manager_email' ] );
        // 插入管理员表字段tp_admin表
        $time     = time();
        $ip       = get_client_ip();
        $ip       = empty( $ip ) ? "0.0.0.0" : $ip;
        $password = md5( trim( $_POST[ 'manager_pwd' ] ) );
        $pdo->query( "delete from `{$dbPrefix}admin` where account = 'admin'" );

        $sql = " insert  into `{$dbPrefix}admin` VALUES  ('1', '$username', '$password', unix_timestamp(now()), 0, '1', null, unix_timestamp(now()))";
        $pdo->exec( $sql );

        $message = '成功添加管理员<br />成功写入配置文件<br>安装完成．';
        $json    = array(
            'n'   => 999999,
            'msg' => $message,
        );
        echo json_encode( $json );
    }

    /**
     * @return array
     */
    public function getReturnArray()
    {
        return $this->returnArray;
    }

    /**
     * @param array $returnArray
     */
    public function setReturnArray( $returnArray )
    {
        $this->returnArray = $returnArray;
    }


}