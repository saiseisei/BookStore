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
use Zend\Session\Container as SessionContainer; 
use Zend\View\Model\ViewModel;
use Bookstore\Form\BookForm;

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
        $form = new BookForm();
        $bookInfo = array(); //get_object_vars

        $bookInfoTable = $this->getServiceLocator()->get('Bookstore\Model\BookInfoTable');
        if ($request->isPost()) {
            $bookInfo = $request->getPost();
            $form->setInputFilter($form->getInputFilter());
            $form->setData($bookInfo);
            if ($form->isValid() === true) {
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
        $session = new SessionContainer();
        $view = new ViewModel;
        $request = $this->getRequest();
        $form = new BookForm();
        $bookInfo = array();
        $row = null;

        $bookInfoTable = $this->getServiceLocator()->get('Bookstore\Model\BookInfoTable');
        if ($request->isGet()) {
            $isbn = $this->params()->fromQuery('id', 0);
            $row = $bookInfoTable->getBook($isbn);
            if (!$row) {
                throw new \Exception("Could not find $isbn");
            }
            $session->bookInfo = $row;
        } else if ($request->isPost()) {
            $row = $session->bookInfo;
            $bookInfo = array(
                'isbn' => $session->bookInfo->isbn,
                'title' => $request->getPost('title'),
                'price' => $request->getPost('price'),
            );
           
            
            $form->setInputFilter($form->getInputFilter());
            $form->setData($bookInfo);
            
            
            if ($form->isValid() === true) {
                $bookInfoTable->editBook($bookInfo);
                $view->dataAfter = $bookInfo;
                echo '書類情報変更成功！';
            }else {
                //echo '123456789';
            }
            
        }
        
        $view->dataBefore = $row;
        $view->form = $form;
        return $view;
    }

    //書類削除
    public function deleteAction() {

        $view = new ViewModel;
        return $view;
    }

}
