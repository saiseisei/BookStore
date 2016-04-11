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
use \Bookstore\Form\PageForm;

define('RECORDS_IN_ONE_PAGE', 15);

class BookController extends AbstractActionController {

    //List all the books
    public function indexAction() {
        $view = new ViewModel;
        $form = new PageForm();
        $request = $this->getRequest();
        $session = new SessionContainer;
        $bookInfoTable = $this->getServiceLocator()->get('Bookstore\Model\BookInfoTable');

        $pagination = array('books' => array(), 'currentPage' => 1);
        $post = array();
        $keyword = "";
        $totalPages = "";

        if ($request->isPost()) {
            $post = $request->getPost();
//\Zend\Debug\Debug::dump($post);
            $form->setInputFilter($form->getInputFilter());
            $form->setData($post);
            if ($form->isValid() == true) {
                if (array_key_exists('SEARCH', $post)) {
                    $keyword = $post['keyword'];
                    $session->keyword = $keyword;
                }
                $rowsNum = $bookInfoTable->countBooksNum($session->keyword);
                $totalPages = ceil($rowsNum / RECORDS_IN_ONE_PAGE);
                $currentPage = $session->currentPage;
                $pagination = $this->Pagination($currentPage, $totalPages, $post);
            }
            $session->post = $post;
            $session->currentPage = $pagination['currentPage'];
        } else {
            $rowsNum = $bookInfoTable->countBooksNum($session->keyword);
            $totalPages = ceil($rowsNum / RECORDS_IN_ONE_PAGE);
            $currentPage = $session->currentPage;
            
            if (empty($currentPage)) {
                $currentPage = 1;
            } else if ($currentPage > $totalPages) {
                $currentPage = $currentPage - 1;
            } else {
                $currentPage = $currentPage;
            }
            
            $form->setData($post);
            $pagination = $this->Pagination($currentPage, $totalPages, $post);
            $session->currentPage = $currentPage;
        }

        $showBefore = TRUE;
        $showNext = TRUE;
        if ($session->currentPage == 1) {
            $showBefore = FALSE;
        }
        if ($session->currentPage == $totalPages) {
            $showNext = FALSE;
        }

//\Zend\Debug\Debug::dump($pagination);die;
        $view->keyword = $session->keyword;
        $view->data = $pagination["books"];
        $view->currentPage = $pagination["currentPage"];
        $view->totalPages = $totalPages;
        $view->showBefore = $showBefore;
        $view->showNext = $showNext;
        $view->title = "Books List";
        $view->form = $form;
        $view->setTemplate("bookstore/book/index");
        return $view;
    }

    //Add a book
    public function addAction() {

        $view = new ViewModel;
        $request = $this->getRequest();
        $form = new BookForm();
        $bookInfo = array();
        $addFlag = false;

        $bookInfoTable = $this->getServiceLocator()->get('Bookstore\Model\BookInfoTable');
        if ($request->isPost()) {
            $bookInfo = $request->getPost();
            $form->setInputFilter($form->getInputFilter());
            $form->setData($bookInfo);
            if ($form->isValid() === true) {
                $option = $bookInfo->CATEGORY;
                $categoryTable = $this->getServiceLocator()->get('Bookstore\Model\CategoryTable');
                $category = $categoryTable->getCategory($option);
                $bookInfo->CATEGORY = $category['CATEGORY'];
                $bookInfoTable->addBook($bookInfo);
                $addFlag = true;
            } else {
                //error
            }
        }
//\Zend\Debug\Debug::dump($bookInfo);
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
        $editFlag = false;

        $bookInfoTable = $this->getServiceLocator()->get('Bookstore\Model\BookInfoTable');
        if ($request->isGet()) {
            $id = $this->params()->fromQuery('id', 0);
            $bookInfo = $bookInfoTable->getBook($id);
            $session->bookInfo = $bookInfo;
        } else if ($request->isPost()) {
            $bookInfo = array(
                'NO' => $session->bookInfo['NO'],
                'ISBN' => $session->bookInfo['ISBN'],
                'TITLE' => $request->getPost('TITLE'),
                'SUBTITLE' => $request->getPost('SUBTITLE'),
                'WRITER' => $request->getPost('WRITER'),
                'PRICE' => $request->getPost('PRICE'),
                'CATEGORYID' => $request->getPost('CATEGORY'),
                'COMMENT' => $request->getPost('COMMENT'),
            );

            $form->setInputFilter($form->getInputFilter());
            $form->setData($bookInfo);

            if ($form->isValid() === true) {
                $bookInfoTable->editBook($bookInfo);
                $editFlag = true;
                //return $this->redirect()->toUrl('/bookstore/book/index');
            } else {
                //error
            }
        }
        $view->bookInfo = $bookInfo;
        $view->editFlag = $editFlag;
        $view->form = $form;
        $view->title = "Edit A Book";
        return $view;
    }

    //Delete books
    public function deleteAction() {
        $session = new SessionContainer;
        $request = $this->getRequest();
        $view = new ViewModel;
        $isbns = array();

        $bookInfoTable = $this->getServiceLocator()->get('Bookstore\Model\BookInfoTable');
        if ($request->isGet()) {
            $isbns = $this->params()->fromQuery('deleteObjs', 0);
            $session->isbns = $isbns;
            $bookInfoTable->deleteBook($session->isbns);
            unset($session->isbns);
            return $this->redirect()->toUrl('/bookstore/book/index');
        }

        $view->title = "Delete Books";
        return $view;
    }

    public function Pagination($currentPage, $totalPages, $post) {

        // Search by keyword
        $session = new SessionContainer;
        $keyword = $session->keyword;

        switch (isset($post)) {
            case array_key_exists('BEFORE', $post):
                $currentPage = $currentPage - 1;
                break;
            case array_key_exists('NEXT', $post):
                $currentPage = $currentPage + 1;
                break;
            case array_key_exists('GO', $post):
                if (!empty($post['goToPage'])) {
                    if ($post['goToPage'] > 0 && $post['goToPage'] <= $totalPages) {
                        $currentPage = $post['goToPage'];
                    }
                } else {
                    $currentPage = 1;
                }
                break;
            case array_key_exists('SEARCH', $post):
                $keyword = $post['keyword'];
                $currentPage = 1;
            default:
                if (empty($currentPage)) {
                    $currentPage = 1;
                } else {
                    $currentPage = $currentPage;
                }
                break;
        }  
//echo 'currentPage:'.$currentPage.'<br>totalPages:'.$totalPages.'<br>keyword:'.$keyword;
        $bookInfoTable = $this->getServiceLocator()->get('Bookstore\Model\BookInfoTable');
        $books = $bookInfoTable->getBooks($currentPage, $keyword);
        return $pagination = array(
            'currentPage' => $currentPage,
            'books' => $books,
        );
    }
    
}
