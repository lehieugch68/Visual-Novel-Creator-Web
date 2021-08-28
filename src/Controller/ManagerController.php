<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\String\Slugger\AsciiSlugger;

use App\Entity\Account;
use App\Entity\Game;
use App\Entity\GameScene;
use App\Entity\GameImage;
use App\Entity\GameSound;
use App\Entity\GameBattleScene;
use App\Form\GameType;
use App\Form\GameSceneType;

class ManagerController extends AbstractController
{
    /**
     * @Route("/manager", methods={"GET"}, name="manager")
     */
    public function index(SessionInterface $session): Response
    {
        return $this->redirectToRoute('pageManager', ['page' => '1']);
    }

    /**
     * @Route("/manager/page-{page}", name="pageManager")
     */
    public function showPage(SessionInterface $session, Request $request, $page): Response
    {
        $userid = $session->get('userid');
        if ($userid == null) return $this->redirectToRoute('home');
        if (intval($page) <= 0) return $this->redirectToRoute('pageManager', ['page' => '1']);

        $account = $this->getDoctrine()->getRepository(Account::class)->find($userid);
        $games = $account->getGames();
        $totalpage = ceil(count($games) / 3);

        if ($page > $totalpage) return $this->redirectToRoute('pageManager', ['page' => '1']);

        if ((int)$page == $totalpage) $games = $games->slice((count($games) % 3) * -1);
        else $games = $games->slice(3 * ((int)$page - 1), 3);

        $game = new Game();
        $form = $this->createForm(GameType::class, $game, [
            'user' => $account,
            'currentcover' => null,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $game->setCreator($account);
            $date = date_create();
            $game->setCreatedat(strval(date_timestamp_get($date)));
            $file = $form->get('cover')->get('image')->getData();
            $em = $this->getDoctrine()->getManager();
            if ($file) {
                $slugger = new AsciiSlugger();
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
                $folder = 'img/'.$userid;
                try {
                    $file->move($folder, $newFilename);
                } catch (FileException $e) {
                    return new Response(
                        json_encode(['error' => $e->getMessage()]),
                        Response::HTTP_INTERNAL_SERVER_ERROR,
                        [
                            'content-type' => 'application/json'
                        ]
                    );
                }
                $gameImg = new GameImage();
                $gameImg->setUrl('/'.$folder.'/'.$newFilename);
                $gameImg->setFilename($newFilename);
                $gameImg->setUploader($account);
                $em->persist($gameImg);
                $em->flush();
                $game->setImagecover($gameImg);
            } else {
                $cover = $form->get('imageselect')->getData();
                $game->setImagecover($cover);
            }
            $em->persist($game);
            $em->flush();
            $this->addFlash("Notice", "Create successfully!");
            return $this->redirectToRoute('pageManager', ['page' => '1']);
        }

        return $this->render('manager/index.html.twig', [
            'account' => $account,
            'games' => $games,
            'totalpage' => $totalpage,
            'currentpage' => (int)$page,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/editor/{id}", name="editor")
     */
    public function update(SessionInterface $session, Request $request, $id): Response
    {
        $userid = $session->get('userid');
        if ($userid == null) return $this->redirectToRoute('home');
        $game = $this->getDoctrine()->getRepository(Game::class)->find($id);
        if ($game == null || $game->getCreator()->getId() != $userid) return $this->redirectToRoute('home');
        $account = $this->getDoctrine()->getRepository(Account::class)->find($userid);
        $form = $this->createForm(GameType::class, $game, [
            'user' => $account,
            'currentcover' => $game->getImagecover(),
        ]);

        $scene = new GameScene();
        $sceneForm = $this->createForm(GameSceneType::class, $scene, [
            'user' => $account
        ]);

        $sceneForm->handleRequest($request);
        $form->handleRequest($request);

        if ($sceneForm->isSubmitted() && $sceneForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if ($sceneForm->get('sceneorder')->getData() == null) {
                $largestOrder = $this->getDoctrine()->getRepository(GameScene::class)->findLargestOrder($game->getId())[0];
                $scene->setSceneorder(intval($largestOrder['LargestOrder']) + 1);
            }
            $bg = $sceneForm->get('bg')->get('image')->getData();
            $slugger = new AsciiSlugger();
            if ($bg) {
                $originalFilename = pathinfo($bg->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$bg->guessExtension();
                $folder = 'img/'.$userid;
                try {
                    $bg->move($folder, $newFilename);
                } catch (FileException $e) {
                    return new Response(
                        json_encode(['error' => $e->getMessage()]),
                        Response::HTTP_INTERNAL_SERVER_ERROR,
                        [
                            'content-type' => 'application/json'
                        ]
                    );
                }
                $gameImg = new GameImage();
                $gameImg->setUrl('/'.$folder.'/'.$newFilename);
                $gameImg->setFilename($newFilename);
                $gameImg->setUploader($account);
                $em->persist($gameImg);
                $em->flush();
                $scene->setBackground($gameImg);
            } else {
                $bg = $sceneForm->get('imageselect')->getData();
                $scene->setBackground($bg);
            }
            $bgm = $sceneForm->get('bgm')->get('sound')->getData();
            if ($bgm) {
                $originalFilename = pathinfo($bgm->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$bgm->guessExtension();
                $folder = 'sound/'.$userid;
                try {
                    $bgm->move($folder, $newFilename);
                } catch (FileException $e) {
                    return new Response(
                        json_encode(['error' => $e->getMessage()]),
                        Response::HTTP_INTERNAL_SERVER_ERROR,
                        [
                            'content-type' => 'application/json'
                        ]
                    );
                }
                $gameSound = new GameSound();
                $gameSound->setUrl('/'.$folder.'/'.$newFilename);
                $gameSound->setFilename($newFilename);
                $gameSound->setUploader($account);
                $em->persist($gameSound);
                $em->flush();
                $scene->setMusic($gameSound);
            } else {
                $sound = $sceneForm->get('soundselect')->getData();
                $scene->setMusic($sound);
            }
            $scene->setGame($game);
            if ($scene->getIsbattle() == true) {
                $battleScene = new GameBattleScene();
                $scene->setGameBattleScene($battleScene);
                $em->persist($battleScene);
                $em->flush();
            }
            $em->persist($scene);
            $em->flush();
            $this->addFlash("Notice", "Add scene successfuly!");
            return $this->redirectToRoute('editor', ['id' => $id]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $file = $form->get('cover')->get('image')->getData();
            if ($file) {
                $slugger = new AsciiSlugger();
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
                $folder = 'img/'.$userid;
                try {
                    $file->move($folder, $newFilename);
                } catch (FileException $e) {
                    return new Response(
                        json_encode(['error' => $e->getMessage()]),
                        Response::HTTP_INTERNAL_SERVER_ERROR,
                        [
                            'content-type' => 'application/json'
                        ]
                    );
                }
                $gameImg = new GameImage();
                $gameImg->setUrl('/'.$folder.'/'.$newFilename);
                $gameImg->setFilename($newFilename);
                $gameImg->setUploader($account);
                $em->persist($gameImg);
                $em->flush();
                $game->setImagecover($gameImg);
            } else {
                $imgCover = $form->get('imageselect')->getData();
                $game->setImagecover($imgCover);
            }
            $em->persist($game);
            $em->flush();
            $this->addFlash("Notice", "Update successfully!");
            return $this->redirectToRoute('editor', ['id' => $id]);
        }

        $scenes = $game->getGameScenes();

        return $this->render('manager/editor.html.twig', [
            'game' => $game,
            'scenes' => $scenes,
            'form' => $form->createView(),
            'sceneform' => $sceneForm->createView(),
        ]);
    }

    /**
     * @Route("/game/delete", name="deletegame", methods={"POST"})
     */
    public function deleteGame(SessionInterface $session, Request $request): Response {
        $userid = $session->get('userid');
        if ($userid == null) return $this->redirectToRoute('home');
        $game = $this->getDoctrine()->getRepository(Game::class)->find($request->get('gameid'));
        if ($game == null || $game->getCreator()->getId() != $userid) return $this->redirectToRoute('pageManager', ['page' => '1']);
        $em = $this->getDoctrine()->getManager();
        $em->remove($game);
        $em->flush();
        $this->addFlash("Notice", "Delete successfully!");
        return $this->redirectToRoute('pageManager', ['page' => '1']);
    }
}
