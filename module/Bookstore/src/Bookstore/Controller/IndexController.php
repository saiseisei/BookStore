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
        
        if($request->isPost()){
            $userInfo = $request->getPost();
            $form->setInputFilter($form->getInputFilter());
            $form->setData($userInfo);
            if($form->isValid() == true){
                echo '123';
            }

            
        var_dump($userInfo);
        }
        $view->form = $form;
        $view->data = $userInfo;
        $view->title = "ログイン";
        return $view;
    }
}
