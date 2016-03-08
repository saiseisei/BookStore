<?php
/**
 * 日立従業員ポータル
 *
 * 機能：ベースコントローラー
 */

namespace Application\Controller;

use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Console\Request as ConsoleRequest;
use Application\Service\UtilsService as Utils;
use Zend\Session\Container;

class BaseController extends AbstractActionController
{
    protected $dbAdapter;
    protected $viewObject;
    protected $dataTable = array();
    public $isManage = false;
    public $aclName = '';
    public $aclType = ACL_TYPE_ALL;
    public $canAccessManage = [];

    protected function getDBAdapter()
    {
        if(!$this->dbAdapter) {
            $sm = $this->getServiceLocator();
            $this->dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        }
        return $this->dbAdapter;
    }

    public function checkLogin()
    {
        $ldapId = false;
        $env = getenv('APP_ENV') ?: APP_ENV_PRODUCTION;
        $whenErr = 'employee';
        $checkResult = true;
        if ($env == APP_ENV_PRODUCTION) {
            if (!isset($_SERVER[USER_LDAP_ID])) {
                $checkResult = false;
            } else {
                $ldapId = $_SERVER[USER_LDAP_ID];
            }
        } else {
            if (!isset($_COOKIE[USER_LDAP_ID])) {
                $checkResult = false;
            } else {
                $ldapId = $_COOKIE[USER_LDAP_ID];
            }
        }
        if ($checkResult) {
            if ($this->isManage) {
                $tableAdmin = $this->getDataTable('tbl_admin_mast');
                $checkResultAdmin = $tableAdmin->existUser($ldapId);
                $tableOfficeAdmin = $this->getDataTable('tbl_office_admin_mast');
                $checkResultOfficeAdmin = $tableOfficeAdmin->existUser($ldapId);
                
                if (!empty($this->canAccessManage['admin']) && !empty($this->canAccessManage['office_admin'])) {
                    // 両権限が閲覧できる機能の場合のチェック
                    if (!$checkResultAdmin && !$checkResultOfficeAdmin) $checkResult = false;
                }
                else if (!empty($this->canAccessManage['admin']) || !empty($this->canAccessManage['office_admin'])){
                    if (!empty($this->canAccessManage['admin'])) {
                        // 管理者権限
                        if (!$checkResultAdmin) $checkResult = false;
                    }
                    if (!empty($this->canAccessManage['office_admin'])) {
                        // 事業所管理者権限
                        if (!$checkResultOfficeAdmin) $checkResult = false;
                    }
                }
                else {
                    $checkResult = false;
                }
                $whenErr = 'manage';
            }

            $table = $this->getDataTable('tbl_mp_user_mast');
            $userData = $table->fetchAll(null, ['LDAP_ID' => $ldapId]);
            if (empty($userData)) {
                $checkResult = false;
            } elseif (count($userData) > 1) {
                $checkResult = false;
            } else {
                $userData = $userData->current();
                if (!empty($userData)) {
                    if (strlen($userData->LDAP_ID) != 8) {
                        $checkResult = false;
                    } elseif ($userData->LDAP_ID == '99999999') {
                        $checkResult = false;
                    } else {
                        $view = $this->getViewModel();
                        $view->user_name = $userData->LAST_NAME_KANJI . $userData->FIRST_NAME_KANJI;
                        //$view->user_name = $userData->FIRST_NAME_KANJI . $userData->LAST_NAME_KANJI;
                        $view->user_company = $userData->COMPANY_NAME;
                    }
                }
                else {
                    $checkResult = false;
                }
            }
        }

        if (!$checkResult) {
            $whenErr = $this->aclName;
            return $this->redirect()->toUrl(SUBDIR_PATH . '/' . $whenErr . '/error/errauth');
        }

        $session = new Container(SESSION_NAMESPACE);
        if ($session->logonFlag != 1) {
            $logTable = $this->getDataTable('tbl_log_data');
            $ldapid = $this->getLDAPID();
            if (!$this->isManage) {
                $logTable->saveLogData(0, $ldapid, 0);
            }
            $session->logonFlag = 1;
        }
        $tableLog = $this->getDataTable('tbl_log_data');
        $logon_log = $tableLog->fetchAll('LOG_DATE DESC', ['LOG_ID' => 0, 'LOG_LDAP_ID' => $ldapId])->toArray();
        if (count($logon_log) >= 2) {
            $view->last_logon_time = $logon_log[1]['LOG_DATE'];
        } elseif(count($logon_log) == 1) {
            //$view->last_logon_time = $logon_log[0]['LOG_DATE'];
            $view->last_logon_time = '';
        } else {
            $view->last_logon_time = '';
        }

        return true;
    }

