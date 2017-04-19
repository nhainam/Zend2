<?php
// Filename: /module/Blog/src/Blog/Form/PostForm.php
namespace Blog\Form;

use Zend\Form\Form;

class PostForm extends Form
{
    public function __construct($name = "", $options = array())
    {
        parent::__construct();

        $this->add([
            'name'  => 'post-fieldset',
            'type'  => 'Blog\Form\PostFieldSet',
            'options' => [
                'use_as_base_fieldset' => true
            ]
        ]);

        $this->add([
            'type'  => 'submit',
            'name'  => 'submit',
            'attributes' => [
                'value' => 'Insert new post'
            ]
        ]);
    }
}