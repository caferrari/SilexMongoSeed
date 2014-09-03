<?php

namespace Site\Form;

use Symfony\Component\Validator\Constraints as Assert;

use Application\AbstractForm;

class Login extends AbstractForm
{

    public function buildForm($factory, $entity = null)
    {
        return $factory->createBuilder('form')
            ->add('email', 'email')
            ->add('password', 'password')
            ->add('login', 'submit')
            ->getForm();
    }

}