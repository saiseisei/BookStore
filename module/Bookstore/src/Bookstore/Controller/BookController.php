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

    //List all the books
    public function indexAction() {

        $view = new ViewModel;
        $bookInfoTable = $this->getServiceLocator()->get('Bookstore\Model\BookInfoTable');
        $books = $bookInfoTable->fetchAll();
        //\Zend\Debug\Debug::dump($books);
        $view->data = $books;
        $view->title = "Books List";
        $view->setTemplate("bookstore/book/index");
        return $view;
    }

    //Add a book
    public function addAction() {

        $view = new ViewModel;
        $request = $this->getRequest();
        $form = new BookForm();
        $bookInfo = array(); //get_object_vars
        $addFlag = false;

        $bookInfoTable = $this->getServiceLocator()->get('Bookstore\Model\BookInfoTable');
        if ($request->isPost()) {
            $bookInfo = $request->getPost();
            $form->setInputFilter($form->getInputFilter());
            $form->setData($bookInfo);
//\Zend\Debug\Debug::dump($bookInfo->category) ;
            if ($form->isValid() === true) {
                $bookInfoTable->addBook($bookInfo);
                $addFlag = true;
            }else {
                //error
            }
        }\Zend\Debug\Debug::dump($bookInfo);
        $view->addFlag = $addFlag;
        $view->data = $bookInfo;
        $view->form = $form;
        $view->title = "Add A Book";
        return $view;
    }

    //Edit a book
    public function editAction() {
        $session = new SessionContainer();
        $view = new ViewModel;
        $request = $this->getRequest();
        $form = new BookForm();
        $bookInfo = array();
        $row = null;
        $editFlag = false;

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
                $editFlag = true;
            } else {
                //error
            }
        }
        $view->editFlag = $editFlag;
        $view->dataBefore = $row;
        $view->form = $form;
        $view->title = "Edit A Book";
        return $view;
    }

    //Delete a book
    public function deleteAction() {
        $session = new SessionContainer;
        $request = $this->getRequest();
        $view = new ViewModel;
        $row = null;

        $bookInfoTable = $this->getServiceLocator()->get('Bookstore\Model\BookInfoTable');
        if ($request->isGet()) {
            $isbn = $this->params()->fromQuery('id', 0);
            $row = $bookInfoTable->getBook($isbn);
            if (!$row) {
                throw new \Exception("Could not find $isbn");
            }
            $session->bookInfo = $row;
            $view->data = $row;
        } elseif ($request->isPost()) {
            if ($request->getPost()->yes) {
                $bookInfoTable->deleteBook($session->bookInfo);
            }
            unset($session->bookInfo);
            return $this->redirect()->toUrl('/bookstore/book/index');
        }
        $view->title = "Delete A Book";
        return $view;
    }

}
