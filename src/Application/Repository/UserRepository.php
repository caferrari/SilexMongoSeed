<?php

namespace Application\Repository;

use Application\Document\User;
use Doctrine\ODM\MongoDB\DocumentRepository;

class UserRepository extends DocumentRepository
{

    public function insert(User $user)
    {
        $dm = $this->getDocumentManager();
        $dm->persist($user);
        $dm->flush($user);
    }

    public function authenticate(array $data)
    {
        $dm = $this->getDocumentManager();
        $user = $this->findOneByEmail($data['email']);

        if (!$user) {
            return false;
        }

        if (!$user->verifyPassword($data['password'])) {
            return false;
        }

        return $user;
    }

}
