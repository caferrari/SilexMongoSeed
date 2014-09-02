<?php

namespace Application\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ODM\Document(collection="user", repositoryClass="Application\Repository\UserRepository")
 * @ODM\HasLifecycleCallbacks
 */
class User
{
    /** @ODM\Id */
    private $id;

    /** @ODM\String
     */
    private $name;

    /** @ODM\String
     *  @ODM\Index(unique=true, order="asc")
     */
    private $email;

    /** @ODM\String */
    private $password;

    /** @ODM\Date
     *  @ODM\Index(order="desc")
     */
    private $joinDate;

    /** @ODM\String */
    private $role;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @return bool
     */
    public function isGuest()
    {
        return 'guest' == $this->role;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return 'admin' == $this->role;
    }

    /**
     * @ODM\PrePersist
     */
    public function initialize()
    {
        if (null == $this->role) {
            $this->role = 'admin';
        }
        $this->joinDate = new \DateTime;
        $this->password = password_hash($this->password, PASSWORD_BCRYPT, ['cost' => 14]);
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param $password
     * @return bool
     */
    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }

    /**
     * @param array $data
     * @return $this
     */
    public function exchangeArray(array $data)
    {
        $this->setName($data['name']);
        $this->setEmail($data['email']);
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'email' => $this->email,
            'role'  => $this->role,
            'admin' => $this->isAdmin()
        ];
    }

    /**
     * @param array $data
     * @return $this
     */
    public static function create(array $data) {
        $user = new User;
        $user->exchangeArray($data);
        return $user;
    }

    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('email', new Assert\Email());
        $metadata->addPropertyConstraint('password', new Assert\Length(array('min' => 6)));
    }

}
