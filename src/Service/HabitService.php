<?php

/**
 * habit service.
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
     * Task repository.
     */
    private HabitRepository $habitRepository;
    /**
     * Execution service.
     */
    private ExecutionServiceInterface $executionService;

    /**
     * Constructor.
     *
     * @param HabitRepository $habitRepository habit repository
     */
    public function __construct(HabitRepository $habitRepository, ExecutionServiceInterface $executionService)
    {
        $this->habitRepository = $habitRepository;
        $this->executionService = $executionService;
    }

    public function findAll(User $user): array
    {
        return $this->habitRepository->queryAll($user);
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
     * Count habit executions.
     *
     * @param Habit $habit
     * @return int
     */
    public function countAllExecutions(Habit $habit): int
    {
        $executions = $this->executionService->countByHabit($habit);

        return $executions;
    }

    /**
     * Count percentage of execution.
     *
     * @param Habit $habit
     * @return int
     */
    public function countPercentage(Habit $habit): int
    {
        $executions = $this->executionService->countByHabit($habit);

        $startDate = $habit->getCreatedAt();
        $endDate = new \DateTimeImmutable();

        $resultDays = ['Monday' => 0,
            'Tuesday' => 0,
            'Wednesday' => 0,
            'Thursday' => 0,
            'Friday' => 0,
            'Saturday' => 0,
            'Sunday' => 0,
        ];


        while ($startDate <= $endDate) {

            $timestamp = strtotime($startDate->format('d-m-Y'));

            $weekDay = date('l', $timestamp);
            $resultDays[$weekDay] = $resultDays[$weekDay] + 1;

            $startDate->modify('+1 day');
        }

        $totalExecutions = 0;
        if ($habit->isMonday()) {
            $totalExecutions += $resultDays['Monday'];
        }
        if ($habit->isTusday()) {
            $totalExecutions += $resultDays['Tuesday'];
        }
        if ($habit->isWednesday()) {
            $totalExecutions += $resultDays['Wednesday'];
        }
        if ($habit->isThursday()) {
            $totalExecutions += $resultDays['Thursday'];
        }
        if ($habit->isFriday()) {
            $totalExecutions += $resultDays['Friday'];
        }
        if ($habit->isSathurday()) {
            $totalExecutions += $resultDays['Saturday'];
        }
        if ($habit->isSunday()) {
            $totalExecutions += $resultDays['Sunday'];
        }

        if ($executions > 0 && $totalExecutions>0 ) {
           $percentage = (($executions-1) / $totalExecutions) * 100;
        } else {
           $percentage = 0;
       }

        return $percentage;
    }

    /**
     * Find habits that should be executed today.
     *
     * @param User $user
     * @return array
     */
    public function todayHabits(User $user): array
    {
        $todayHabits = [];

        $today = new \DateTimeImmutable();
        $timestamp = strtotime($today->format('d-m-Y'));
        $weekDay = date('l', $timestamp);

        $habits = $this->habitRepository->queryAll($user);

        foreach ($habits as $habit) {
            if ('Monday' == $weekDay && $habit->isMonday()) {
                array_push($todayHabits, $habit);
            }
            if ('Tuesday' == $weekDay && $habit->isTusday()) {
                array_push($todayHabits, $habit);
            }
            if ('Wednesday' == $weekDay && $habit->isWednesday()) {
                array_push($todayHabits, $habit);
            }
            if ('Thursday' == $weekDay && $habit->isThursday()) {
                array_push($todayHabits, $habit);
            }
            if ('Friday' == $weekDay && $habit->isFriday()) {
                array_push($todayHabits, $habit);
            }
            if ('Saturday' == $weekDay && $habit->isSathurday()) {
                array_push($todayHabits, $habit);
            }
            if ('Sunday' == $weekDay && $habit->isSunday()) {
                array_push($todayHabits, $habit);
            }
        }

        return $todayHabits;
    }

    /**
     * Find habit by id.
     *
     * @param int $id
     * @return array
     */
    public function findAllId(int $id): array
    {
        return $this->habitRepository->findAllId($id);
    }


    /**
     * Find date when habit should've been recently executed.
     * @param $habit
     * @return \DateTimeImmutable|false|int
     */
    public function checkStreak($habit)
    {
        $weekdays = ['Monday' => 0,
            'Tuesday' => 0,
            'Wednesday' => 0,
            'Thursday' => 0,
            'Friday' => 0,
            'Saturday' => 0,
            'Sunday' => 0,
        ];


        if ( $habit->isMonday()) {
            $weekdays['Monday']=1;
        }
        if ( $habit->isTusday()) {
            $weekdays['Tuesday']=1;
        }
        if ( $habit->isWednesday()) {
            $weekdays['Wednesday']=1;
        }
        if ( $habit->isThursday()) {
            $weekdays['Thursday']=1;
        }
        if ( $habit->isFriday()) {
            $weekdays['Friday']=1;
        }
        if ( $habit->isSathurday()) {
            $weekdays['Saturday']=1;
        }
        if ( $habit->isSunday()) {
            $weekdays['Sunday']=1;
        }

        $startDate=new \DateTimeImmutable();
        $recent=0;
        $i=0;

        while ($i<7){
            $startDate=$startDate->modify('-1 day');
            $timestamp = strtotime($startDate->format('d-m-Y'));
            $weekDay = date('l', $timestamp);

            if($weekdays[$weekDay] == 1){
                $recent=$startDate;
                break;
            }
             $i=$i+1;
        }
        return $recent;
    }
}
