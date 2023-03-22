<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HeroController extends AbstractController
{
    #[Route(path: '/hero', name: 'app_hero')]
    public function main(): Response
    {
        return $this->render('hero/hero.html.twig');
    }
}
