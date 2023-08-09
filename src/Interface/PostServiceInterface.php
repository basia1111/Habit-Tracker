<?php

namespace App\Interface;

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
}
