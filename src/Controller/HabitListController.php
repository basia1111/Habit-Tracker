<?php

namespace App\Controller;

use App\Entity\Execution;
use App\Entity\Habit;
use App\Form\HabitFormType;
use App\Interface\HabitServiceInterface;
use App\Interface\ExecutionServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class HabitListController extends AbstractController
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
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(
        '/create',
        name: 'habit_create',
        methods: 'GET|POST',
    )]
    public function create(Request $request): Response
    {
        $user = $this->getUser();

        $habit = new Habit();
        $form = $this->createForm(HabitFormType::class, $habit);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $habit->setStreak('0');
            $habit->setBestStreak('0');
            $habit->setUser($user);
            $habit->setCreatedAt(new \DateTimeImmutable());
            $habit->setLastExecution(new \DateTimeImmutable('2000-00-00 00:00:00'));
            $this->habitService->save($habit);

            $execution = new Execution();
            $execution->setHabit($habit);
            $execution->setUser($user);
            $execution->setDate(new \DateTimeImmutable('2000-00-00 00:00:00'));
            $this->executionService->save($execution);


            $today = new \DateTimeImmutable();
            $timestamp = strtotime($today->format('d-m-Y'));
            $weekDay = date('l', $timestamp);
            $day=0;

            if ('Monday' == $weekDay && $habit->isMonday()) {
                $day='1';
            }
            if ('Tuesday' == $weekDay && $habit->isTusday()) {

                $day='1';
            }
            if ('Wednesday' == $weekDay && $habit->isWednesday()) {

                $day='1';
            }
            if ('Thursday' == $weekDay && $habit->isThursday()) {

                $day='1';
            }
            if ('Friday' == $weekDay && $habit->isFriday()) {

                $day='1';
            }
            if ('Saturday' == $weekDay && $habit->isSathurday()) {

                $day='1';
            }
            if ('Sunday' == $weekDay && $habit->isSunday()) {
                $day='1';
            }

            $user = $this->getUser();
            $todayHabits = $this->habitService->todayHabits($user);
            $habits = $this->habitService->findAll($user);
            $html = $this->renderView('main/rerendered/today_habits.html.twig',  ['todayHabits' => $todayHabits, 'date'=> $today]);
            $habits=$this->renderView('main/rerendered/all_habits.html.twig',  ['habits' => $habits,]);

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
            if(count($todayHabits)>0) {
                $percentage= round(($doneHabits/count($todayHabits))*100);
            }
            else{
                $percentage=0;
            }
            $all_habits = $this->habitService->findAll($user);
            $number_of_habits= count($all_habits);

            $data =array(
                'id'=>$habit->getId(),
                'name'=>$habit->getName(),
                'image'=> $habit->getImage(),
                'day'=>$day,
                'html'=>$html,
                'habits'=>$habits,
                'percentage'=>$percentage,
                'number'=>$number_of_habits,

            );

            return new JsonResponse($data);
        }

        return $this->redirectToRoute('app_main');
    }

    /**
     * Show action.
     *
     * @param Habit $habit Habit
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'habit_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function show(Habit $habit): Response
    {
        if ($habit->getUser() !== $this->getUser()) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.record_not_found')
            );

            return $this->redirectToRoute('task_index');
        }

        $edit_form = $this->createForm(
            HabitFormType::class,
            $habit,

        );
        $form=$edit_form->createView();
        $executions = $this->habitService->countAllExecutions($habit);
        $percentage = $this->habitService->countPercentage($habit);

        return $this->render('show/show.html.twig', ['habit' => $habit, 'executions' => $executions, 'percentage' => $percentage,'form'=>$form]);
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Habit   $habit   habit entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'habit_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT|POST')]
    public function edit(Request $request, Habit $habit): Response
    {
        if ($habit->getUser() !== $this->getUser()) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.record_not_found')
            );

            return $this->redirectToRoute('task_index');
        }
        $form = $this->createForm(
            HabitFormType::class,
            $habit,

        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->habitService->save($habit);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')

            );
            $user = $this->getUser();
            $todayHabits = $this->habitService->todayHabits($user);

            $data =array(
                'id'=>$habit->getId(),
                'name'=>$habit->getName(),
                'icon'=>$habit->getImage(),
                'monday'=>$habit->isMonday(),
                'tusday'=>$habit->isTusday(),
                'wednesday'=>$habit->isWednesday(),
                'thursday'=>$habit->isThursday(),
                'friday'=>$habit->isFriday(),
                'sunday'=>$habit->isSunday(),
                'sathurday'=>$habit->isSathurday(),
                'percentage'=>$this->habitService->countPercentage($habit)
            );

            return new JsonResponse($data);
        }


        return $this->redirectToRoute('app_main');
    }

    /**
     * delete action.
     *
     * @param Request $request HTTP request
     * @param Habit   $habit   habit entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'habit_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT|DELETE')]
    public function delete(Request $request, Habit $habit): Response
    {
        if ($habit->getUser() !== $this->getUser()) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.record_not_found')
            );

            return $this->redirectToRoute('app_main');
        }
            $executions=$this->executionService->queryAll($habit);
            if (count($executions) < 1){
                return $this->redirectToRoute('app_main');
            }
            foreach($executions as $execution){
                $this->executionService->delete($execution);
            }

            $this->habitService->delete($habit);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            $today = new \DateTimeImmutable();
            $user = $this->getUser();
            $todayHabits = $this->habitService->todayHabits($user);
            $habits = $this->habitService->findAll($user);
            $html = $this->renderView('main/rerendered/today_habits.html.twig',  ['todayHabits' => $todayHabits, 'date'=> $today]);
            $habits=$this->renderView('main/rerendered/all_habits.html.twig',  ['habits' => $habits,]);

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
        $all_habits = $this->habitService->findAll($user);
        $number_of_habits= count($all_habits);

        if(count($todayHabits)>0) {
            $percentage= round(($doneHabits/count($todayHabits))*100);
        }
        else{
            $percentage=0;
        }
            $data =array(
                'html'=>$html,
                'habits'=>$habits,
                'percentage'=>$percentage,
                'number'=>$number_of_habits,
            );

            return new JsonResponse($data);

    }
    #[Route(
        '/dates/{id}/{month}',
        name: 'habit_dates',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function dates(Habit $habit, $month): Response{

        $allExecutions= $this->executionService->queryAll($habit);
        $dates = array();

        for($i=0; $i<count($allExecutions); $i++){
             $date=$allExecutions[$i]->getDate();
             $monthE = $date->format('m');
             if( $month  == $monthE){
                 array_push($dates,$date->format('j') );
             }
        }
        $response=array(
            'dates' => $dates
        );

        return new JsonResponse($response);
    }

}