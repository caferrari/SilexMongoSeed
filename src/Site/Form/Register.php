<?php

namespace Site\Form;

use Symfony\Component\Validator\Constraints as Assert;

use Application\Document\User;
use Application\AbstractForm;

class Register extends AbstractForm
{

    public function buildForm($factory, $entity = null)
    {

        if (is_null($entity)) {
            $entity = new User();
        }

        return $factory->createBuilder('form', $entity)
            ->add('id', 'hidden')
            ->add('name', 'text')
            ->add('email', 'email', [
                'constraints' => [
                    new Assert\Callback(function ($email, $ctx) {
                        // se o email já for inválido, não precisamos consultar o banco
                        foreach ($ctx->getViolations()->getIterator() as $constraint) {
                            if ('data.email' === $constraint->getPropertyPath()) {
                                return true;
                            }
                        }

                        // Pesquisa por algum usuário com este e-mail usuário no banco de dados
                        $repo = $this->app['dm']->getRepository('\\Application\\Document\\User');
                        $user = $repo->findOneByEmail($email);

                        // Se não existir ninguém com este email, tudo certo
                        if (!$user) {
                            return true;
                        }

                        // Adiciona o erro
                        $ctx->addViolationAt(
                            'email',
                            'e-mail alread in use',
                            array(),
                            null
                        );
                    })
                ]
            ])
            ->add('password', 'repeated', [
                'type' => 'password',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options' => array('label' => 'Password'),
                'second_options' => array('label' => 'Confirm Password'),
            ])
            ->add('save', 'submit')
            ->getForm();
    }

}