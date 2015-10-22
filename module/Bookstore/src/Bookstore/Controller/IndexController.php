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

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $view = new ViewModel();
        $request = $this->getRequest();
        $form = new LoginForm();
        $userInfo = array();
        $resultSet = array();
        $userInfoTable = $this->getServiceLocator()->get('Bookstore\Model\UserInfoTable');
        
        if($request->isPost()){
            $userInfo = $request->getPost();
            $form->setData($userInfo);
            $form->setInputFilter($form->getInputFilter());
            
            if($form->isValid() == false){
                echo '<pre>';    
        var_dump($userInfo);
        echo '</pre>';
                
                
            }
            $resultSet = $userInfoTable->getUser($userInfo['email']);

        echo '<pre>';    
        //var_dump($userInfo);
        echo '</pre>';
        }
        $view->form = $form;
        $view->data = $resultSet;
        $view->title = "ログイン";
        return $view;
    }
}
