<?php
/**
 * Habit service interface.
 */

namespace App\Interface;

use App\Entity\Habit;
use App\Entity\User;

/**
 * Interface HabitServiceInterface.
 */
interface HabitServiceInterface
{
    /**
     * findAll
     *
     * @param User $user
     * @return array
     */
    public function findAll(User $user): array;

    /**
     * findAllId
     *
     * @param int $user
     * @return array
     */
    public function findAllId(int $user): array;

    /**
     * save
     *
     * @param Habit $habit
     * @return void
     */
    public function save(Habit $habit): void;

    /**
     * delete
     *
     * @param Habit $habit
     * @return void
     */
    public function delete(Habit $habit): void;

    /**
     * countAllExecutions
     *
     * @param Habit $habit
     * @return int
     */
    public function countAllExecutions(Habit $habit): int;

    /**
     * countPercentage
     *
     * @param Habit $habit
     * @return int
     */
    public function countPercentage(Habit $habit): int;

    /**
     * todayHabits
     *
     * @param User $user
     * @return array
     */
    public function todayHabits(User $user): array;

    /**
     * checkStreak
     *
     * @param $habit
     * @return mixed
     */
    public function checkStreak($habit);
}
