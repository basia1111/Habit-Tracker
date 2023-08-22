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
use App\Interface\PostServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
/**
 * Blog Controller.
 */
class BlogController extends AbstractController
{
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
    public function __construct(PostServiceInterface $postService, TranslatorInterface $translator)
    {
        $this->postService = $postService;
        $this->translator = $translator;
    }

    /**
     * Blog index.
     *
     * @return Response
     */
    #[Route(path: '/blog', name: 'app_blog')]
    public function admin(): Response
    {

        $posts=$this->postService->findAll();

        return $this->render('blog/blog.html.twig', ['posts'=>$posts]);
    }


    /**
     * Show Post.
     *
     * @return Response
     */
    public function showPost($id): string
    {
        $post = $this->postService->find($id);
        return $this->render('blog/post.html.twig', ['post'=>$post]);
    }


}
