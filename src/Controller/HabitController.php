<?php
/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Execution;
use App\Entity\Habit;
use App\Form\HabitFormType;
use App\Interface\ExecutionServiceInterface;
use App\Interface\HabitServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Habit ListController.
 */
class HabitController extends AbstractController
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
     *
     * @param HabitServiceInterface     $habitService     habit Service
     * @param TranslatorInterface       $translator       translator
     * @param ExecutionServiceInterface $executionService execution Service
     */
    public function __construct(HabitServiceInterface $habitService, TranslatorInterface $translator, ExecutionServiceInterface $executionService)
    {
        $this->habitService = $habitService;
        $this->executionService = $executionService;
        $this->translator = $translator;
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

            return $this->redirectToRoute('app_main');
        }

        $edit_form = $this->createForm(
            HabitFormType::class,
            $habit,
        );
        $executions = $this->habitService->countAllExecutions($habit);
        $percentage = $this->habitService->habitSuccessRate($habit);

        return $this->render('show/show.html.twig', ['habit' => $habit, 'executions' => $executions, 'percentage' => $percentage, 'form' => $edit_form->createView()]);
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

            return $this->redirectToRoute('app_main');
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

            $data = [
                'id' => $habit->getId(),
                'name' => $habit->getName(),
                'icon' => $habit->getImage(),
                'monday' => $habit->isMonday(),
                'tusday' => $habit->isTusday(),
                'wednesday' => $habit->isWednesday(),
                'thursday' => $habit->isThursday(),
                'friday' => $habit->isFriday(),
                'sunday' => $habit->isSunday(),
                'sathurday' => $habit->isSathurday(),
                'percentage' => $this->habitService->habitSuccessRate($habit),
            ];

            return new JsonResponse($data);
        }

        return $this->redirectToRoute('app_main');
    }

    #[Route(
        '/dates/{id}/{month}',
        name: 'habit_dates',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function dates(Habit $habit, $month): Response
    {
        $allExecutions = $this->executionService->queryAll($habit);
        $dates = [];

        for ($i = 0; $i < count($allExecutions); ++$i) {
            $date = $allExecutions[$i]->getDate();
            $monthE = $date->format('m');
            if ($month == $monthE) {
                $dates[] = $date->format('j');
            }
        }
        $response = [
            'dates' => $dates,
        ];

        return new JsonResponse($response);
    }
}
