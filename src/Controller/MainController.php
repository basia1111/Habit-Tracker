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
use App\Entity\User;
use App\Form\DeleteHabitFormType;
use App\Form\HabitFormType;
use App\Form\ProfilePictureFormType;
use App\Interface\ExecutionServiceInterface;
use App\Interface\HabitServiceInterface;
use App\Interface\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Main Controller.
 */
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
     * User service.
     */
    private UserServiceInterface $userService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Slugger.
     */
    private SluggerInterface $slugger;

    /**
     * Constructor.
     *
     * @param HabitServiceInterface     $habitService     habit Service
     * @param UserServiceInterface      $userService      user Service
     * @param TranslatorInterface       $translator       translator
     * @param ExecutionServiceInterface $executionService execution Service
     * @param SluggerInterface          $slugger          slugger
     */
    public function __construct(HabitServiceInterface $habitService, UserServiceInterface $userService, TranslatorInterface $translator, ExecutionServiceInterface $executionService, SluggerInterface $slugger)
    {
        $this->habitService = $habitService;
        $this->userService = $userService;
        $this->executionService = $executionService;
        $this->translator = $translator;
        $this->slugger = $slugger;
    }

    /**
     * Main action.
     *
     * @return Response HTTP response
     */
    #[Route(path: '/main', name: 'app_main')]
    public function main(): Response
    {
        $user = $this->getUser();

        $todayHabits = $this->habitService->todayHabits($user);
        $allHabits =  $this->habitService->findAll($user);
        $habits =  $this->habitService->findAll($user);
        $date = new \DateTime();

        $this->habitService->checkStreak($todayHabits);
        $percentage = $this->habitService->todaySuccessRate($user);

        $habit = new Habit();
        $create = $this->createForm(HabitFormType::class, $habit)->createView();
        $imageForm = $this->createForm(ProfilePictureFormType::class)->createView();
        $deleteForms =[];

        foreach ($allHabits as $habit) {
            $deleteForm = $this->createForm(DeleteHabitFormType::class, null,[
            'habitId' => $habit->getId(),
            ]);
            $deleteForms[$habit->getId()] = $deleteForm->createView();
        }


        $week = $this->habitService->createWeek($user);
        $monday = $week[0];
        $tuesday = $week[1];
        $wednesday = $week[2];
        $thursday = $week[3];
        $friday = $week[4];
        $saturday = $week[5];
        $sunday = $week[6];

        return $this->render('main/index.html.twig', ['user' => $user, 'percentage' => $percentage, 'todayHabits' => $todayHabits, 'allHabits'=>$allHabits ,'date' => $date, 'habits' => $habits, 'create' => $create, 'number' => count($habits), 'monday' => $monday, 'tuesday' => $tuesday, 'wednesday' => $wednesday, 'thursday' => $thursday, 'friday' => $friday, 'saturday' => $saturday, 'sunday' => $sunday, 'imageForm' => $imageForm, 'deleteForms' => $deleteForms]);
    }

    /**
     * Render Page.
     *
     * @param $page
     *
     * @return Response
     */
    #[Route('/page/{page}', name: 'render_page', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT|DELETE')]
    public function renderPage($page): Response
    {
        $pages = [
            'home' => 'renderHomePage',
            'habits' => 'renderHabitsPage',
            'week' => 'renderWeekPage',
        ];
        $functionName = $pages[$page];
        $html = $this->$functionName();

        $data = [
            'html' => $html,
        ];

        return new JsonResponse($data);
    }

    /**
     * Change action - change habit status (done/not done).
     *
     * @param Habit $habit habit entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/change/{id}',
        name: 'habit_change',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function change(Habit $habit): Response
    {
        $user = $this->getUser();
        $lastExecution = $habit->getLastExecution();
        $date = new \DateTime();
        $date = $date->format('Y-m-d');

        if ($lastExecution->format('Y-m-d') !== $date) {
            $habit->setLastExecution(new \DateTime());
            $streak = $habit->getStreak();
            $habit->setStreak($streak + 1);

            if ($habit->getBestStreak() < $streak + 1) {
                $habit->setBestStreak($streak + 1);
            }
            $this->habitService->save($habit);

            $execution = new Execution();
            $execution->setHabit($habit);
            $execution->setUser($user);
            $execution->setDate(new \DateTime());
            $this->executionService->save($execution);
        } else {
            $execution = $this->executionService->queryLastExecution($habit);
            $this->executionService->delete($execution[0]);

            $execution = $this->executionService->queryLastExecution($habit);
            $habit->setLastExecution($execution[0]->getDate());
            $this->habitService->save($habit);

            $streak = $habit->getStreak();
            if ($streak > 0) {
                $habit->setStreak($streak - 1);
                $this->habitService->save($habit);
            }
        }

        $id = $habit->getId();
        $streak = $habit->getStreak();
        $percentage = $this->habitService->todaySuccessRate($user);

        $data = [
            'id' => $id,
            'percentage' => $percentage,
            'streak' => $streak,
        ];

        return new JsonResponse($data);
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response JSON response
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

            $htmlToday = $this->renderTodayHabits($user);
            $htmlAll = $this->renderAllHabits($user);
            $percentage = $this->habitService->todaySuccessRate($user);
            $numberOfHabits = count($this->habitService->findAll($user));

            $data = [
                'id' => $habit->getId(),
                'name' => $habit->getName(),
                'image' => $habit->getImage(),
                'today' => $htmlToday,
                'all' => $htmlAll,
                'percentage' => $percentage,
                'number' => $numberOfHabits,
            ];

            return new JsonResponse($data);
        }

        return $this->redirectToRoute('app_main');
    }

    /**
     * delete action.
     *
     * @param Habit $habit habit entity
     *
     * @return Response JSON response
     */
    #[Route('/{id}/delete', name: 'habit_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT|DELETE|POST')]
    public function delete(Habit $habit, Request $request): Response
    {
        if ($habit->getUser() !== $this->getUser()) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.record_not_found')
            );

            return $this->redirectToRoute('app_main');
        }
        $user = $this->getUser();

        $form = $this->createForm(DeleteHabitFormType::class, null,[
            'habitId' => $habit->getId(),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $executions = $this->executionService->queryAll($habit);
            foreach ($executions as $execution) {
                $this->executionService->delete($execution);
            }
            $this->habitService->delete($habit);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            $htmlToday = $this->renderTodayHabits($user);
            $htmlAll = $this->renderAllHabits($user);
            $percentage = $this->habitService->todaySuccessRate($user);
            $numberOfHabits = count($this->habitService->findAll($user));

            $data = [
                'today' => $htmlToday,
                'all' => $htmlAll,
                'percentage' => $percentage,
                'number' => $numberOfHabits,
            ];

            return new JsonResponse($data);
        }
        return $this->redirectToRoute('app_main');
    }

    /**
     * Render Today Habits.
     *
     * @param User $user user entity
     *
     * @return string Html code
     */
    public function renderTodayHabits($user): string
    {
        $todayHabits = $this->habitService->todayHabits($user);
        $date = new \DateTime();

        return $this->renderView('main/rerendered/today_habits.html.twig', ['todayHabits' => $todayHabits, 'date' => $date]);
    }

    /**
     * Render All Habits.
     *
     * @param User $user user entity
     *
     * @return string Html code
     */
    public function renderAllHabits($user): string
    {
        $habits = $this->habitService->findAll($user);
        $user = $this->getUser();
        $deleteForms =[];

        foreach ($habits as $habit) {
            $deleteForm = $this->createForm(DeleteHabitFormType::class, null,[
                'habitId' => $habit->getId(),
            ]);
            $deleteForms[$habit->getId()] = $deleteForm->createView();
        }


        return $this->renderView('main/rerendered/all_habits.html.twig', ['habits' => $habits, 'deleteForms'=>$deleteForms]);
    }

    /**
     * Render Home Page.
     *
     * @return string Html code
     */
    public function renderHomePage(): string
    {
        $user = $this->getUser();
        $todayHabits = $this->habitService->todayHabits($user);
        $habits = $this->habitService->findAll($user);
        $date = new \DateTime();

        $this->habitService->checkStreak($todayHabits);
        $percentage = $this->habitService->todaySuccessRate($user);

        $habit = new Habit();
        $create = $this->createForm(HabitFormType::class, $habit)->createView();

        /* Find all habits for each day of the week. */
        $week = $this->habitService->createWeek($user);
        $monday = $week[0];
        $tuesday = $week[1];
        $wednesday = $week[2];
        $thursday = $week[3];
        $friday = $week[4];
        $saturday = $week[5];
        $sunday = $week[6];

        return $this->renderView('main/pages/home.html.twig', ['percentage' => $percentage, 'todayHabits' => $todayHabits, 'date' => $date, 'habits' => $habits, 'create' => $create, 'number' => count($habits), 'monday' => $monday, 'tuesday' => $tuesday, 'wednesday' => $wednesday, 'thursday' => $thursday, 'friday' => $friday, 'saturday' => $saturday, 'sunday' => $sunday]);
    }


    /**
     * Render Week Page.
     *
     * @return string Html code
     */
    public function renderWeekPage(): string
    {
        $user = $this->getUser();
        $week = $this->habitService->createWeek($user);

        $monday = $week[0];
        $tuesday = $week[1];
        $wednesday = $week[2];
        $thursday = $week[3];
        $friday = $week[1];
        $saturday = $week[2];
        $sunday = $week[3];

        return $this->renderView('main/pages/week.html.twig', [
            'monday' => $monday,
            'tuesday' => $tuesday,
            'wednesday' => $wednesday,
            'thursday' => $thursday,
            'friday' => $friday,
            'saturday' => $saturday,
            'sunday' => $sunday,
            'week' => $week,
        ]);
    }

    /**
     * Edit profile picture
     *
     * @param Request $request
     *
     * @return Response
     */
    #[Route('/test', name: 'test', methods: 'GET|PUT|DELETE|POST')]
    public function test(Request $request): Response
    {
        $user = $this->getUser();
        $imageForm = $this->createForm(ProfilePictureFormType::class);
        $imageForm->handleRequest($request);
        $previousImage = $user->getImage();
        if ($imageForm->isSubmitted() && $imageForm->isValid()) {
            $imageFile = $imageForm->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('profile_images_directory'),
                        $newFilename
                    );
                    $user->setImage($newFilename);
                    $this->userService->save($user);
                } catch (FileException) {
                }

                $directory = $this->getParameter('kernel.project_dir').'/public/uploads/profile_images';
                $filesystem = new Filesystem();
                if ('default_user.png' !== $previousImage) {
                    if ($filesystem->exists($directory.'/'.$previousImage)) {
                        $filesystem->remove($directory.'/'.$previousImage);
                    }
                }
            }
            $data = [
                'html' => $user->getImage(),
            ];

            return new JsonResponse($data);
        }
        $data = [
            'html' => 'error',
        ];

        return new JsonResponse($data);
    }

    /**
     * Restore default picture.
     *
     * @return Response
     */
    #[Route('/default', name: 'default', methods: 'GET|PUT|DELETE|POST')]
    public function resetToDefault(): Response
    {
        $user = $this->getUser();
        $previousImage = $user->getImage();
        $user->setImage('default_user.png');
        $this->userService->save($user);
        $data = [
            'html' => $user->getImage(),
        ];
        $directory = $this->getParameter('kernel.project_dir').'/public/uploads/profile_images';
        $filesystem = new Filesystem();

        if ($filesystem->exists($directory.'/'.$previousImage)) {
            $filesystem->remove($directory.'/'.$previousImage);
        }


        return new JsonResponse($data);
    }

    /**
     * Reload content.
     *
     * @return Response JSON response
     */
    #[Route('/reload', name: 'reload', methods: 'GET|PUT|DELETE|POST')]
    public function reload(Request $request): Response
    {   $user = $this->getUser();
        $htmlToday = $this->renderTodayHabits($user);
        $htmlAll = $this->renderAllHabits($user);
        $data = [
            'today' => $htmlToday,
            'all' => $htmlAll,
        ];

        return new JsonResponse($data);

    }
}
