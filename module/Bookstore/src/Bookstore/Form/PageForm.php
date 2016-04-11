<?php

namespace Bookstore\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;

class PageForm extends Form {

    public function __construct() {
        
        parent::__construct('page');
        
        $this->add(array(
            'name' => 'goToPage',
            'type' => 'text',
            'attributes' => array(
                'size' => 3,
            ),
        ));
    }

    public function getInputFilter() {

        $inputFilter = new InputFilter();

        $inputFilter->add(array(
            'name' => 'goToPage',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'Digits',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\Digits::NOT_DIGITS => sprintf('%sを数字で入力してください。', 'ページ'),
                        ),
                    ),
                )
            )
        ));
        
        return $inputFilter;
    }

}
