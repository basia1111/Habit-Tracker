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
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Post Controller.
 */
class PostsController extends AbstractController
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
     * Slugger.
     */
    private SluggerInterface $slugger;
    /**
     * Constructor.
     *
     * @param PostServiceInterface $postService post Service
     * @param UserServiceInterface $userService habit Service
     * @param TranslatorInterface  $translator  translator
     * @param SluggerInterface     $slugger     slugger
     *
     */
    public function __construct(PostServiceInterface $postService, UserServiceInterface $userService, TranslatorInterface $translator,SluggerInterface $slugger)
    {
        $this->postService = $postService;
        $this->userService = $userService;
        $this->translator = $translator;
        $this->slugger = $slugger;
    }

    /**
     * Create Post.
     *
     * @return Response
     */
    #[Route(path: '/post_create', name: 'post_create')]
    public function create(Request $request): Response
    {
        $user = $this->getUser();

        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setAuthor($user);
            $post->setCreatedAt(new \DateTimeImmutable());
            $imageFile = $form->get('cover')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('cover_images_directory'),
                        $newFilename
                    );
                    $post->setCover($newFilename);
                    $this->userService->save($user);
                } catch (FileException) {
                }
            }
            $this->postService->save($post);
        }
        return $this->redirectToRoute('app_admin');
    }
}