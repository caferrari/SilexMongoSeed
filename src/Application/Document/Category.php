<?php

namespace Application\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(collection="category", repositoryClass="Application\Repository\Category")
 */
class Category
{

    /** @ODM\Id */
    private $id;

    /** @ODM\String */
    private $name = '';

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param array $data
     * @return \Application\Document\Category
     */
    public function exchangeArray(array $data)
    {
        $this->setName($data['name']);
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }

    /**
     * @param array $data
     * @return \Application\Document\Category
     */
    public static function create(array $data)
    {
        $category = new Category;
        $category->exchangeArray($data);
        return $category;
    }

}
