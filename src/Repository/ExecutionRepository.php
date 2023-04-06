<?php

namespace App\Repository;

use App\Entity\Execution;
use App\Entity\Habit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Execution>
 *
 * @method Execution|null find($id, $lockMode = null, $lockVersion = null)
 * @method Execution|null findOneBy(array $criteria, array $orderBy = null)
 * @method Execution[]    findAll()
 * @method Execution[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExecutionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Execution::class);
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
        return $queryBuilder ?? $this->createQueryBuilder('execution');
    }


    public function save(Execution $execution): void
    {
        $this->_em->persist($execution);
        $this->_em->flush();
    }

    public function delete(Execution $execution): void
    {
        $this->_em->remove($execution);
        $this->_em->flush();
    }

    /**
     * Query all executions connected to habit.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(Habit $habit): array
    {
        $query = $this->createQueryBuilder('execution')
            ->orderBy('execution.id', 'DESC')
            ->andWhere('execution.habit = :habit')
            ->setParameter('habit', $habit)
            ->getQuery();

        $results = $query->getResult();

        return $results;
    }

    /**
     *  Query latest execution connected to habit.
     *
     * @param Habit $habit
     * @return array
     */
    public function queryExecution(Habit $habit): array
    {
        $query = $this->createQueryBuilder('execution')
            ->orderBy('execution.id', 'DESC')
            ->andWhere('execution.habit = :habit')
            ->setParameter('habit', $habit)
            ->setMaxResults(1)
            ->getQuery();

        $results = $query->getResult();

        return $results;
    }

    /**
     * Count all executions connected to habit.
     *
     * @param Habit $habit
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countByHabit(Habit $habit): int
    {
        $qb = $this->getOrCreateQueryBuilder();

        return $qb->select($qb->expr()->countDistinct('execution.id'))
            ->where('execution.habit = :habit')
            ->setParameter('habit', $habit)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
