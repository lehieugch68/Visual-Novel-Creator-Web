<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

use App\Entity\Account;
use App\Entity\Game;
use App\Entity\GameImage;
use App\Entity\GameSound;
use App\Entity\GameIntro;
use App\Entity\GameScene;
use App\Entity\GameStoryScene;
use App\Entity\GameBattleScene;
use App\Form\GameIntroType;
use App\Form\GameSceneUpdateType;
use App\Form\GameStorySceneType;
use App\Form\GameBattleSceneType;

class SceneController extends AbstractController
{
    /**
     * @Route("/editor/{gameid}/intro", name="intro")
     */
    public function intro(SessionInterface $session, Request $request, $gameid): Response
    {
        $userid = $session->get('userid');
        if ($userid == null) return $this->redirectToRoute('home');
        $game = $this->getDoctrine()->getRepository(Game::class)->find($gameid);
        if ($game == null || $game->getCreator()->getId() != $userid) return $this->redirectToRoute('pageManager', ['page' => '1']);

        $intro = new GameIntro();
        $form = $this->createForm(GameIntroType::class, $intro);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if ($form->get('introorder')->getData() == null) {
                $largestOrder = $this->getDoctrine()->getRepository(GameIntro::class)->findLargestOrder($game->getId())[0];
                $intro->setIntroorder(intval($largestOrder['LargestOrder']) + 1);
            }
            $intro->setGame($game);
            $em->persist($intro);
            $em->flush();
            $this->addFlash("Notice", "Create successfully!");
            return $this->redirectToRoute('intro', ['gameid' => $gameid]);
        }

        $intros = $game->getGameIntros();

