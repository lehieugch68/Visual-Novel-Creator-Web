<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Game;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", methods="GET", name="home")
     */
    public function index(SessionInterface $session): Response
    {
        $games = $this->getDoctrine()->getRepository(Game::class)->findAll();
        return $this->render('home/index.html.twig', [
            'games' => $games,
        ]);
    }
}
