<?php

namespace App\Controller;

use App\Api\Http\Wikimedia\Client;
use App\Api\Pokepedia\Client\Auth;
use App\Cache\CacheHandler;
use App\Entity\Generation;
use App\Entity\MoveLearnMethod;
use App\Entity\Pokemon;
use App\Entity\PokemonMoveAvailability;
use App\Exception\InvalidResponse;
use App\Processor\CompareProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompareController extends AbstractController
{
    private EntityManagerInterface $em;
    private CompareProcessor $processor;
    private Auth $auth;
    private CacheHandler $cacheHandler;

    public function __construct(EntityManagerInterface $em, CompareProcessor $processor, Auth $auth,CacheHandler $cacheHandler)
    {
        $this->em = $em;
        $this->processor = $processor;
        $this->auth = $auth;
        $this->cacheHandler = $cacheHandler;
    }

    /**
     * @Route("/admin/compare", name="compare")
     */
    public function index(): Response
    {
        return $this->render('compare/index.html.twig', [
            'controller_name' => 'CompareController',
        ]);
    }

    /**
     * @Route("/admin/compare/start", name="_init_compare", options={"expose"=true})
     */
    public function initCompare(Request $request)
    {
        try {
            if (!$request->isXMLHttpRequest()) {
                return new JsonResponse(['error' => 'This is not an ajax call'], 400);
            }

            $startAt = $request->get('startAt');
            $pokemons = $this->em->getRepository(PokemonMoveAvailability::class)->findPokemonWithSpecificPageStartingAt($startAt);
            $learnmethod = $this->em->getRepository(MoveLearnMethod::class)->findOneBy(['name' => 'level-up']);
            $generations = $this->em->getRepository(Generation::class)->findAll();

            $pokemonIds = array_map(function ($value) {
                return $value->getId();
            }, $pokemons);

            $generationsIds = array_map(function ($value) {
                return $value->getId();
            }, $generations);

            $response = (new JsonResponse([
                'success' => true,
                'data' => [
                    'pokemons' => $pokemonIds,
                    'learnMethod' => $learnmethod->getId(),
                    'generations' => $generationsIds,
                ]
            ]));
            $response->headers->setcookie(new Cookie('token', $this->login()));
            return $response;
        } catch (\Exception $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], 400);
        }
    }

    /**
     * @Route("/admin/compare/next", name="_next_compare", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function nextCompare(Request $request)
    {
        if (!$request->isXMLHttpRequest()) {
            return new Response('This is not ajax!', 400);
        }

        $generation = $request->get('generation');
        $pokemon = $request->get('pokemon');
        $learnMethod = $request->get('learnMethod');

        try {
            $data = $this->processor->compare($generation, $learnMethod, $pokemon, false);
            return new JsonResponse(
                [
                    'success' => true,
                    'data' => $data
                ]
            );
        } catch (\Exception $exception) {
            return new JsonResponse(
                [
                    'success' => false,
                    'error' => $exception->getMessage(),
                ],
                400
            );
        }
    }

    private function login()
    {
        $endPoint = "https://www.pokepedia.fr/api.php";

        $login_Token = $this->auth->getLoginToken($endPoint);
        $this->auth->loginRequest($login_Token, $endPoint);
        return $this->auth->getCSRFToken($endPoint);
    }

    /**
     * @Route("/admin/compare/upload", name="_upload_compare", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadCompare(Request $request): JsonResponse
    {
        $cookies = $request->cookies;

        $title = $request->get('title');
        $token = $cookies->get('token');
        $section = $request->get('section');
        $wikiText = $request->get('wikitext');

        $params = [
            "action" => "edit",
            "section" => $section,
            "title" => str_replace("%27","'",$title),
            "text" => $wikiText,
            "format" => "json",
            "bot" => true,
            "nocreate" => true,
            "token" => $token,
            "summary" => 'Mis a jour des attaques apprises'
        ];

        try {
            Client::edit('https://www.pokepedia.fr/api.php', $params);

            return new JsonResponse(
                [
                    'success' => true,
                    'data' => 'Wiki text uploaded'
                ]
            );

        } catch (InvalidResponse $exception) {
            return new JsonResponse(
                [
                    'success' => false,
                    'error' => $exception->getMessage()
                ]
            );
        }
    }

    /**
     * @Route("/admin/clear-cache", name="_clear_cache", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function clearPokepediaMoveCache(Request $request): JsonResponse
    {
        $this->cacheHandler->deleteCache();

        return new JsonResponse("OK");
    }

}
