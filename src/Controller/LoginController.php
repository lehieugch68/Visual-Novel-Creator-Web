<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Account;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", methods={"POST"}, name="login")
     */
    public function index(SerializerInterface $serializer, Request $request, SessionInterface $session): Response
    {
        try {
            $account = $this->getDoctrine()->getRepository(Account::class)->findOneByUsername($request->request->get('username'));
            if ($account == null) {
                return new Response(
                    json_encode(['error' => 'Incorrect account or password']),
                    Response::HTTP_NOT_FOUND,
                    [
                      'content-type' => 'application/json'
                    ]
                );

            } else {
                $password = $account->getPassword();
                if(password_verify($request->request->get('password'), $password)) {
                    $userid = $account->getId();
                    $session->set('userid', "$userid");
                    return new Response(null, Response::HTTP_NO_CONTENT);
                } else {
                    return new Response(
                        json_encode(['error' => 'Incorrect account or password']),
                        Response::HTTP_NOT_FOUND,
                        [
                          'content-type' => 'application/json'
                        ]
                    );
                }
            } 
        } catch(\Exception $e) {
            return new Response(
                json_encode(['error' => $e->getMessage()]),
                Response::HTTP_BAD_REQUEST,
                [
                    'content-type' => 'application/json'
                ]
            );
        }
    }
}
