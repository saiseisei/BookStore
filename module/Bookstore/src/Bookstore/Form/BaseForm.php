<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

 class BaseForm extends Form
                implements ServiceLocatorAwareInterface {

     public function __construct($name = null)
     {
         parent::__construct($name);

        $this->add(array(
             'name' => 'back',
             'type' => 'hidden',
             'attributes' => array(
                 'value' => '1',
                 'id' => 'back',
                 'class' => 'back'
             ),
        ));
     }

    private $service_manager;

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->service_manager = $serviceLocator;
    }

    public function getServiceLocator()
    {
        return $this->service_manager;
    }

    public function getDataTable($table)
    {
        $tf = $this->getServiceLocator()->get('TableManager');
        return $tf->getDataTable($table);
    }

    public function getValues()
    {
// \Zend\Debug\Debug::dump($this->getElements());

        $elements = $this->getElements();
        $result = [];
        foreach ($elements as $key => $e) {
            $result[$key] = $e->value;
        }

        return $result;
    }
 }