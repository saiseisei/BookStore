<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Metadata\Metadata;
use Application\Service\DataClass;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BaseTable implements ServiceLocatorAwareInterface {

    protected $idColumn = 'id';
    protected $delColumn = null;
    protected $tableGateway;
    protected $serviceManager;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->service_manager = $serviceLocator;
    }

    public function getServiceLocator()
    {
        return $this->service_manager;
    }

    public function getDataTable($table)
    {
        $tf = $this->getServiceLocator()->get('TableManager');
        return $tf->getDataTable($table);
    }

    // プロパテリ
    public function setIdColumn($idname) {
        $this->idColumn = $idname;
    }

    public function getIdColumn() {
        return $this->idColumn;
    }

    public function setDelColumn($idname) {
        $this->delColumn = $idname;
    }

    public function getDelColumn() {
        return $this->delColumn;
    }

    // テーブル情報を自動取得
    public static function getTableInfo2($sm, $tableName) {
        $adapter = $sm->get('Zend\Db\Adapter\Adapter');
        $metadata = new Metadata($adapter);
        $constraints = $metadata->getConstraints($tableName, DB_SCHEMA);
        $pkColumn = '';
        foreach ($constraints as $c) {
            if ($c->isPrimaryKey()) {
                $columns = $c->getColumns();
                $pkColumn = $columns[0];
                break;
            }
        }

        $info = new DataClass();
        $info->tableName = $tableName;
        $info->pkColumn = $pkColumn;
        $info->columns = $metadata->getColumnNames($tableName, 'nilax_db');
        return $info;
    }

    // 一覧データを取得------------------------
    public function fetchAll($order = null, Array $condition = array()) {
         $rowset = $this->tableGateway->select(function (Select $select) use ($order, $condition) {
            if (!empty($condition)) {
                $select->where($condition);
            }
            if (!is_null($order)) {
                $select->order($order);
            }
            // echo $select->getSqlString();
        });
        return $rowset;
    }

    public function getData($id) {
        $idColumn = $this->idColumn;
        $delColumn = $this->delColumn;
        $rowset = $this->tableGateway->select(function (Select $select) use ($id, $idColumn, $delColumn) {
            if (!is_null($this->delColumn)) {
                $select->where([$delColumn => 0]);
            }
            $select->where([$idColumn => $id]);
        });
        $row = $rowset->current();
        if (!$row) {
            //throw new \Exception("ID = $id のデータは存在しません。");
            return null;
        }
        return $row;
    }

    public function getDataById($id) {
        $rowset = $this->tableGateway->select(array($this->idColumn => $id));
        return $rowset->current();
    }

    public function getSelectOptions($column_name, $order = null) {
        if (is_null($order)) {
            $order = $this->idColumn . ' ASC';
        }
        $result = $this->fetchAll($order);
        $array = array();
        foreach ($result as $row) {
            $id = $this->idColumn;
            $array[$row->$id] = $row->$column_name;
        }

        return $array;
    }

    // データを保存------------------------
    public function saveData($data) {
        $arrData = $this->obj2arr($data);
        $column = $this->idColumn;
        $id = (int) $data->$column;

        if ($id == 0) {
// \Zend\Debug\Debug::dump($arrData);die;
            $this->tableGateway->insert($arrData);
            $newId = $this->tableGateway->getLastInsertValue();
            return $newId;
        } else {
            $data = $this->getData($id);
            if ($data) {
                $this->tableGateway->update($arrData, array($this->idColumn => $id));
                return $id;
            } else {
                throw new \Exception('ID = $id のデータは存在しません。');
            }
        }
    }

    // データを削除------------------------
    public function deleteDataVirtual($id) {
        $this->tableGateway->update(array($this->delColumn => 1), array($this->idColumn => $id));
    }

    public function deleteData($id) {
        $this->tableGateway->delete(array($this->idColumn => $id));
    }

    // データのユニックチェック------------------------
    public function isUniqId($id) {
        $resultSet = $this->tableGateway->select(array($this->idColumn => $id));
        if (count($resultSet)) {
            return false;
        } else {
            return true;
        }
    }

    public function isUniqColumn($column_name, $value, $edit_flg, $id = null) {
        if ($edit_flg) {
            $resultSet1 = $this->tableGateway->select(array($this->idColumn => $id));
            if ($resultSet1->current()->url_key == $value) {
                return true;
            }
        }
        $resultSet2 = $this->tableGateway->select(array($column_name => $value));
        if (count($resultSet2)) {
            return false;
        } else {
            return true;
        }
    }

    // テーブルにデータが存在するかチェック
    public function isTableDataExists($id) {
        $id = (int) $id;
        $resultSet = $this->tableGateway->select(array($this->idColumn => $id));
        if (count($resultSet) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getLastID(Array $condition = array(), $isMaster = false) {
        $where = '';
        if (!$isMaster) {
            $condition['del_flg'] = 0;
            foreach ($condition as $key => $value) {
                $where .= " AND {$key} = {$value} ";
            }
        }
        $sql = "
            SELECT MAX({$this->idColumn}) + 1 as id
            FROM {$this->tableGateway->getTable()}
            WHERE 1 = 1 {$where}
        ";

        $result = $this->tableGateway->getAdapter()->query($sql)->execute()->current();
        return empty($result['id']) ? 1 : $result['id'];
    }

    public function getMaxID($idColumn, Array $condition = array()) {
        $where = '';

        foreach ($condition as $key => $value) {
            $where .= " AND {$key} = {$value} ";
        }

        $sql = "
            SELECT MAX({$idColumn}) + 1 as id
            FROM {$this->tableGateway->getTable()}
            WHERE 1 = 1 {$where}
        ";

        $result = $this->tableGateway->getAdapter()->query($sql)->execute()->current();
        return empty($result['id']) ? 1 : $result['id'];
    }

    // トランザクション
    public function beginTransaction() {
        $this->tableGateway->getAdapter()->getDriver()->getConnection()->beginTransaction();
    }

    public function rollback() {
        $this->tableGateway->getAdapter()->getDriver()->getConnection()->rollback();
    }

    public function commit() {
        $this->tableGateway->getAdapter()->getDriver()->getConnection()->commit();
    }

    // データのユニックチェック------------------------
    // 表示順関連
    public function getLastSort(Array $condition = array(), $sortColumn = 'sort', $isMaster = false) {
        $where = '';
        if (!$isMaster) {
            $condition['del_flg'] = 0;
            foreach ($condition as $key => $value) {
                $where .= " AND {$key} = {$value} ";
            }
        }
        $sql = "
            SELECT MAX({$sortColumn}) + 1 as sort
            FROM {$this->tableGateway->getTable()}
            WHERE 1 = 1 {$where}
        ";

        $result = $this->tableGateway->getAdapter()->query($sql)->execute()->current();
        return empty($result['sort']) ? 1 : $result['sort'];
    }

    public function updateSort($id, $direction, Array $condition = array(), $sortColumn = 'sort', $isMaster = false) {
        $data = $this->getData($id);

        if (!$data) {
            throw new \Exception('ID = $id のデータは存在しません。');
        }
        $where = '';
        if (!$isMaster) {
            $condition['del_flg'] = 0;
            foreach ($condition as $key => $value) {
                $where .= " AND {$key} = {$value} ";
            }
        }
        $sql = "
            SELECT
                {$this->idColumn} as id,
                {$sortColumn} as sort
            FROM {$this->tableGateway->getTable()}
            where 1 AND
                {$sortColumn} = (SELECT %s({$sortColumn}) as sort
                                   FROM {$this->tableGateway->getTable()}
                                   WHERE {$sortColumn} %s {$where})
            {$where}
        ";

        if ($direction === DIRECTION_UP) {
            $sql = sprintf($sql, 'MAX', ' < ' . $data->sort);
        } else {
            $sql = sprintf($sql, 'MIN', ' > ' . $data->sort);
        }

        $result = $this->tableGateway->getAdapter()->query($sql)->execute();
        $row = $result->current();

        if (!$row['id'] || !$row['sort']) {
            return;
        }

        $target = $this->getData($row['id']);

        if (!$target) {
            throw new \Exception('ID = $row[$this->idColumn] のデータは存在しません。');
        }

        $newSort = $target->$sortColumn;
        $target->$sortColumn = $data->sort;
        $this->saveData($target);
        $data->sort = $newSort;
        $this->saveData($data);
    }

    // 表示順関連
    // 表示・非表示切り替え
    public function updateDisplayFlg($id) {
        $data = $this->getData($id);
        if (!$data) {
            throw new \Exception('ID = $row[$this->idColumn] のデータは存在しません。');
        }

        $data->display_flg = $data->display_flg == DISPLAY_FLG_ON ? DISPLAY_FLG_OFF : DISPLAY_FLG_ON;
        $data->up_date = date('Y/m/d H:i:s');

        $this->saveData($data);

        return $data->display_flg;
    }

    // ツールメソッド
    protected function obj2arr($obj) {
        if (!is_object($obj))
            return $obj;

        $arr = (array) $obj;
        $result = array();
        foreach ($arr as $key => &$a) {
            if (!is_object($a) && !is_array($a)) {
                $result[$key] = $a;
            }
        }
        return $result;
    }

    public function toArray($obj) {
        $array = array();
        foreach ($obj as $row) {
            $array[] = $row;
        }
        return $array;
    }

    public function getPaginator($select, $sql, Array $pageInfo = null)
    {
        if ($pageInfo == null) {
            $pageInfo = array(
                'page_no'           => 1,
                'count_per_page'    => PAGINATOR_PERPAGE,
                'page_range'        => PAGINATOR_RANGE,
            );
        } else {
            $pageInfo = array(
                'page_no'           => empty($pageInfo['page_no']) ? 1 : $pageInfo['page_no'],
                'count_per_page'    => empty($pageInfo['count_per_page']) ? PAGINATOR_PERPAGE : $pageInfo['count_per_page'],
                'page_range'        => empty($pageInfo['page_range']) ? PAGINATOR_RANGE : $pageInfo['page_range']
            );
        }

        $adapter = new \Zend\Paginator\Adapter\DbSelect($select, $sql);
        $paginator = new \Zend\Paginator\Paginator($adapter);
        $paginator->setCurrentPageNumber($pageInfo['page_no']);
        $paginator->setItemCountPerPage($pageInfo['count_per_page']);
        $paginator->setPageRange($pageInfo['page_range']);

        $paginator->getCurrentItems();

        return $paginator;
    }
    
    public function getPagiNatorByArray($list, Array $pageInfo = null) {
        if ($pageInfo == null) {
            $pageInfo = array(
                'page_no'           => 1,
                'count_per_page'    => PAGINATOR_PERPAGE,
                'page_range'        => PAGINATOR_RANGE,
            );
        } else {
            $pageInfo = array(
                'page_no'           => empty($pageInfo['page_no']) ? 1 : $pageInfo['page_no'],
                'count_per_page'    => empty($pageInfo['count_per_page']) ? PAGINATOR_PERPAGE : $pageInfo['count_per_page'],
                'page_range'        => empty($pageInfo['page_range']) ? PAGINATOR_RANGE : $pageInfo['page_range']
            );
        }
        
        // ページネイターの作成
        $paginator = new \Zend\Paginator\Paginator(
                new \Zend\Paginator\Adapter\ArrayAdapter($list));
       $paginator->setCurrentPageNumber($pageInfo['page_no']);
        $paginator->setItemCountPerPage($pageInfo['count_per_page']);
        $paginator->setPageRange($pageInfo['page_range']);

        $paginator->getCurrentItems();

        return $paginator;
    }
    // ツールメソッド
}
