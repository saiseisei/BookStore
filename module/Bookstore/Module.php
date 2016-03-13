<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Bookstore;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\ServiceManager;
use Bookstore\Model\UserInfoTable;
use Bookstore\Model\UserInfo;
use Bookstore\Model\BookInfoTable;
use Bookstore\Model\BookInfo;
use Bookstore\Model\CategoryTable;
use Bookstore\Model\Category;

class Module {

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

//        $serviceManager = new ServiceManager();
//        $viewModel = $e->getApplication()->getMvcEvent()->getViewModel();
//        $myService = $serviceManager->get('MyModule\Service\MyService');
//        $viewModel->someVar = $myService->getSomeValue();
        //$this->layout()->someVar;
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig() {

        return array(
            'factories' => array(
                //'userinfo'
                'Bookstore\Model\UserInfoTable' => function(ServiceManager $serviceManager) {
                    $tableGateway = $serviceManager->get('UserInfoTableGateway');
                    $table = new UserInfoTable($tableGateway);
                    return $table;
                },
                'UserInfoTableGateway' => function(ServiceManager $serviceManager) {
                    $dbAdapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new UserInfo());
                    return new TableGateway('userinfo', $dbAdapter, NULL, $resultSetPrototype);
                },
                //'bookinfo'
                'Bookstore\Model\BookInfoTable' => function(ServiceManager $serviceManager) {
                    $tableGateway = $serviceManager->get('BookInfoTableGateway');
                    $table = new BookInfoTable($tableGateway);
                    return $table;
                },
                'BookInfoTableGateway' => function(ServiceManager $serviceManager) {
                    $dbAdapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new BookInfo());
                    return new TableGateway('bookinfo', $dbAdapter, NULL, $resultSetPrototype);
                },
                //'category'
                'Bookstore\Model\CategoryTable' => function(ServiceManager $serviceManager) {
                    $tableGateway = $serviceManager->get('CategoryTableGateway');
                    $table = new CategoryTable($tableGateway);
                    return $table;
                },
                'CategoryTableGateway' => function(ServiceManager $serviceManager) {
                    $dbAdapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Category());
                    return new TableGateway('category', $dbAdapter, NULL, $resultSetPrototype);
                },
            )
        );
    }

}
