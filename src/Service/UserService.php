<?php
/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * User service.
 */

namespace App\Service;

use App\Entity\User;
use App\Interface\UserServiceInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\QueryBuilder;

set_time_limit(0);
/**
 * Class UserService.
 */
class UserService implements UserServiceInterface
{
    /**
     * User repository.
     */
    private UserRepository $userRepository;

    /**
     * Constructor.
     *
     * @param UserRepository $userRepository user repository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    /**
     * Save entity.
     *
     * @param User $user User entity
     */
    public function save($user): void
    {
        $this->userRepository->save($user);
    }

    /**
     * Delete entity.
     *
     * @param User $user User entity
     */
    public function delete($user): void
    {
        $this->userRepository->delete($user);
    }

    /**
     * Find All.
     */
    public function findAll(): array
    {
        return $this->userRepository-> queryAll();

    }
}
