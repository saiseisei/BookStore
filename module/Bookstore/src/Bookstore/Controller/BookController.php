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
use Bookstore\Form\AddBookForm;

class BookController extends AbstractActionController {

    //書類一覧
    public function indexAction() {

        $view = new ViewModel;
        $bookInfoTable = $this->getServiceLocator()->get('Bookstore\Model\BookInfoTable');
        $books = $bookInfoTable->fetchAll();
        $view->data = $books;
        $view->title = "書類一覧";
        $view->setTemplate("bookstore/book/index");
        return $view;
    }

    //書類登録
    public function addAction() {
        
        $view = new ViewModel;
        $request = $this->getRequest();
        $form = new AddBookForm();
        $bookInfo = array(); //get_object_vars
        
        $bookInfoTable = $this->getServiceLocator()->get('Bookstore\Model\BookInfoTable');
        if($request->isPost()){
            $bookInfo = $request->getPost();
            $form->setInputFilter($form->getInputFilter());
            $form->setData($bookInfo);
            if($form->isValid() === true){
                $bookInfoTable->addBook($bookInfo);
                echo '書類登録成功！';
            }
        }
        $view->data = $bookInfo;
        $view->form = $form;
        $view->title = "書類登録";
        return $view;
    }

    //書類更新
    public function editAction() {
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
