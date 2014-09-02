<?php

namespace Application\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Application\Document\Category as CategoryEntity;

class CategoryRepository extends DocumentRepository
{

    public function findAll()
    {
        $dm = $this->getDocumentManager();
        return $dm->createQueryBuilder('Application\Document\Category')
            ->sort('name', 'asc')
            ->getQuery()
            ->execute();
    }

    public function insert(array $data)
    {

        $category = CategoryEntity::create($data);

        $dm = $this->getDocumentManager();
        $dm->persist($category);
        $dm->flush($category);

        return $category;
    }

}