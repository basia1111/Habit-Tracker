<?php
/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\Habit;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * HabitRepository.
 *
 * @method Habit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Habit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Habit[]    findAll()
 * @method Habit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

/**
 * Habit Repository.
 */
class HabitRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Habit::class);
    }

    /**
     * Save entity.
     *
     * @param Habit $habit Habit entity
     */
    public function save(Habit $habit): void
    {
        $this->_em->persist($habit);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Habit $habit Habit entity
     */
    public function delete(Habit $habit): void
    {
        $this->_em->remove($habit);
        $this->_em->flush();
    }

    /**
     * Query all user habits.
     *
     * @param User $user Habit entity
     */
    public function queryAll(User $user): array
    {
        $query = $this->createQueryBuilder('habit')
            ->orderBy('habit.id', 'DESC')
            ->andWhere('habit.user = :user')
            ->setParameter('user', $user)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * Query all user habits with set time.
     *
     * @param User $user Habit entity
     */
    public function queryAllOrderedByTime(User $user): array
    {
        $query = $this->createQueryBuilder('habit')
            ->orderBy('habit.time', 'ASC')
            ->andWhere('habit.user = :user')
            ->andWhere('habit.time != :time')
            ->setParameter('user', $user)
            ->setParameter('time', '00:00:00')
            ->getQuery();

        return $query->getResult();
    }
    /**
     * Query all user habits without set time.
     *
     * @param User $user Habit entity
     */
    public function queryAllWithoutTime(User $user): array
    {
        $query = $this->createQueryBuilder('habit')
            ->orderBy('habit.time', 'ASC')
            ->andWhere('habit.user = :user')
            ->andWhere('habit.time = :time')
            ->setParameter('user', $user)
            ->setParameter('time', '00:00:00')
            ->getQuery();

        return $query->getResult();
    }

    /**
     * Find habit by id.
     *
     * @param int $id user Id
     */
    public function findOneById(int $id): Habit
    {
        $query = $this->createQueryBuilder('habit')
            ->orderBy('habit.id', 'DESC')
            ->andWhere('habit.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        return $query->getResult();
    }
}
