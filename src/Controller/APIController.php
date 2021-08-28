<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Serializer\SerializerInterface;

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

class APIController extends AbstractController
{
    /**
     * @Route("/api/intro/get/{id}", name="getIntro")
     */
    public function getIntro(SessionInterface $session, Request $request, SerializerInterface $serializer, $id): Response 
    {
        $userid = $session->get('userid');
        if ($userid == null) return new Response(null, Response::HTTP_FORBIDDEN);
        $intro = $this->getDoctrine()->getRepository(GameIntro::class)->find($id);
        if ($intro == null) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
        $arr = array(
            'introid' => $intro->getId(),
            'title' => $intro->getTitle(),
            'introorder' => $intro->getIntroorder(),
            'content' => $intro->getContent(),
        );
        $result = $serializer->serialize($arr,'json');
        return new Response(
            $result,
            Response::HTTP_OK,
            [
                'content-type' => 'application/json'
            ]
        );
    }

    /**
     * @Route("/api/game/get/source/{gameid}", name="getGameSource")
     */
    public function getGameSource(SessionInterface $session, Request $request, SerializerInterface $serializer, $gameid): Response 
    {
        $game = $this->getDoctrine()->getRepository(Game::class)->find($gameid);
        if ($game == null) return new Response(null, Response::HTTP_NOT_FOUND);
    
        $intros = $game->getGameIntros()->toArray();
        $scenes = $game->getGameScenes()->toArray();
        $arr = array(
            'intros' => array(),
            'scenes' => array(),
        );
        foreach ($intros as $intro => $value) {
            $arr['intros'][] = array(
                'title' => $value->getTitle(),
                'content' => $value->getContent(),
                'order' => $value->getIntroorder()
            );
        }
        foreach ($scenes as $scene => $value) {
            $img = $value->getBackground();
            $background = null;
            if ($img != null) $background = $img->getUrl();
            $sound = $value->getMusic();
            $music = null;
            if ($sound != null) $music = $sound->getUrl();
            $sceneArr = array(
                'bgm' => $music,
                'background' => $background,
                'order' => $value->getSceneorder(),
                'contexts' => array(),
                'battle' => $value->getIsbattle(),
            );
            if ($value->getIsbattle() == false) {
                $contexts = $value->getGameStoryScenes();
                foreach ($contexts as $context) {
                    $img = $context->getCharacterimage();
                    $charImg = null;
                    if ($img != null) $charImg = $img->getUrl();
                    $sceneArr['contexts'][] =  array(
                        'talker' => $context->getTalker(),
                        'order' => $context->getContextorder(),
                        'avatar' => $context->getTalkericon()->getUrl(),
                        'message' => $context->getText(),
                        'body' => $charImg,
                    );
                }
            } else {
                $battle = $value->getGameBattleScene();
                $img = $battle->getPlayericon();
                $avatar = null;
                if ($img != null) $avatar = $img->getUrl();
                $img = $battle->getEnemyimage();
                $enemy = null;
                if ($img != null) $enemy = $img->getUrl();
                $sceneArr['battleInfo'] = array(
                    'avatar' => $avatar,
                    'enemy' => $enemy,
                    'playerAtt' => array(
                        'name' => $battle->getPlayername(),
                        'hp' => $battle->getPlayerhp(),
                        'atk' => $battle->getPlayeratk(),
                        'def' => $battle->getPlayerdef(),
                    ),
                    'enemyAtt' => array(
                        'name' => $battle->getEnemyname(),
                        'hp' => $battle->getEnemyhp(),
                        'atk' => $battle->getEnemyatk(),
                        'def' => $battle->getEnemydef(),
                    ),
                );
            }
            $arr['scenes'][] = $sceneArr;
        }
        $result = $serializer->serialize($arr,'json');
        return new Response(
            $result,
            Response::HTTP_OK,
            [
                'content-type' => 'application/json'
            ]
        );
    }

