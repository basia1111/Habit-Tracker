<?php
/**
 * Execution service.
 */

namespace App\Service;

use App\Entity\Execution;
use App\Entity\Habit;
use App\Interface\ExecutionServiceInterface;
use App\Repository\ExecutionRepository;

/**
 * Class ExecutionService.
 */
class ExecutionService implements ExecutionServiceInterface
{
    /**
     * Execution repository.
     */
    private ExecutionRepository $executionRepository;

    /**
     * Constructor.
     *
     * @param ExecutionRepository $executionRepository execution repository
     */
    public function __construct(ExecutionRepository $executionRepository)
    {
        $this->executionRepository = $executionRepository;
    }

    /**
     * Save entity.
     *
     * @param Execution $execution Execution entity
     */
    public function save(Execution $execution): void
    {
        $this->executionRepository->save($execution);
    }

    /**
     * Delete entity.
     *
     * @param Execution $execution Execution entity
     */
    public function delete(Execution $execution): void
    {
        $this->executionRepository->delete($execution);
    }

    /**
     * Find all executions connected to habit.
     *
     * @param Habit $habit
     * @return array
     */
    public function queryAll(Habit $habit): array
    {
        return $this->executionRepository->queryAll($habit);
    }

    /**
     * Count all executions of habit.
     *
     * @param Habit $habit
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countByHabit(Habit $habit): int
    {
        return $this->executionRepository->countByHabit($habit);
    }

    /**
     * Find last habit execution;
     *
     * @param Habit $habit
     * @return array
     */
    public function queryExecution(Habit $habit): array
    {
        return $this->executionRepository->queryExecution($habit);
    }


}