    public function getViewModel()
    {
        if(!$this->viewObject) {
            $this->viewObject = new ViewModel();
        }
        return $this->viewObject;
    }

    public function getDataTable($table)
    {
        if(!isset($this->dataTable[$table]) || !$this->dataTable[$table]) {
            $tf = $this->getServiceLocator()->get('TableManager');
            $this->dataTable[$table] = $tf->getDataTable($table);
        }
        return $this->dataTable[$table];
    }

    public function setEventManager(EventManagerInterface $events)
    {
        parent::setEventManager($events);
        $controller = $this;
        $events->attach('dispatch', function ($e)
        {
            $controller = $e->getTarget();
            $routeMatch = $e->getRouteMatch();
            $c = $routeMatch->getParam('controller');
            $a = $routeMatch->getParam('action');
            $view = $controller->getViewModel();
            $view->controllerName = $c;
            $view->actionName = $a;

            $rname = $routeMatch->getMatchedRouteName();
            $request = $controller->getRequest();
            if ($request instanceof ConsoleRequest) {
                return;
            }

            if (strpos($rname, 'manage') !== false) {
                $controller->aclName = 'manage';
            } elseif (strpos($rname, 'employee') !== false) {
                if (strpos($rname, 'popup') !== false) {
                    $controller->aclName = 'employee';
                    $this->layout('layout/popup');
                }else{
                    $controller->aclName = 'employee';
                    $this->layout('layout/employee');
                }
            } elseif (strpos($rname, 'family') !== false) {
                if (strpos($rname, 'popup') !== false) {
                    $controller->aclName = 'family';
                    $this->layout('layout/popup');
                }else{
                    $controller->aclName = 'family';
                    $this->layout('layout/family');
                }
            }

            if (strpos($rname, 'manage') !== false || strpos($rname, 'employee') !== false) {
                if (strpos($rname, 'manage') !== false) {
                    $controller->isManage = true;

                    if ($controller->isManage && ($controller->aclType != ACL_TYPE_ALL and $controller->aclType != ACL_TYPE_MANAGE)) {
                        throw new \Exception(E044);
                    }

                    $this->layout('layout/manage');
                }
                $controller->checkLogin();
            }
        }, 100);
    }

    protected function getFlashMessages()
    {
        $message_array = array();
        $flashMessenger = $this->flashMessenger();
        if($flashMessenger->hasMessages()) {
            $message_array = $flashMessenger->getMessages();
        }
        return $message_array;
    }

    public function getLDAPID() {
        $env = getenv('APP_ENV') ?: APP_ENV_PRODUCTION;
        $ldapId = "";
        if ($env == APP_ENV_PRODUCTION) {
            $ldapId = $_SERVER[USER_LDAP_ID];
        } else {
            $ldapId = $_COOKIE[USER_LDAP_ID];
        }
        return $ldapId;
    }

    public function getCompanyName() {
        $table = $this->getDataTable('tbl_mp_user_mast');
        $ldap_id = $this->getLDAPID();
        $userData = $table->getData($ldap_id);
        return $userData->COMPANY_NAME;
    }
}
