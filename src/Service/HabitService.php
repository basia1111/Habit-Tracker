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
 * Habit service.
 */

namespace App\Service;

use App\Entity\Habit;
use App\Entity\User;
use App\Interface\ExecutionServiceInterface;
use App\Interface\HabitServiceInterface;
use App\Repository\HabitRepository;

set_time_limit(0);
/**
 * Class HabitService.
 */
class HabitService implements HabitServiceInterface
{
    /**
     * Habit repository.
     */
    private HabitRepository $habitRepository;
    /**
     * Execution service.
     */
    private ExecutionServiceInterface $executionService;

    /**
     * Constructor.
     *
     * @param HabitRepository           $habitRepository  habit repository
     * @param ExecutionServiceInterface $executionService execution Service
     */
    public function __construct(HabitRepository $habitRepository, ExecutionServiceInterface $executionService)
    {
        $this->habitRepository = $habitRepository;
        $this->executionService = $executionService;
    }

    /**
     * Save entity.
     *
     * @param Habit $habit Habit entity
     */
    public function save(Habit $habit): void
    {
        $this->habitRepository->save($habit);
    }

    /**
     * Delete entity.
     *
     * @param Habit $habit Habit entity
     */
    public function delete(Habit $habit): void
    {
        $this->habitRepository->delete($habit);
    }

    /**
     * FindAll.
     *
     * @param User $user User entity
     */
    public function findAll(User $user): array
    {
        return $this->habitRepository->queryAll($user);
    }

    /**
     * Find habit by id.
     *
     * @param int $id Id
     */
    public function findOneById(int $id): Habit
    {
        return $this->habitRepository->findOneById($id);
    }

    /**
     * Count habit executions.
     *
     * @param Habit $habit Habit entity
     */
    public function countAllExecutions(Habit $habit): int
    {
        return $this->executionService->countByHabit($habit);
    }

    /**
     * Count habit success rate.
     *
     * @param Habit $habit Habit entity
     */
    public function habitSuccessRate(Habit $habit): int
    {
        $executions = $this->executionService->countByHabit($habit);

        $startDate = $habit->getCreatedAt();
        $endDate = new \DateTimeImmutable();

        $days = ['Monday' => 0,
            'Tuesday' => 0,
            'Wednesday' => 0,
            'Thursday' => 0,
            'Friday' => 0,
            'Saturday' => 0,
            'Sunday' => 0,
        ];

        /* count all days between the date of creating habit and today */
        while ($startDate <= $endDate) {
            $timestamp = strtotime($startDate->format('d-m-Y'));
            $weekDay = date('l', $timestamp);
            $days[$weekDay] = $days[$weekDay] + 1;
            $startDate->modify('+1 day');
        }

        /* count all days on which habit should've been performed */
        $totalExecutions = 0;
        if ($habit->isMonday()) {
            $totalExecutions += $days['Monday'];
        }
        if ($habit->isTusday()) {
            $totalExecutions += $days['Tuesday'];
        }
        if ($habit->isWednesday()) {
            $totalExecutions += $days['Wednesday'];
        }
        if ($habit->isThursday()) {
            $totalExecutions += $days['Thursday'];
        }
        if ($habit->isFriday()) {
            $totalExecutions += $days['Friday'];
        }
        if ($habit->isSathurday()) {
            $totalExecutions += $days['Saturday'];
        }
        if ($habit->isSunday()) {
            $totalExecutions += $days['Sunday'];
        }

        if ($executions > 0 && $totalExecutions > 0) {
            $percentage = (($executions - 1) / $totalExecutions) * 100;
        } else {
            $percentage = 0;
        }

        return $percentage;
    }

    /**
     * Find habits that should be executed today.
     *
     * @param User $user User entity
     */
    public function todayHabits(User $user): array
    {
        $todayHabits = [];

        $today = new \DateTimeImmutable();
        $timestamp = strtotime($today->format('d-m-Y'));
        $weekDay = date('l', $timestamp);

        $habits = $this->habitRepository->queryAll($user);

        foreach ($habits as $habit) {
            if ('Monday' === $weekDay && $habit->isMonday()) {
                $todayHabits[] = $habit;
            }
            if ('Tuesday' === $weekDay && $habit->isTusday()) {
                $todayHabits[] = $habit;
            }
            if ('Wednesday' === $weekDay && $habit->isWednesday()) {
                $todayHabits[] = $habit;
            }
            if ('Thursday' === $weekDay && $habit->isThursday()) {
                $todayHabits[] = $habit;
            }
            if ('Friday' === $weekDay && $habit->isFriday()) {
                $todayHabits[] = $habit;
            }
            if ('Saturday' === $weekDay && $habit->isSathurday()) {
                $todayHabits[] = $habit;
            }
            if ('Sunday' === $weekDay && $habit->isSunday()) {
                $todayHabits[] = $habit;
            }
        }

        return $todayHabits;
    }

