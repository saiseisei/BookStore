<?php
namespace Bookstore\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Bookstore\Model;

class TableFactory implements FactoryInterface
{
    private $serviceManager = null;
    private $tableConfig = array();
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $service = new TableFactory();
        $config = [
            'bookinfo'      => [
                'table' => 'BookInfoTable',
                'model' => 'BookInfo'
            ],
            'userinfo'      => [
                'table' => 'UserInfoTable',
                'model' => 'UserInfo'
            ],
            'category'      => [
                'table' => 'CategoryTable',
                'model' => 'Category'
            ],
        ];
        $service->setServiceManager($serviceLocator);
        $service->setTableConfig($config);
        return $service;
    }

    public function setServiceManager(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceManager = $serviceLocator;
    }

    public function setTableConfig(Array $config)
    {
        $this->tableConfig = $config;
    }

    public function getDataTable($tableName)
    {
        if (!isset($this->tableConfig[$tableName])) {
            throw new \Exception(sprintf(E020, $tableName));
        }

        $config = $this->tableConfig[$tableName];
        $modelObject = 'Bookstore\\Model\\' . $config['model'];
        $tableObject = 'Bookstore\\Model\\' . $config['table'];

        $tableInstance = new $tableObject(static::getTableGateway($this->serviceManager, $tableName, new $modelObject()));
        $tableInstance->setServiceLocator($this->serviceManager);
        return $tableInstance;
    }

    static function getTableGateway($sm, $table, $object) {
        $resultSetPrototype = new HydratingResultSet();
        $resultSetPrototype->setObjectPrototype($object);
        return new TableGateway($table,
                        $sm->get('Zend\Db\Adapter\Adapter'),
                        null,
                        $resultSetPrototype
        );
    }

}