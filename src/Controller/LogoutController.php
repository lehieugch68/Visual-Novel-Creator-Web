<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;

class LogoutController extends AbstractController
{
    #[Route('/logout', name: 'logout')]
    public function index(Request $request, SessionInterface $session): Response
    {
        if ($session->get('userid') == null) return $this->redirectToRoute('home');
        $session->clear();
        return $this->redirect($request->request->get('referer'));
    }
}