    /**
     * Find last date when habit should've been performed.
     */
    public function findLastDate($habit): \DateTimeImmutable|int
    {
        $weekdays = ['Monday' => 0,
            'Tuesday' => 0,
            'Wednesday' => 0,
            'Thursday' => 0,
            'Friday' => 0,
            'Saturday' => 0,
            'Sunday' => 0,
        ];

        if ($habit->isMonday()) {
            $weekdays['Monday'] = 1;
        }
        if ($habit->isTusday()) {
            $weekdays['Tuesday'] = 1;
        }
        if ($habit->isWednesday()) {
            $weekdays['Wednesday'] = 1;
        }
        if ($habit->isThursday()) {
            $weekdays['Thursday'] = 1;
        }
        if ($habit->isFriday()) {
            $weekdays['Friday'] = 1;
        }
        if ($habit->isSathurday()) {
            $weekdays['Saturday'] = 1;
        }
        if ($habit->isSunday()) {
            $weekdays['Sunday'] = 1;
        }

        $startDate = new \DateTimeImmutable();
        $recent = 0;
        $i = 0;

        while ($i < 7) {
            $startDate = $startDate->modify('-1 day');
            $timestamp = strtotime($startDate->format('Y-m-d'));
            $weekDay = date('l', $timestamp);

            if (1 === $weekdays[$weekDay]) {
                $recent = $startDate;
                break;
            }
            $i = $i + 1;
        }

        return $recent;
    }

    /**
     * Make an array of all habits for each day of the week.
     *
     * @param User $user User entity
     */
    public function createWeek(User $user): array
    {
        $week = [
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],


        ];
        $habits = $this->habitRepository->queryAllOrderedByTime($user);
        foreach ($habits as $habit) {
            if ($habit->isMonday()) {
                $week[0][] = $habit;
            }
            if ($habit->isTusday()) {
                $week[1][] = $habit;
            }
            if ($habit->isWednesday()) {
                $week[2][] = $habit;
            }
            if ($habit->isThursday()) {
                $week[3][] = $habit;
            }
            if ($habit->isFriday()) {
                $week[4][] = $habit;
            }
            if ($habit->isSathurday()) {
                $week[5][] = $habit;
            }
            if ($habit->isSunday()) {
                $week[6][] = $habit;
            }
        }
        $habitsUnscheduled = $this->habitRepository->queryAllWithoutTime($user);
        foreach ($habitsUnscheduled as $habit) {
            if ($habit->isMonday()) {
                $week[7][] = $habit;
            }
            if ($habit->isTusday()) {
                $week[8][] = $habit;
            }
            if ($habit->isWednesday()) {
                $week[9][] = $habit;
            }
            if ($habit->isThursday()) {
                $week[10][] = $habit;
            }
            if ($habit->isFriday()) {
                $week[11][] = $habit;
            }
            if ($habit->isSathurday()) {
                $week[12][] = $habit;
            }
            if ($habit->isSunday()) {
                $week[13][] = $habit;
            }
        }

        return $week;
    }

    /**
     * Today Success Rate.
     */
    public function todaySuccessRate($user): int
    {
        $date = new \DateTime();
        $todayHabits = $this->todayHabits($user);
        $doneHabits = 0;
        foreach ($todayHabits as $habit) {
            $last = $habit->getLastExecution();
            if ($last->format('Y-m-d') === $date->format('Y-m-d')) {
                $doneHabits = $doneHabits + 1;
            }
        }

        if (count($todayHabits) > 0) {
            $percentage = round(($doneHabits / count($todayHabits)) * 100);
        } else {
            $percentage = 0;
        }

        return $percentage;
    }

    /**
     * Check Streak.
     *
     * @return void
     */
    public function checkStreak($todayHabits)
    {
        $date = new \DateTime();
        foreach ($todayHabits as $habit) {
            $last = $habit->getLastExecution();
            $supposed = $this->findLastDate($habit);

            if ($last->format('Y-m-d') === $date->format('Y-m-d')) {
                continue;
            } else {
                if ($last->format('Y-m-d') !== $supposed->format('Y-m-d')) {
                    $habit->setStreak(0);
                    $this->save($habit);
                }
            }
        }
    }
}
