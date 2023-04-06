<?php
/**
 * Execution service interface.
 */

namespace App\Interface;

use App\Entity\Execution;
use App\Entity\Habit;

/**
 * Interface ExecutionServiceInterface.
 */
interface ExecutionServiceInterface
{
    /**
     * save
     *
     * @param Execution $execution
     * @return void
     */
    public function save(Execution $execution): void;

    /**
     * delete
     *
     * @param Execution $execution
     * @return void
     */
    public function delete(Execution $execution): void;

    /**
     * queryAll
     *
     * @param Habit $habit
     * @return array
     */
    public function queryAll(Habit $habit): array;

    /**
     * countByHabit
     *
     * @param Habit $habit
     * @return int
     */
    public function countByHabit(Habit $habit): int;

    /**
     * queryExecution
     *
     * @param Habit $habit
     * @return array
     */
    public function queryExecution(Habit $habit): array;
}
