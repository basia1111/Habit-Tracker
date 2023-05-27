<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class AboutController extends AbstractController
{
    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     */
    public function __construct( TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route(path: '/about', name: 'app_about')]
    public function about(Request $request): Response
    {

        return $this->render('about.html.twig');
    }


}