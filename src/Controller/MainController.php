<?php

namespace App\Controller;

use App\Entity\Execution;
use App\Entity\Habit;
use App\Form\HabitFormType;
use App\Interface\HabitServiceInterface;
use App\Interface\ExecutionServiceInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class MainController extends AbstractController
{
    /**
     * Habit service.
     */
    private HabitServiceInterface $habitService;
    /**
     * Execution service.
     */
    private ExecutionServiceInterface $executionService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;
    /**
     * Constructor.
     */
    public function __construct(HabitServiceInterface $habitService, TranslatorInterface $translator, ExecutionServiceInterface $executionService)
    {
        $this->habitService = $habitService;
        $this->executionService = $executionService;
        $this->translator = $translator;
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route(path: '/habits', name: 'app_main')]
    public function main(Request $request): Response
    {
        date_default_timezone_set('Europe/Warsaw');
        $user = $this->getUser();
        $date = new \DateTime();

        $todayHabits = $this->habitService->todayHabits($user);

        foreach($todayHabits as $habit){
            $last = $habit->getLastExecution();
            $suppoused = $this->habitService->checkStreak($habit);

            if( $last->format('Y-m-d') == $date->format('Y-m-d')){
            }
            else{
                if ($last->format('Y-m-d') != $suppoused->format('Y-m-d')){
                    $habit->setStreak(0);
                    $this->habitService->save($habit);
                }
            }
        }
        $date = new \DateTime();
        $date->format('Y-m-d');
        $habits = $this->habitService->findAll($user);

        $habit = new Habit();
        $create_form = $this->createForm(HabitFormType::class, $habit);
        $create = $create_form->createView();

        $date = new \DateTime();
        $user = $this->getUser();
        $todayHabits = $this->habitService->todayHabits($user);

        $doneHabits=0;
        foreach ($todayHabits as $habit) {
            $last= $habit-> getLastExecution();
            if($last->format('Y-m-d') == $date->format('Y-m-d')){
                $doneHabits = $doneHabits+1;
            }
        }

        $percentage= round(($doneHabits/count($todayHabits))*100);


        return $this->render('habits/index.html.twig', ['percentage'=> $percentage, 'todayHabits' => $todayHabits, 'date'=>$date, 'habits' => $habits, 'create'=>$create]);
    }

    /**
     * @param Request $request
     * @param Habit $habit
     * @return Response
     */

    #[Route(
        '/change/{id}',
        name: 'habit_change',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function change(Request $request, Habit $habit): Response
    {
        date_default_timezone_set('Europe/Warsaw');
        $user = $this->getUser();

        $lastExecution = $habit->getLastExecution();
        $lastExecution->format('Y-m-d');

        $date = new \DateTime();
        $date->format('Y-m-d');
        $timestamp = strtotime($date->format('d-m-Y'));
        $weekDay = date('l', $timestamp);

        if ($lastExecution->format('Y-m-d') != $date->format('Y-m-d')){

            $time = new \DateTime();

            $habit->setLastExecution($time);
            $streak=$habit->getStreak();
            $habit->setStreak($streak+1);
            $best_streak=$habit->getBestStreak();

            if($streak+1>=$best_streak){
                $habit->setBestStreak($streak+1);
            }
            $this->habitService->save($habit);



            $execution = new Execution();
            $execution->setHabit($habit);
            $execution->setUser($user);
            $execution->setDate($time);
            $this->executionService->save($execution);
        }
        else{
            $executions = $this->executionService->queryExecution($habit);
            foreach($executions as $execution) {
                $this->executionService->delete($execution);
            }

            $executions = $this->executionService->queryExecution($habit);
            foreach($executions as $execution) {
                $habit->setLastExecution($execution->getDate());
                $this->habitService->save($habit);
            }


            $streak=$habit->getStreak();
            if($streak>0){
                $habit->setStreak($streak-1);
                $this->habitService->save($habit);
            }

        }

        $date = new \DateTime();
        $user = $this->getUser();
        $todayHabits = $this->habitService->todayHabits($user);

        $doneHabits=0;
        foreach ($todayHabits as $habit) {
            $last= $habit-> getLastExecution();
            if($last->format('Y-m-d') == $date->format('Y-m-d')){
                $doneHabits = $doneHabits+1;
            }
        }

        $percentage= round(($doneHabits/count($todayHabits))*100);


        return $this->json(array('id'=> $habit->getId(), 'streak' => $habit->getStreak(), 'percentage'=>$percentage));

    }

    #[Route('/render_html', name: 'render_html', methods: 'GET|PUT|DELETE')]
    public function render_html(Request $request): Response

    {
        $date = new \DateTime();
        $user = $this->getUser();
        $habits = $this->habitService->findAll($user);
        foreach ($habits as $habit) {
            $deleteForm = $this->createForm(HabitFormType::class, $habit);
            $deleteForms[$habit->getId()] = $deleteForm->createView();
        }
        $todayHabits = $this->habitService->todayHabits($user);
        $doneHabits=0;
        foreach ($todayHabits as $habit) {
            $last= $habit-> getLastExecution();
            if($last->format('Y-m-d') == $date->format('Y-m-d')){
                $doneHabits = $doneHabits+1;
            }
        }

        $percentage= ($doneHabits/count($todayHabits))*100;

        $html=$this->renderView('habits/delete_habits.html.twig',  ['habits' => $habits,'deleteForms'=>$deleteForms ]);
        $data =array(

            'html'=>$html,
            'percentage'=>$percentage,


        );
        return new JsonResponse($data);
    }
}