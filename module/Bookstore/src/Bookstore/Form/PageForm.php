<?php

namespace Bookstore\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;

class PageForm extends Form {

    public function __construct() {
        
        parent::__construct('page');
        //$this->setAttribute('method', 'post');
        
//        $this->add(array(
//            'name' => 'BEFORE',
//            'type' => 'button',
//        ));
//        
//        $this->add(array(
//            'name' => 'NEXT',
//            'type' => 'button',
//        ));
//        
//        $this->add(array(
//            'name' => 'GO',
//            'type' => 'button',
//        ));

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
                            \Zend\Validator\Digits::NOT_DIGITS => sprintf('%sを数字で入力してください。', '書類価格'),
                        ),
                    ),
                )
            )
        ));

        return $inputFilter;
    }

}
