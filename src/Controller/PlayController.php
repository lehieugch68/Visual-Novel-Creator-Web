<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Game;

class PlayController extends AbstractController
{
    /**
     * @Route("/play/{gameid}", methods={"GET"}, name="play")
     */
    public function index($gameid): Response
    {
        $game = $this->getDoctrine()->getRepository(Game::class)->find($gameid);
        if ($game == null) return $this->redirectToRoute('home');
        return $this->render('play/index.html.twig', [
            'game' => $game,
        ]);
    }
}
