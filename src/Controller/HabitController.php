<?php
/**
 * Task controller.
 */

namespace App\Controller;

use App\Entity\Habit;
use App\Repository\HabitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HabitController.
 */
#[Route('/habits')]
class TaskController extends AbstractController
{
    /**
     * Habit repository.
     *
     * @var HabitRepository
     */
    private HabitRepository $habitRepository;


    /**
     * Constructor.
     *
     * @param HabitRepository     $habitRepository Habit repository
     */
    public function __construct(HabitRepository $habitRepository)
    {
        $this->habitRepository = $habitRepository;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[Route(name: 'habit_index', methods: 'GET')]
    public function index(Request $request): Response
    {
        $habits = $this->habitRepository->findAll();

        return $this->render(
            'habit/index.html.twig',
            ['habits' => $habits]
        );
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
        return $this->render('task/show.html.twig', ['habit' => $habit]);
    }
}
