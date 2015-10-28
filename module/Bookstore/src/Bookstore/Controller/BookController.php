<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Bookstore\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BookController extends AbstractActionController {

    public function indexAction() {
        //書類一覧
        $view = new ViewModel;
        $bookInfoTable = $this->getServiceLocator()->get('Bookstore\Model\BookInfoTable');
        $resultSet = $bookInfoTable->fetchAll();
        $view->data = $resultSet;
        $view->setTemplate("bookstore/book/index");
        return $view;
    }

    //書類登録
    public function addAction() {
        //書類一覧
        $view = new ViewModel;
        return $view;
    }

    //書類更新
    public function updateAction() {
        //書類一覧
        $view = new ViewModel;
        return $view;
    }

    //書類削除
    public function deleteAction() {
        //書類一覧
        $view = new ViewModel;
        return $view;
    }

}
