<?php
    /*------------------------------------------------------------------------------------------------------*
     * @copyright   : grandsurgery
     * @description : database process 클래스
     * @author      : 김동영 kdy0602@nate.com
     * @created     : 2012.03
     *------------------------------------------------------------------------------------------------------*/

    settype($Conn, 'object');

    class DBClass
    {
        var $connect_host;
        var $connect_id;
        var $connect_pass;
        var $connect_db;
        var $obj_db_conn;

        /*------------------------------------------------------------------------------------------------------*
         * 데이터베이스 접속, 데이터베이스 접속종료
         *------------------------------------------------------------------------------------------------------*/
        function DBClass()
        {
            $this->connect_host = 'localhost';
            $this->connect_id   = 'eightps8';
            $this->connect_pass = 'dlsdk08580858';
            $this->connect_db   = 'eightps8';

            $this->obj_db_conn = @mysql_connect($this->connect_host, $this->connect_id, $this->connect_pass) or dir(NO_DATABASE);
            @mysql_select_db($this->connect_db, $this->obj_db_conn);
            @mysql_query('set names utf8;', $this->obj_db_conn); // 문자셋이 다른경우 사용
        }

        /*
        function __destruct()
        {
            $this->connect_host = '';
            $this->connect_id   = '';
            $this->connect_pass = '';
            $this->connect_db   = '';
        }
        */

        function DisConnect()
        {
            @mysql_close($this->obj_db_conn);
            $this->obj_db_conn = null;
        }

        /*------------------------------------------------------------------------------------------------------*
         * 트랜잭션
         *------------------------------------------------------------------------------------------------------*/
        function StartTrans($pConnect=null)
        {
            if(!$pConnect) $pConnect = $this->obj_db_conn;
            @mysql_query("set autocommit = 0;", $pConnect);
            //@mysql_query("start transaction;", $pConnect);
            //@mysql_query("begin;", $pConnect);
        }

        function CommitTrans($pConnect=null)
        {
            if(!$pConnect) $pConnect = $this->obj_db_conn;
            @mysql_query("set autocommit = 1;", $pConnect);
            @mysql_query("commit;", $pConnect);
        }

        function RollbackTrans($pConnect=null)
        {
            if(!$pConnect) $pConnect = $this->obj_db_conn;
            @mysql_query("rollback;", $pConnect);
            @mysql_query("set autocommit = 1;", $pConnect);
        }

        /*------------------------------------------------------------------------------------------------------*
         * 페이징
         *------------------------------------------------------------------------------------------------------*/
        function PageListCount($pTableName, $pWhere, $pSearchKey, $pSearchVal, $pConnect=null)
        {
            if($pSearchKey != '' && $pSearchVal != ''){
                if(!$pWhere) $pWhere = $pWhere.' where ';
                else $pWhere = $pWhere.' and ';
                $pWhere = $pWhere.' '.$pSearchKey." like '%".$pSearchVal."%' ";
            }
            $sql = 'select count(*) as counts from '.$pTableName.' '.$pWhere;
            if(!$pConnect) $pConnect = $this->obj_db_conn;
            $sqlQuery = @mysql_query($sql, $pConnect);

            $counts = @mysql_fetch_row($sqlQuery);
            @mysql_free_result($sqlQuery);
            unset($sqlQuery);
            return $counts[0];
        }

        function PageList($pTableName, $pClass, $pWhere, $pSearchKey, $pSearchVal, $pOrderBy, $pSubSql, $pPageNo, $pPageView, $pJoin=null, $pConnect=null)
        {
            if($pSearchKey != '' && $pSearchVal != ''){
                if($pWhere) $pWhere = $pWhere.' and ';
                else $pWhere = ' where ';
                $pWhere = $pWhere." ".$pSearchKey." like '%".$pSearchVal."%' ";
            }
            $sql = 'select * '.$pSubSql.' from '.$pTableName.' '.$pWhere.' '.$pOrderBy.' limit '.$pPageView * ($pPageNo - 1).' , '.$pPageView;
            if(!$pConnect) $pConnect = $this->obj_db_conn;
            $sqlQuery = @mysql_query($sql, $pConnect);

            $returnPageList = array();
            for($i=0; $Result=@mysql_fetch_array($sqlQuery); $i++) {
                $returnPageList[$i] = $pClass->ArrList($Result, $i, $pJoin);
            }
            @mysql_free_result($sqlQuery);
            unset($sqlQuery);
            return $returnPageList;
        }

        function PageJoinList($pTableName, $pColumn, $pClass, $pWhere, $pSearchKey, $pSearchVal, $pOrderBy, $pSubSql, $pPageNo, $pPageView, $pJoin=null, $pConnect=null)
        {
            if($pSearchKey != '' && $pSearchVal != ''){
                if($pWhere) $pWhere = $pWhere.' and ';
                else $pWhere = ' where ';
                $pWhere = $pWhere." ".$pSearchKey." like '%".$pSearchVal."%' ";
            }
            $sql = 'select '.$pColumn.' '.$pSubSql.' from '.$pTableName.' '.$pWhere.' '.$pOrderBy.' limit '.$pPageView * ($pPageNo - 1).' , '.$pPageView;
            if(!$pConnect) $pConnect = $this->obj_db_conn;
            $sqlQuery = @mysql_query($sql, $pConnect);

            $returnPageJoinList = array();
            for($i=0; $Result=@mysql_fetch_array($sqlQuery); $i++) {
                $returnPageJoinList[$i] = $pClass->ArrList($Result, $i, $pJoin); //join 변수
            }
            @mysql_free_result($sqlQuery);
            unset($sqlQuery);
            return $returnPageJoinList;
        }

        /*------------------------------------------------------------------------------------------------------*
         * 데이터 셀렉트
         *------------------------------------------------------------------------------------------------------*/
        function AllList($pTableName, $pClass, $pColumn='*', $pWhere=null, $pSubSql=null, $pJoin=null, $pConnect=null)
        {
            $sql = 'select '.$pColumn.' '.$pSubSql.' from '.$pTableName.' '.$pWhere;
            if(!$pConnect) $pConnect = $this->obj_db_conn;
            $sqlQuery = @mysql_query($sql, $pConnect);

            $returnAllList = array();
            for($i=0; $Result=@mysql_fetch_array($sqlQuery); $i++) {
                $returnAllList[$i] = $pClass->ArrList($Result, $i, $pJoin); //join 변수
            }
            @mysql_free_result($sqlQuery);
            unset($sqlQuery);
            return $returnAllList;
        }

        function SelectColumn($pTableName, $pColumn='count(*)', $pWhere=null, $pJoin=null, $pConnect=null)
        {
            $sql = 'select '.$pColumn.' from '.$pTableName.' '.$pWhere;
            if(!$pConnect) $pConnect = $this->obj_db_conn;
            $sqlQuery = @mysql_query($sql, $pConnect);

            $returnSelectColumn = @mysql_fetch_row($sqlQuery);
            @mysql_free_result($sqlQuery);
            unset($sqlQuery);
            return $returnSelectColumn[0];
        }

        function UnionAllList($pTableName, $pClass, $pColumn='*', $pWhereColumn, $pWhere=null, $pOrderBy=null, $pLastOrderBy=null, $pJoin=null, $pConnect=null)
        {
            $where_seq = explode(',',$pWhere);
            for($i=0, $cnt=count($where_seq); $i < $cnt; $i++) {
                $sql = $sql.' union all (select '.$pColumn.' from '.$pTableName.' where '.$pWhereColumn." = '".$where_seq[$i]."' ".$pOrderBy.")";
            }
            $sql = substr($sql,11)." ".$pLastOrderBy;

            if(!$pConnect) $pConnect = $this->obj_db_conn;
            $sqlQuery = @mysql_query($sql, $pConnect);

            $returnAllList = array();
            for($i=0; $Result=@mysql_fetch_array($sqlQuery); $i++) {
                $returnUnionAllList[$i] = $pClass->ArrList($Result, $i, $pJoin); //join 변수
            }
            @mysql_free_result($sqlQuery);
            unset($sqlQuery);
            return $returnUnionAllList;
        }

        /*------------------------------------------------------------------------------------------------------*
         * 입력, 수정, 삭제
         *------------------------------------------------------------------------------------------------------*/
        function InsertDB($pTableName, $pField, $pArr, $pConnect=null)
        {
            $sql = 'insert into '.$pTableName.' ('.join(',',$pField).") values('".join("','",$pArr)."')";
            if(!$pConnect) $pConnect = $this->obj_db_conn;
            return mysql_query($sql, $pConnect);
        }

        function InsertMultiDB($pTableName, $pField, $pArr, $pConnect=null)
        {
            $sql = 'insert into '.$pTableName.' ('.join(',',$pField).") values";

            for($i=0, $cnt=count($pArr); $i < $cnt; $i++) {
                $multiValue = $multiValue.",('".join("','",$pArr[$i])."')";
            }
            $sql = $sql.substr($multiValue,1);

            if(!$pConnect) $pConnect = $this->obj_db_conn;
            return @mysql_query($sql, $pConnect);
        }

        function insertIdentityDB($pTableName, $pField, $pArr, $pConnect=null)
        {
            $sql = 'insert into '.$pTableName.' ('.join(',',$pField).") values('".join("','",$pArr)."')";
            if(!$pConnect) $pConnect = $this->obj_db_conn;
            @mysql_query($sql, $pConnect);

            $sql = 'select last_insert_id()';
            $sqlQuery = @mysql_query($sql, $pConnect);
            $identitySeq = @mysql_fetch_row($sqlQuery);
            @mysql_free_result($sqlQuery);
            unset($sqlQuery);
            return $identitySeq[0];
        }

        function insertFirstReplyDB($pTableName, $pUpField, $pPrimary, $pField, $pArr, $pConnect=null)
        {
            $sql = 'insert into '.$pTableName.' ('.join(',',$pField).") values('".join("','",$pArr)."')";
            if(!$pConnect) $pConnect = $this->obj_db_conn;
            @mysql_query($sql, $pConnect);

            $sql = 'select last_insert_id()';
            $sqlQuery = @mysql_query($sql, $pConnect);
            $identitySeq = @mysql_fetch_row($sqlQuery);
            @mysql_free_result($sqlQuery);
            unset($sqlQuery);

            $sql = "update ".$pTableName." set ".$pUpField."='".$identitySeq[0]."' where ".$pPrimary." = '".$identitySeq[0]."'";
            return @mysql_query($sql, $pConnect);
        }

        function insertReplyDB($pTableName, $pUpField, $pPrimary, $pField, $pArr, $pConnect=null)
        {
            $sql = 'insert into '.$pTableName.' ('.join(',',$pField).") values('".join("','",$pArr)."')";
            if(!$pConnect) $pConnect = $this->obj_db_conn;
            @mysql_query($sql, $pConnect);

            $sql = 'select last_insert_id()';
            $sqlQuery = @mysql_query($sql, $pConnect);
            $identitySeq = @mysql_fetch_row($sqlQuery);
            @mysql_free_result($sqlQuery);
            unset($sqlQuery);

            $sql = "update ".$pTableName." set ".$pUpField." and ".$pPrimary." <> '".$identitySeq[0]."'";
            return@mysql_query($sql, $pConnect);
        }

        function UpdateDB($pTableName, $pField, $pArr, $pWhere=null, $pConnect=null)
        {
            for($i=0, $cnt=count($pField); $i < $cnt; $i++) {
                $column = $column.','.$pField[$i]."='".$pArr[$i]."'";
            }
            $sql = 'update '.$pTableName.' set '.substr($column,1).' '.$pWhere;

            if(!$pConnect) $pConnect = $this->obj_db_conn;
            return @mysql_query($sql, $pConnect);
        }

        function UpdateMultiDB($pTableName, $pField, $pArr, $pWhereVal, $pWhereField, $pConnect=null)
        {
            $ascii = 65; //알파벳
            for($i=0, $cnt=count($pWhereVal); $i < $cnt; $i++) {
                for($k=0, $cnt_field=count($pField); $k < $cnt_field; $k++) {
                    $column = $column.','.chr($ascii).'.'.$pField[$k]."='".$pArr[$i][$k]."'";
                }
                if($i == 0){
                    $where_str = 'where';
                }else{
                    $where_str = 'and';
                }
                $tables = $tables.','.$pTableName." as ".chr($ascii);
                $wheres = $wheres.' '.$where_str.' '.chr($ascii).'.'.$pWhereField.'='.$pWhereVal[$i];
                $ascii  = $ascii + 1;
            }
            $sql = 'update '.substr($tables,1).' set '.substr($column,1).' '.$wheres;

            if(!$pConnect) $pConnect = $this->obj_db_conn;
            return @mysql_query($sql, $pConnect);
        }

        function UpdateDBQuery($pTableName, $pQuery, $pConnect=null)
        {
            $sql = 'update '.$pTableName.' set '.$pQuery;
            if(!$pConnect) $pConnect = $this->obj_db_conn;
            return @mysql_query($sql, $pConnect);

        }

        function DeleteDB($pTableName, $pWhere=null, $pConnect=null)
        {
            $sql = 'delete from '.$pTableName.' '.$pWhere;
            if(!$pConnect) $pConnect = $this->obj_db_conn;
            return @mysql_query($sql, $pConnect);
        }

    }// end class
?>