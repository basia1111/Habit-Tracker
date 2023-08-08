<?php

namespace App\Interface;

/**
 * Interface UserServiceInterface.
 */
interface UserServiceInterface
{
    /**
     * save.
     */
    public function save($user): void;

    /**
     * delete.
     */
    public function delete($user): void;

    /**
     * findAll.
     */
    public function findAll(): array;
}
