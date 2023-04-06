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
     * @param Request $request
     *
     * @return Response
     */
    #[Route(path: '/habit_list', name: 'habit_list')]
    public function main(Request $request): Response
    {
        $user = $this->getUser();
        $habits = $this->habitService->findAll($user);

        $date = new \DateTime();
        $date->format('Y-m-d');

        foreach ($habits as $habit) {
            $editForm = $this->createForm(HabitFormType::class, $habit);
            $editForms[$habit->getId()] = $editForm->createView();
        }

        return $this->render('habits/habits.html.twig', ['habits' => $habits, 'date' => $date,  'editForms' => $editForms,]);
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


            return $this->redirectToRoute('app_main');
        }

        return $this->render('habits/create.html.twig', ['form' => $form->createView()]);
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

        $executions = $this->habitService->countAllExecutions($habit);
        $percentage = $this->habitService->countPercentage($habit);

        return $this->render('habits/show.html.twig', ['habit' => $habit, 'executions' => $executions, 'percentage' => $percentage]);
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
            [
                'method' => 'POST',
                'action' => $this->generateUrl('habit_edit', ['id' => $habit->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->habitService->save($habit);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')

            );
            $data =array(
                'id'=>$habit->getId(),
                'name'=>$habit->getName()
            );

            return new JsonResponse($data);
        }


        return new JsonResponse(['success' => false]);
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
        $form = $this->createForm(FormType::class, $habit, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('habit_delete', ['id' => $habit->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $executions=$this->executionService->queryAll($habit);
            foreach($executions as $execution){
                $this->executionService->delete($execution);
            }

            $this->habitService->delete($habit);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('app_main');
        }

        return $this->render(
            'habits/delete.html.twig',
            [
                'form' => $form->createView(),
                'habit' => $habit,
            ]
        );
    }

}