    /**
     * @Route("/api/intro/update", name="updateIntro", methods={"POST"})
     */
    public function updateIntro(SessionInterface $session, Request $request): Response
    {
        $userid = $session->get('userid');
        if ($userid == null) return new Response(null, Response::HTTP_FORBIDDEN);
        try {
            $intro = $this->getDoctrine()->getRepository(GameIntro::class)->find($request->request->get('introid'));
            if ($intro == null) return new Response(null, Response::HTTP_NOT_FOUND);
            $user = $intro->getGame()->getCreator();
            if ($userid != $user->getId()) return new Response(null, Response::HTTP_FORBIDDEN);
            $intro->setTitle($request->request->get('title'));
            $intro->setIntroorder(intval($request->request->get('introorder')));
            $intro->setContent($request->request->get('content'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($intro);
            $em->flush();
            return new Response(null, Response::HTTP_NO_CONTENT);
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

    /**
     * @Route("/api/context/story/get/{id}", name="getStoryContext")
     */
    public function getStoryContext(SessionInterface $session, Request $request, SerializerInterface $serializer, $id): Response 
    {
        $userid = $session->get('userid');
        if ($userid == null) return new Response(null, Response::HTTP_FORBIDDEN);
        $context = $this->getDoctrine()->getRepository(GameStoryScene::class)->find($id);
        if ($context == null) return new Response(null, Response::HTTP_NOT_FOUND );
        if ($context->getGamescene()->getGame()->getCreator()->getId() != $userid) return new Response(null, Response::HTTP_FORBIDDEN);
        $user = $this->getDoctrine()->getRepository(Account::class)->find($userid);
        $arr = array(
            'contextid' => $context->getId(),
            'talker' => $context->getTalker(),
            'contextorder' => $context->getContextorder(),
            'text' => $context->getText(),
            'currtalkericon' => null,
            'currcharimg' => null,
            'userimages' => array()
        );
        $userimgs = $user->getGameImages()->toArray();
        foreach ($userimgs as $a => $value) {
            $arr['userimages'][] = array(
                'imgid' => $value->getId(),
                'url' => $value->getUrl(),
                'filename' => $value->getFilename()
            );
        }
        $talkericon = $context->getTalkericon();
        if ($talkericon != null) $arr['currtalkericon'] = $talkericon->getId();
        $charimg = $context->getCharacterimage();
        if ($charimg != null) $arr['currcharimg'] = $charimg->getId();

        $result = $serializer->serialize($arr,'json');
        return new Response(
            $result,
            Response::HTTP_OK,
            [
                'content-type' => 'application/json'
            ]
        );
    }

    /**
     * @Route("/api/context/battle/update/{contextid}", name="updateBattleContext", methods={"POST"})
     */
    public function updateBattleContext(SessionInterface $session, Request $request, $contextid): Response 
    {
        $userid = $session->get('userid');
        if ($userid == null) return new Response(null, Response::HTTP_FORBIDDEN);
        try {
            $context = $this->getDoctrine()->getRepository(GameBattleScene::class)->find($contextid);
            if ($context == null) return new Response(null, Response::HTTP_NOT_FOUND);
            $user = $context->getScene()->getGame()->getCreator();
            if ($userid != $user->getId()) return new Response(null, Response::HTTP_FORBIDDEN);
            $maxSize = 2 * 1024 * 1024;
            $acceptable = array(
                'image/jpeg',
                'image/jpg',
                'image/png'
            );
            $folder = 'img/'.$userid;
            $slugger = new AsciiSlugger();
            $em = $this->getDoctrine()->getManager();
            
            if ($request->request->get('formtype') == 'player') {
                $context->setPlayername($request->request->get('char_name'));
                $context->setPlayerhp(intval($request->request->get('char_hp')));
                $context->setPlayeratk(intval($request->request->get('char_atk')));
                $context->setPlayerdef(intval($request->request->get('char_def')));
            } else if ($request->request->get('formtype') == 'enemy') {
                $context->setEnemyname($request->request->get('char_name'));
                $context->setEnemyhp(intval($request->request->get('char_hp')));
                $context->setEnemyatk(intval($request->request->get('char_atk')));
                $context->setEnemydef(intval($request->request->get('char_def')));
            } else return new Response(null, Response::HTTP_BAD_REQUEST);
            //handle image
            if ($_FILES['context_image']['tmp_name']) {
                $fileInfo = pathinfo($_FILES['context_image']['name']);
                if ($_FILES['context_image']['size'] > $maxSize) return new Response(
                    json_encode(['error' => 'File size must be less than 2MB']),
                    Response::HTTP_BAD_REQUEST,
                    [
                        'content-type' => 'application/json'
                    ]
                );
                if (!in_array($_FILES['context_image']['type'], $acceptable) && (!empty($_FILES['context_image']['type']))) return new Response(
                    json_encode(['error' => 'Only accept image file'.$fileInfo['filename']]),
                    Response::HTTP_BAD_REQUEST,
                    [
                        'content-type' => 'application/json'
                    ]
                );
                $safeFilename = $slugger->slug($fileInfo['filename']);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$fileInfo['extension'];
                move_uploaded_file($_FILES['context_image']['tmp_name'], $folder.'/'.$newFilename);
                $gameImg = new GameImage();
                $gameImg->setUrl('/'.$folder.'/'.$newFilename);
                $gameImg->setFilename($newFilename);
                $gameImg->setUploader($user);
                $em->persist($gameImg);
                $em->flush();
                if ($request->request->get('formtype') == 'player') $context->setPlayericon($gameImg);
                else $context->setEnemyimage($gameImg);
            } else {
                $selectedIcon = $request->request->get('char_img_select');
                if ($selectedIcon == null) {
                    if ($request->request->get('formtype') == 'player') $context->setPlayericon(null);
                    else $context->setEnemyimage(null);
                } else {
                    $icon = $this->getDoctrine()->getRepository(GameImage::class)->find($selectedIcon);
                    if ($icon == null || $icon->getUploader()->getId() != $userid) {
                        if ($request->request->get('formtype') == 'player') $context->setPlayericon(null);
                        else $context->setEnemyimage(null);
                    }
                    else {
                        if ($request->request->get('formtype') == 'player') $context->setPlayericon($icon);
                        else $context->setEnemyimage($icon);
                    }
                }
            }
            $em->persist($context);
            $em->flush();
            return new Response(null, Response::HTTP_NO_CONTENT);
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

    /**
     * @Route("/api/context/story/update", name="updateStoryContext", methods={"POST"})
     */
    public function updateStoryContext(SessionInterface $session, Request $request): Response
    {
        $userid = $session->get('userid');
        if ($userid == null) return new Response(null, Response::HTTP_FORBIDDEN);
        try {
            $context = $this->getDoctrine()->getRepository(GameStoryScene::class)->find($request->request->get('contextid'));
            if ($context == null) return new Response(null, Response::HTTP_NOT_FOUND);
            $user = $context->getGameScene()->getGame()->getCreator();
            if ($userid != $user->getId()) return new Response(null, Response::HTTP_FORBIDDEN);
            $maxSize = 2 * 1024 * 1024;
            $acceptable = array(
                'image/jpeg',
                'image/jpg',
                'image/png'
            );
            $folder = 'img/'.$userid;
            $slugger = new AsciiSlugger();
            $em = $this->getDoctrine()->getManager();
            
            $context->setTalker($request->request->get('context_talker'));
            $context->setContextorder(intval($request->request->get('context_order')));
            $context->setText($request->request->get('context_text'));

            //talker icon
            if ($_FILES['context_talker_icon']['tmp_name']) {
                $fileInfo = pathinfo($_FILES['context_talker_icon']['name']);
                if ($_FILES['context_talker_icon']['size'] > $maxSize) return new Response(
                    json_encode(['error' => 'File size must be less than 2MB']),
                    Response::HTTP_BAD_REQUEST,
                    [
                        'content-type' => 'application/json'
                    ]
                );
                if (!in_array($_FILES['context_talker_icon']['type'], $acceptable) && (!empty($_FILES["context_talker_icon"]["type"]))) return new Response(
                    json_encode(['error' => 'Only accept image file'.$fileInfo['filename']]),
                    Response::HTTP_BAD_REQUEST,
                    [
                        'content-type' => 'application/json'
                    ]
                );
                $safeFilename = $slugger->slug($fileInfo['filename']);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$fileInfo['extension'];
                move_uploaded_file($_FILES['context_talker_icon']['tmp_name'], $folder.'/'.$newFilename);
                $gameImg = new GameImage();
                $gameImg->setUrl('/'.$folder.'/'.$newFilename);
                $gameImg->setFilename($newFilename);
                $gameImg->setUploader($user);
                $em->persist($gameImg);
                $em->flush();
                $context->setTalkericon($gameImg);
            } else {
                $selectedTalkerIcon = $request->request->get('context_select_talker_icon');
                if ($selectedTalkerIcon == null) {
                    $context->setTalkericon(null);
                } else {
                    $talkerIcon = $this->getDoctrine()->getRepository(GameImage::class)->find($selectedTalkerIcon);
                    if ($talkerIcon == null || $talkerIcon->getUploader()->getId() != $userid) $context->setTalkericon(null);
                    else $context->setTalkericon($talkerIcon);
                }
            }

            //character image
            if ($_FILES['context_charimg']['tmp_name']) {
                $fileInfo = pathinfo($_FILES['context_charimg']['name']);
                if ($_FILES['context_charimg']['size'] > $maxSize) return new Response(
                    json_encode(['error' => 'File size must be less than 2MB']),
                    Response::HTTP_BAD_REQUEST,
                    [
                        'content-type' => 'application/json'
                    ]
                );
                if (!in_array($_FILES['context_charimg']['type'], $acceptable) && (!empty($_FILES["context_charimg"]["type"]))) return new Response(
                    json_encode(['error' => 'Only accept image file'.$fileInfo['filename']]),
                    Response::HTTP_BAD_REQUEST,
                    [
                        'content-type' => 'application/json'
                    ]
                );
                $safeFilename = $slugger->slug($fileInfo['filename']);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$fileInfo['extension'];
                move_uploaded_file($_FILES['context_charimg']['tmp_name'], $folder.'/'.$newFilename);
                $gameImg = new GameImage();
                $gameImg->setUrl('/'.$folder.'/'.$newFilename);
                $gameImg->setFilename($newFilename);
                $gameImg->setUploader($user);
                $em->persist($gameImg);
                $em->flush();
                $context->setCharacterimage($gameImg);
            } else {
                $selectedCharImg = $request->request->get('context_select_charimg');
                if ($selectedCharImg == null) {
                    $context->setCharacterimage(null);
                } else {
                    $charImg = $this->getDoctrine()->getRepository(GameImage::class)->find($selectedCharImg);
                    if ($charImg == null || $charImg->getUploader()->getId() != $userid) $context->setCharacterimage(null);
                    else $context->setCharacterimage($charImg);
                }
            }

            $em->persist($context);
            $em->flush();
            return new Response(null, Response::HTTP_NO_CONTENT);
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
