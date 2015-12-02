<?php

namespace Bookstore\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;

class BookForm extends Form {

    //book form
    public function __construct() {

        parent::__construct('book');

        $this->add(array(
            'name' => 'isbn',
            'type' => 'text',
            'attributes' => array(
                'size' => '20',
                'maxlength' => '20',
            ),
//            'options' => array(
//                'label' => 'ISBN:',
//            ),
        ));
        $this->add(array(
            'name' => 'title',
            'type' => 'text',
            'attributes' => array(
                'size' => '30',
                'maxlength' => '100',
            ),
//            'options' => array(
//                'label' => 'TITLE:',
//            ),
        ));
        $this->add(array(
            'name' => 'price',
            'type' => 'text',
            'attributes' => array(
                'size' => '15',
                'maxlength' => '11',
            ),
//            'options' => array(
//                'label' => 'PRICE:',
//            ),
        ));
    }

    public function getInputFilter() {

        $inputFilter = new InputFilter();

        $inputFilter->add(array(
            'name' => 'isbn',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => sprintf('%sを入力してください。', '書類番号'),
                        ),
                    ),
                ),
                array(
                    'name' => 'StringLength',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 0,
                        'max' => 20,
                        'message' => array(
                            \Zend\Validator\StringLength::TOO_LONG => sprintf('%sは%s文字以内で入力してください。', '書類番号', '20'),
                        )
                    ),
                ),
            ),
        ));

        $inputFilter->add(array(
            'name' => 'title',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => sprintf('%sを入力してください。', '書類タイトル'),
                        ),
                    ),
                ),
                array(
                    'name' => 'StringLength',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 0,
                        'max' => 100,
                        'messages' => array(
                            \Zend\Validator\StringLength::TOO_LONG => sprintf('%sを最多%s文字で入力してください。', '書類タイトル', '100'),
                        ),
                    ),
                )
            )
        ));

        $inputFilter->add(array(
            'name' => 'price',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => sprintf('%sを入力してください。', '書類価格'),
                        ),
                    ),
                ),
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
