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
use Zend\Session\Container as SessionContainer;
use Bookstore\Form\LoginForm;

class IndexController extends AbstractActionController {

    //Login in the site
    public function indexAction() {
        $session = new SessionContainer();
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
            if ($form->isValid() === true) {
                $resultSet = $userInfoTable->getUser($userInfo['email']);
                $session->userInfo = $resultSet;
                //ユーザー情報が二週間保存できる
                setcookie('email', $resultSet->email, (time() + 14 * 24 * 3600), '/');
                setcookie('password', $resultSet->password, (time() + 14 * 24 * 3600), '/');
                //メインメニューへ
                return $this->redirect()->toUrl('/bookstore/index/menu');
                //return $this->forward()->dispatch('Bookstore\Controller\Index',array('action' => 'menu')); 
                //return $this->redirect()->toRoute('bookstore',array('controllter'=>'index','action'=>'menu'));
            }
        } else {
            //既に登録された場合、ユーザー情報をクッキーに保持
            $userInfo['email'] = isset($_COOKIE['email']) ? $_COOKIE['email'] : NULL;
            $userInfo['password'] = isset($_COOKIE['password']) ? $_COOKIE['password'] : NULL;
            $form->get('email')->setValue($userInfo['email']);
            $form->get('password')->setValue($userInfo['password']);
        }
        $view->form = $form;
        $view->title = "ログイン";
        $this->layout()->setTemplate('layout/layout_index');
        return $view;
    }

    //select a choice
    public function menuAction() {

        $session = new SessionContainer();
        $view = new ViewModel();
        $this->layout()->setVariable('userInfo', $session->userInfo->user);
        //$dirs = explode("/", $_SERVER['REQUEST_URI'], -1);
        //$basePath = $dirs[1];
        //echo $_SERVER['SERVER_NAME']; 
        $view->data = $session->userInfo;
        $view->title = "メニュー";
        return $view;
    }

}
