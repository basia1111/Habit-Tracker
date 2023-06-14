<?php

namespace App\Repository;

use App\Entity\Habit;
use App\Entity\User;
use App\Interface\ExecutionServiceInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Habit>
 *
 * @method Habit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Habit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Habit[]    findAll()
 * @method Habit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HabitRepository extends ServiceEntityRepository
{
    /**
     * Execution service.
     */
    private ExecutionServiceInterface $executionService;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Habit::class);
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('habit');
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
     * @param Habit $habit
     * @return void
     */
    public function delete(Habit $habit): void
    {
        $this->_em->remove($habit);
        $this->_em->flush();
    }

    /**
     * Query all user habits.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(User $user): array
    {
        $query = $this->createQueryBuilder('habit')
            ->orderBy('habit.id', 'DESC')
            ->andWhere('habit.user = :user')
            ->setParameter('user', $user)
            ->getQuery();

        $results = $query->getResult();

        return $results;
    }


    /**
     * Find habit by id.
     *
     * @param int $id
     * @return array
     */
    public function findAllId(int $id): array
    {
        $query = $this->createQueryBuilder('habit')
            ->orderBy('habit.id', 'DESC')
            ->andWhere('habit.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        $results = $query->getResult();

        return $results;
    }
    public function findAllOrderedByTime(User $user):array
    {
        $query = $this->createQueryBuilder('habit')
            ->orderBy('habit.time', 'ASC')
            ->andWhere('habit.user = :user')
            ->setParameter('user', $user)
            ->getQuery();

        $results = $query->getResult();

        return $results;

    }


}
