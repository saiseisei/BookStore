<?php
namespace Bookstore\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CodeFactory implements FactoryInterface
{
    private $serviceManager = null;
    private $tableManager = null;

    private $codeConfig = [
        'kenpo_flag'    => [
            SEND_KENPO_OFF => '',
            SEND_KENPO_ON => '日立けんぽ加入者'
        ],
        'important_flag'    => [
            IMPORTANT_FLAG_ON => '重要',
            IMPORTANT_FLAG_OFF => '重要ではない'
        ],
        'target_flag'    => [
            FLAG_ON => ' 別ウィンドウ表示',
            FLAG_OFF => ' 同じウィンドウ表示'
        ],
        'disp_flag' => [
            DISP_FLAG_ON    => '公開',
            DISP_FLAG_OFF   => '非公開'
        ],
        
        'sum_out_flag' => [
            SUM_OUT_FLAG_ON    => '集計する',
            SUM_OUT_FLAG_OFF   => '集計しない'
        ],
        
        'redirect_type' => [
            REDIRECT_TYPE_TITLE    => 'タイトルのみ',
            REDIRECT_TYPE_DETAIL   => '詳細ページあり'
        ],
        'info_target'   => [
            INFO_TARGET_EMPLOYEE    => '従業員向けサイト',
            INFO_TARGET_FAMILY      => '家族向けサイト'
        ],
        'info_target_employee'   => [
            INFO_TARGET_EMPLOYEE    => '従業員向けサイト',
        ],
        'send_status'   => [
            SEND_STATUS_PENDING     => '未送信',
            SEND_STATUS_SENDING     => '送信中',
            SEND_STATUS_SENDED      => '送信済',
            SEND_STATUS_ERROR       => 'エラー',
        ],
        'file_status'   => [
            FILE_STATUS_PENDING     => '未作成',
            FILE_STATUS_MAKING     => '作成中',
            FILE_STATUS_MAKED      => '作成済',
            FILE_STATUS_ERROR       => 'エラー',
        ],
        'mail_to_type'  => [
            MAIL_TO_TYPE_SELECT     => '送信対象ユーザをToにして送信',
            MAIL_TO_TYPE_INPUT      => '以下のメールアドレスをToにして送信対象ユーザをBccに設定する'
        ],
        'send_user_data_type'  => [
            SEND_USER_DATA_TYPE_FILE     => 'ファイルUP',
            SEND_USER_DATA_TYPE_USER      => 'ユーザ情報より取得'
        ],
        'comp_code_type' => [
            COMP_CODE_TYPE_KAISYA   => '会社コード',
            COMP_CODE_TYPE_ZAISEKI_JI => '在籍事業所コード',
            COMP_CODE_TYPE_ZAIKIN_JI => '在勤事業所コード',
            COMP_CODE_TYPE_ZAISEKI_SYO => '在籍所属コード',
            COMP_CODE_TYPE_ZAIKIN_SYO => '在勤所属コード',
        ],
        'replace_type'  => [
            REPLACE_TYPE_CMPCD      => '会社コード',
            REPLACE_TYPE_LDAPID     => '従業員番号',
            REPLACE_TYPE_ZAISEKI  => '在籍事業所名称',
            REPLACE_TYPE_ZAIKIN   => '在勤事業所名称',
            REPLACE_TYPE_LASTNAME => 'ラストネーム（漢字）',
            REPLACE_TYPE_FIRSTNAME => 'ファーストネーム（漢字）',
            REPLACE_TYPE_EMAIL     => 'メールアドレス'
        ],
        'comp_code_type_show' => [
            'MP_COMP_CODE'   => '会社コード',
            'ENROLL_OFFICE_CODE' => '在籍事業所コード',
            'ZAIKIN_OFFICE_CODE' => '在勤事業所コード',
            'ENROLL_BELONG_CODE' => '在籍所属コード',
            'ZAIKIN_BELONG_CODE' => '在勤所属コード',
        ],
        'replace_type_show'  => [
            'MP_COMP_CODE'      => '会社コード',
            'MP_EMP_NO'     => '従業員番号',
            'ENROLL_OFFICE_NAME'  => '在籍事業所名称',
            'ZAIKIN_OFFICE_NAME'   => '在勤事業所名称',
            'LAST_NAME_KANJI' => 'ラストネーム（漢字）',
            'FIRST_NAME_KANJI' => 'ファーストネーム（漢字）',
            'EMAIL'     => 'メールアドレス'
        ],
        'office_comp_code_type' => [
            OFFICE_COMP_CODE_TYPE_KAISYA => '会社コード',
            OFFICE_COMP_CODE_TYPE_PR => 'PR管理コード',
            OFFICE_COMP_CODE_TYPE_HR => 'HR管理コード',
            OFFICE_COMP_CODE_TYPE_ZAISEKI_JI => '在籍事業所コード',
        ],
         'office_comp_code_type_values' => [
            OFFICE_COMP_CODE_TYPE_KAISYA => 'MP_COMP_CODE',
            OFFICE_COMP_CODE_TYPE_PR => 'PR_ADMIN_CODE',
            OFFICE_COMP_CODE_TYPE_HR => 'HR_ADMIN_CODE_ENROLL',
            OFFICE_COMP_CODE_TYPE_ZAISEKI_JI => 'ENROLL_OFFICE_CODE',
        ],
        'office_comp_code_type_show' => [
            'MP_COMP_CODE' => '会社コード',
            'PR_ADMIN_CODE' => 'PR管理コード',
            'HR_ADMIN_CODE_ENROLL' => 'HR管理コード',
            'ENROLL_OFFICE_CODE' => '在籍事業所コード',
        ],
        'office_comp_code_type_values_show' => [
            'MP_COMP_CODE' => OFFICE_COMP_CODE_TYPE_KAISYA,
            'PR_ADMIN_CODE' => OFFICE_COMP_CODE_TYPE_PR,
            'HR_ADMIN_CODE_ENROLL' => OFFICE_COMP_CODE_TYPE_HR,
            'ENROLL_OFFICE_CODE' => OFFICE_COMP_CODE_TYPE_ZAISEKI_JI,
        ],
        'send_mail_type'  => [
            SEND_MAIL_TYPE_IMPORTANT     => '制度関連',
            SEND_MAIL_TYPE_PR      => '福利関連'
        ],
        'open_date_year'    => [],
        'open_date_month'   => [
            '01' => '1',
            '02' => '2',
            '03' => '3',
            '04' => '4',
            '05' => '5',
            '06' => '6',
            '07' => '7',
            '08' => '8',
            '09' => '9',
            '10' => '10',
            '11' => '11',
            '12' => '12',
        ],
        'open_date_day'     => [
            '01' => '1',
            '02' => '2',
            '03' => '3',
            '04' => '4',
            '05' => '5',
            '06' => '6',
            '07' => '7',
            '08' => '8',
            '09' => '9',
            '10' => '10',
            '11' => '11',
            '12' => '12',
            '13' => '13',
            '14' => '14',
            '15' => '15',
            '16' => '16',
            '17' => '17',
            '18' => '18',
            '19' => '19',
            '20' => '20',
            '21' => '21',
            '22' => '22',
            '23' => '23',
            '24' => '24',
            '25' => '25',
            '26' => '26',
            '27' => '27',
            '28' => '28',
            '29' => '29',
            '30' => '30',
            '31' => '31',
        ],
        'open_date_Hour'    => [
            '00' => '00',
            '1' => '01',
            '2' => '02',
            '3' => '03',
            '4' => '04',
            '5' => '05',
            '6' => '06',
            '7' => '07',
            '8' => '08',
            '9' => '09',
            '10' => '10',
            '11' => '11',
            '12' => '12',
            '13' => '13',
            '14' => '14',
            '15' => '15',
            '16' => '16',
            '17' => '17',
            '18' => '18',
            '19' => '19',
            '20' => '20',
            '21' => '21',
            '22' => '22',
            '23' => '23',
        ],
        'open_date_min'     => [
            '00' => '00',
            '01' => '01',
            '02' => '02',
            '03' => '03',
            '04' => '04',
            '05' => '05',
            '06' => '06',
            '07' => '07',
            '08' => '08',
            '09' => '09',
            '10' => '10',
            '11' => '11',
            '12' => '12',
            '13' => '13',
            '14' => '14',
            '15' => '15',
            '16' => '16',
            '17' => '17',
            '18' => '18',
            '19' => '19',
            '20' => '20',
            '21' => '21',
            '22' => '22',
            '23' => '23',
            '24' => '24',
            '25' => '25',
            '26' => '26',
            '27' => '27',
            '28' => '28',
            '29' => '29',
            '30' => '30',
            '31' => '31',
            '32' => '32',
            '33' => '33',
            '34' => '34',
            '35' => '35',
            '36' => '36',
            '37' => '37',
            '38' => '38',
            '39' => '39',
            '40' => '40',
            '41' => '41',
            '42' => '42',
            '43' => '43',
            '44' => '44',
            '45' => '45',
            '46' => '46',
            '47' => '47',
            '48' => '48',
            '49' => '49',
            '50' => '50',
            '51' => '51',
            '52' => '52',
            '53' => '53',
            '54' => '54',
            '55' => '55',
            '56' => '56',
            '57' => '57',
            '58' => '58',
            '59' => '59',
        ],
        'info_category'   => [],
        'log_category'    => [],
        'log_category_select'    => [],
        'log_charge'    => [],
        'log_division'    => [],
        'log_output' => [
            LOG_OUTPUT_ALL    => '閲覧ログ全件',
            LOG_OUTPUT_CONFIRMED      => '確認済ユーザリスト',
            LOG_OUTPUT_UNCONFIRMED      => '未確認ユーザリスト'
        ],
        'email_type' => [
            EMAIL_TYPE_ADD => '追加メールアドレスに送信',
            EMAIL_TYPE_REPLACE => '代替メールアドレスに送信',
            EMAIL_TYPE_BASIC => '基本メールアドレスに送信',
        ],
        'email_send_type' => [
            MAIL_SEND_FLAG_ON   => '受信する',
            MAIL_SEND_FLAG_OFF   => '受信しない',
        ],
        'send_company'   => [],
        'search_logical' => [
            SEARCH_LOGICAL_AND => 'and',
            SEARCH_LOGICAL_OR => 'or'
        ]
    ];

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $service = new CodeFactory();
        $service->setServiceManager($serviceLocator);
        $service->init();
        return $service;
    }

    public function setServiceManager(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceManager = $serviceLocator;
    }

    public function init() {
        $this->tableManager = $this->serviceManager->get('TableManager');
        $this->codeConfig['info_category'] = $this->getInfoCategory();
        $this->codeConfig['log_category'] = $this->getLogCategory();
        $this->codeConfig['log_charge'] = $this->getLogCharge();
        $this->codeConfig['log_division'] = $this->getLogDivision();
        $this->codeConfig['open_date_year'] = $this->getOpenDateYear();
        $this->codeConfig['log_category_select'] = $this->getLogCategorySelect();
        $this->codeConfig['send_company'] = $this->getSendCompany();
    }

    public function getCode($name, $value = null)
    {
        if(isset($this->codeConfig[$name])) {
            $code = $this->codeConfig[$name];
            if(!is_null($value)) {
                if (isset($code[$value]))
                    return $code[$value];
                else
                    return '';
            } else {
                return $code;
            }
        } else {
            return '';
        }
    }

    public function getInfoCategory() {
        $table = $this->tableManager->getDataTable('tbl_info_category_mast');
        return $table->getSelectOptionsCategoyMast('INFO_CATEGORY_NAME');
    }
    public function getLogCategory() {
        $table = $this->tableManager->getDataTable('tbl_log_category_mast');
        return $table->getSelectOptions('LOG_CATEGORY_NAME');
    }
    public function getLogCharge() {
        $table = $this->tableManager->getDataTable('tbl_log_charge_mast');
        return $table->getSelectOptions('LOG_CHARGE_NAME');
    }
    public function getLogDivision() {
        $table = $this->tableManager->getDataTable('tbl_log_division_mast');
        return $table->getSelectOptions('LOG_DIVISION_NAME');
    }
    public function getOpenDateYear() {
        $result = [];
        $year = date('Y');
        $current = 2014;
        while ($current <= $year + 1) {
            $result[$current] = $current;
            $current++;
        }
        return $result;
    }
    public function getLogCategorySelect() {
        $table = $this->tableManager->getDataTable('tbl_log_category_mast');
        return $table->getSelectOptionsForLogSelect('LOG_CATEGORY_NAME');
    }
    public function getSendCompany() {
        $table = $this->tableManager->getDataTable('tbl_mail_send_comp_mast');
        return $table->getSelectOptionsForSendComp('COMPANY_NAME');
    }

}