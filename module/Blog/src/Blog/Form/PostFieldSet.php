<?php
// Filename: /module/Blog/src/Blog/Form/PostFieldSet.php
namespace Blog\Form;

use Blog\Model\Post;
use Zend\Form\Fieldset;
use Zend\Stdlib\Hydrator\ClassMethods;

class PostFieldSet extends Fieldset
{
    public function __construct($name = "", $options = array())
    {
        parent::__construct();

        $this->setHydrator(new ClassMethods(false));
        $this->setObject(new Post());

        $this->add([
            'type' => 'hidden',
            'name'  => 'id'
        ]);

        $this->add([
            'type'  => 'text',
            'name'  => 'text',
            'options'   => [
                'label' => 'The Text'
            ],
        ]);

        $this->add([
            'type'  => 'text',
            'name'  => 'title',
            'options'   => [
                'label' => 'Blog Title'
            ]
        ]);
    }
}