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
use Bookstore\Form\LoginForm;

class IndexController extends AbstractActionController {

    public function indexAction() {
        $view = new ViewModel();
        $request = $this->getRequest();
        $form = new LoginForm();
        $userInfo = array();
        $resultSet = array();
        $userInfoTable = $this->getServiceLocator()->get('Bookstore\Model\UserInfoTable');

        if ($request->isPost()) {
            $userInfo = $request->getPost();
            $form->setInputFilter($form->getInputFilter());
            $form->setData($userInfo);
            if ($form->isValid() == true) {
                $resultSet = $userInfoTable->getUser($userInfo['email']);
                //return $this->forward()->dispatch('Bookstore\Controller\Index',array('action' => 'menu')); 
                return $this->redirect()->toUrl('/bookstore/index/menu');
                /*return $this->redirect()->toRoute('bookstore',array('controllter'=>'index','action'=>'menu'));
                echo '<pre>';
                var_dump($resultSet);
                echo '</pre>';
                $view->setTemplate('bookstore/index/menu');
                return $this->getRequest()->getRequestUri();
                echo $this->redirect()->toRoute('bookstore',array('controller'=>'index','action' => 'menu'));
                 */
            }
        }
        $view->form = $form;
        $view->data = $resultSet;
        $view->title = "ログイン";
        return $view;
    }
    
    public function menuAction() {
        $view = new ViewModel();
        //$dirs = explode("/", $_SERVER['REQUEST_URI'], -1);
        //$basePath = $dirs[1];
        //echo $_SERVER['SERVER_NAME']; 
        //echo '<pre>';
        //var_dump($basePath);
        //echo '</pre>';
        //$view->basePath = $basePath;
        $view->title = "メニュー";
        return $view;
    }

    
    
    
    
}
