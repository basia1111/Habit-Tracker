<?php

namespace App\Interface;

use App\Entity\Post;

/**
 * Interface PostServiceInterface.
 */
interface PostServiceInterface
{
    /**
     * save.
     */
    public function save($post): void;

    /**
     * delete.
     */
    public function delete($post): void;

    /**
     * findAll.
     */
    public function findAll(): array;
    /**
     * find.
     */
    public function find($id): Post;
}
