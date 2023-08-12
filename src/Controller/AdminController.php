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

use App\Entity\Post;
use App\Form\PostFormType;
use App\Interface\PostServiceInterface;
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
     * User service.
     */
    private UserServiceInterface $userService;
    /**
     * Post service.
     */
    private PostServiceInterface $postService;
    /**
     * Translator.
     */
    private TranslatorInterface $translator;
    /**
     * Constructor.
     *
     * @param PostServiceInterface $postService post Service
     * @param UserServiceInterface $userService habit Service
     * @param TranslatorInterface  $translator  translator
     *
     */
    public function __construct(PostServiceInterface $postService, UserServiceInterface $userService, TranslatorInterface $translator)
    {
        $this->postService = $postService;
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
        $limit = 10;
        $users = $this->userService->findAll();

        $numberUsers = count($users);
        $pages = ceil(  $numberUsers/$limit);

        $firstUser = ($number-1)*$limit;


        $pageUsers = array_slice($users,$firstUser,$limit);


        return $this->renderView('admin/pages/users.html.twig', ['users'=>$pageUsers, 'pages'=>$pages, 'current'=>$number]);
    }

    /**
     * Render posts list.
     *
     * @return Response
     */
    public function renderPostsPage(): string
    {
        $posts = $this->postService->findAll();
        return $this->renderView('admin/pages/posts.html.twig', ['posts'=>$posts]);
    }

    /**
     * Render create post.
     *
     * @return Response
     */
    public function renderCreatePage(): string
    {
        $post = new Post();
        $create = $this->createForm(PostFormType::class,  $post)->createView();
        return $this->renderView('admin/pages/create.html.twig', ['form'=> $create]);
    }



}
