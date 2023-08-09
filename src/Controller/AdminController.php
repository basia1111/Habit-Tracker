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

use App\Interface\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Admin Controller.
 */
class AdminController extends AbstractController
{
    /**
     * Habit service.
     */
    private UserServiceInterface $userService;
    /**
     * Translator.
     */
    private TranslatorInterface $translator;
    /**
     * Constructor.
     *
     * @param UserServiceInterface $userService habit Service
     * @param TranslatorInterface  $translator  translator
     *
     */
    public function __construct(UserServiceInterface $userService, TranslatorInterface $translator)
    {
        $this->userService = $userService;
        $this->translator = $translator;
    }

    /**
     * Admin index.
     *
     * @return Response
     */
    #[Route(path: '/admin', name: 'app_admin')]
    public function admin(): Response
    {
        $users=$this->userService->findAll();

        return $this->render('admin/index.html.twig', ['users'=>$users]);
    }
    /**
     * Render Page.
     *
     * @param $page
     *
     * @return Response
     */
    #[Route('/admin_page/{page}', name: 'render_admin_page', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT|DELETE')]
    public function renderPage($page, Request $request): Response
    {
        $pages = [
            'users' => 'renderUsersPage',
            'create' => 'renderCreatePage',
            'posts' => 'renderPostsPage',
        ];
        $number = $request->query->get('number');
        $functionName = $pages[$page];
        if($page === 'users') {
            $html = $this->$functionName($number);
        } else {
            $html = $this->$functionName();
        }


        $data = [
            'html' => $html,
            'number'=>  $number
        ];

        return new JsonResponse($data);
    }


    /**
     * Render users list.
     *
     * @return Response
     */
    public function renderUsersPage($number): string
    {
        $limit = 1;
        $users = $this->userService->findAll();

        $numberUsers = count($users);
        $pages = ceil(  $numberUsers/1);

        $firstUser = ($number-1)*$limit;


        $pageUsers = array_slice($users,$firstUser,$limit);


        return $this->renderView('admin/pages/users.html.twig', ['users'=>$pageUsers, 'pages'=>$pages, 'current'=>$number]);
    }


}
