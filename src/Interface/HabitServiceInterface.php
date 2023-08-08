<?php

namespace App\Interface;

use App\Entity\Habit;
use App\Entity\User;

/**
 * Interface HabitServiceInterface.
 */
interface HabitServiceInterface
{
    /**
     * save.
     */
    public function save(Habit $habit): void;

    /**
     * delete.
     */
    public function delete(Habit $habit): void;

    /**
     * findAll.
     */
    public function findAll(User $user): array;

    /**
     * findOneById.
     */
    public function findOneById(int $id): Habit;

    /**
     * countAllExecutions.
     */
    public function countAllExecutions(Habit $habit): int;

    /**
     * countPercentage.
     */
    public function habitSuccessRate(Habit $habit): int;

    /**
     * todayHabits.
     */
    public function todayHabits(User $user): array;

    /**
     * checkStreak.
     */
    public function findLastDate(Habit $habit): \DateTimeImmutable|int;

    /**
     * createWeek.
     */
    public function createWeek(User $user): array;

    public function todaySuccessRate($user): int;

    public function checkStreak($todayHabits);
}