        return $this->render('scene/intro.html.twig', [
            'game' => $game,
            'intros' => $intros,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/scene/delete", name="deleteScene", methods={"POST"})
     */
    public function deleteScene(SessionInterface $session, Request $request): Response {
        $userid = $session->get('userid');
        $scene = $this->getDoctrine()->getRepository(GameScene::class)->find($request->get('sceneid'));
        if ($scene == null || $userid == null) return $this->redirectToRoute('home');
        $game = $scene->getGame();
        if ($game->getCreator()->getId() != $userid) return $this->redirectToRoute('pageManager', ['page' => '1']);
        $em = $this->getDoctrine()->getManager();
        $em->remove($scene);
        $em->flush();
        $this->addFlash("Notice", "Delete successfully!");
        return $this->redirectToRoute('editor', ['id' => $game->getId()]);
    }

    /**
     * @Route("/intro/delete", name="deleteIntro", methods={"POST"})
     */
    public function deleteIntro(SessionInterface $session, Request $request): Response {
        $userid = $session->get('userid');
        $intro = $this->getDoctrine()->getRepository(GameIntro::class)->find($request->get('introid'));
        if ($intro == null || $userid == null) return $this->redirectToRoute('home');
        $game = $intro->getGame();
        if ($game->getCreator()->getId() != $userid) return $this->redirectToRoute('pageManager', ['page' => '1']);
        $em = $this->getDoctrine()->getManager();
        $em->remove($intro);
        $em->flush();
        $this->addFlash("Notice", "Delete successfully!");
        return $this->redirectToRoute('intro', ['gameid' => $game->getId()]);
    }

    /**
     * @Route("/context/story/delete", name="deleteStoryContext", methods={"POST"})
     */
    public function deleteStoryContext(SessionInterface $session, Request $request): Response {
        $userid = $session->get('userid');
        $context = $this->getDoctrine()->getRepository(GameStoryScene::class)->find($request->get('contextid'));
        if ($context == null || $userid == null) return $this->redirectToRoute('home');
        $game = $context->getGameScene()->getGame();
        if ($game->getCreator()->getId() != $userid) $this->redirectToRoute('pageManager', ['page' => '1']);
        $em = $this->getDoctrine()->getManager();
        $em->remove($context);
        $em->flush();
        $this->addFlash("Notice", "Delete successfully!");
        return $this->redirectToRoute('storyScene', ['gameid' => $game->getId(), 'sceneid' => $context->getGameScene()->getId()]);
    }

    /**
     * @Route("/editor/{gameid}/scene/story/{sceneid}", name="storyScene")
     */
    public function storyScene(SessionInterface $session, Request $request, $gameid, $sceneid): Response
    {
        $userid = $session->get('userid');
        if ($userid == null) return $this->redirectToRoute('home');
        $scene = $this->getDoctrine()->getRepository(GameScene::class)->find($sceneid);
        if ($scene == null || $scene->getIsbattle() == true) return $this->redirectToRoute('editor', ['id' => $gameid]);
        $game = $scene->getGame();
        if ($game->getCreator()->getId() != $userid) return $this->redirectToRoute('pageManager', ['page' => '1']);
        $user = $game->getCreator();


        $sceneForm = $this->createForm(GameSceneUpdateType::class, $scene, [
            'user' => $user,
            'currentbg' => $scene->getBackground(),
            'currentbgm' => $scene->getMusic(),
        ]);
        $sceneForm->handleRequest($request);
        if ($sceneForm->isSubmitted() && $sceneForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $bg = $sceneForm->get('bg')->getData();
            if ($bg != null) {
                $originalFilename = pathinfo($bg->getClientOriginalName(), PATHINFO_FILENAME);
                $slugger = new Slugger();
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().$bg->guessExtension();
                $folder = 'img/'.$userid;
                try {
                    $bg->move($folder);
                } catch (\Exception $err) {
                    return new Responsive(null, Responsive::HTTP_INTERNAL_SERVER_ERROR);
                }
                $img = new GameImage();
                $img->setFilename($newFilename);
                $img->setUrl('/'.$folder.'/'.$newFilename);
                $img->setUploader($user);
                $em->persist($img);
                $scene->setBackground($img);
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
                $gameSound->setUploader($user);
                $em->persist($gameSound);
                $em->flush();
                $scene->setMusic($gameSound);
            } else {
                $sound = $sceneForm->get('soundselect')->getData();
                $scene->setMusic($sound);
            }
            $scene->setGame($game);
            $em->persist($scene);
            $em->flush();
            $this->addFlash("Notice", "Update successfully!");
            return $this->redirectToRoute('storyScene', ['gameid' => $gameid, 'sceneid' => $sceneid]);
        }

        $context = new GameStoryScene();
        $form = $this->createForm(GameStorySceneType::class, $context, [
            'user' => $user
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if ($form->get('contextorder')->getData() == null) {
                $largestOrder = $this->getDoctrine()->getRepository(GameStoryScene::class)->findLargestOrder($scene->getId())[0];
                $context->setContextorder(intval($largestOrder['LargestOrder']) + 1);
            }
            $talkerimg = $form->get('talkerimg')->get('image')->getData();
            $slugger = new AsciiSlugger();
            if ($talkerimg) {
                $originalFilename = pathinfo($talkerimg->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$talkerimg->guessExtension();
                $folder = 'img/'.$userid;
                try {
                    $talkerimg->move($folder, $newFilename);
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
                $gameImg->setUploader($user);
                $em->persist($gameImg);
                $em->flush();
                $context->setTalkericon($gameImg);
            } else {
                $talkerimg = $form->get('talkerimgselect')->getData();
                $context->setTalkericon($talkerimg);
            }
            $charimg = $form->get('characterimg')->get('image')->getData();
            if ($charimg) {
                $originalFilename = pathinfo($charimg->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$charimg->guessExtension();
                $folder = 'img/'.$userid;
                try {
                    $charimg->move($folder, $newFilename);
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
                $gameImg->setUploader($user);
                $em->persist($gameImg);
                $em->flush();
                $context->setCharacterimage($gameImg);
            } else {
                $charimg = $form->get('characterimgselect')->getData();
                $context->setCharacterimage($charimg);
            }
            $context->setGamescene($scene);
            $em->persist($context);
            $em->flush();
            $this->addFlash("Notice", "Create successfully!");
            return $this->redirectToRoute('storyScene', ['gameid' => $gameid, 'sceneid' => $sceneid]);
        }

        return $this->render('scene/storyScene.html.twig', [
            'game' => $game,
            'scene' => $scene,
            'sceneform' => $sceneForm->createView(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/editor/{gameid}/scene/battle/{sceneid}", name="battleScene")
     */
    public function battleScene(SessionInterface $session, Request $request, $gameid, $sceneid): Response
    {
        $userid = $session->get('userid');
        if ($userid == null) return $this->redirectToRoute('home');
        $scene = $this->getDoctrine()->getRepository(GameScene::class)->find($sceneid);
        if ($scene == null || $scene->getIsbattle() == false || $scene->getGameBattleScene() == null) return $this->redirectToRoute('editor', ['id' => $gameid]);
        $battleScene = $scene->getGameBattleScene();
        $game = $scene->getGame();
        if ($game->getCreator()->getId() != $userid) $this->redirectToRoute('pageManager', ['page' => '1']);
        $user = $game->getCreator();

        $sceneForm = $this->createForm(GameSceneUpdateType::class, $scene, [
            'user' => $user,
            'currentbg' => $scene->getBackground(),
            'currentbgm' => $scene->getMusic(),
        ]);
        $sceneForm->handleRequest($request);
        if ($sceneForm->isSubmitted() && $sceneForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
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
                $gameImg->setUploader($user);
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
                $gameSound->setUploader($user);
                $em->persist($gameSound);
                $em->flush();
                $scene->setMusic($gameSound);
            } else {
                $sound = $sceneForm->get('soundselect')->getData();
                $scene->setMusic($sound);
            }
            $scene->setGame($game);
            $em->persist($scene);
            $em->flush();
            $this->addFlash("Notice", "Update successfully!");
            return $this->redirectToRoute('battleScene', ['gameid' => $gameid, 'sceneid' => $sceneid]);
        }
        $userImgs = $user->getGameImages();

        return $this->render('scene/battleScene.html.twig', [
            'game' => $game,
            'scene' => $scene,
            'battle' => $battleScene,
            'userImgs' => $userImgs,
            'sceneform' => $sceneForm->createView(),
        ]);
    }
}
