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
     * save.
     */
    public function save(Execution $execution): void;

    /**
     * delete.
     */
    public function delete(Execution $execution): void;

    /**
     * queryAll.
     */
    public function queryAll(Habit $habit): array;

    /**
     * countByHabit.
     */
    public function countByHabit(Habit $habit): int;

    /**
     * queryExecution.
     */
    public function queryLastExecution(Habit $habit): array;
}
