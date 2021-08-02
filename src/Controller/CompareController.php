<?php

namespace App\Controller;

use App\Api\Pokepedia\Client\Auth;
use App\Entity\Generation;
use App\Entity\MoveLearnMethod;
use App\Entity\Pokemon;
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

    public function __construct(EntityManagerInterface $em, CompareProcessor $processor, Auth $auth)
    {
        $this->em = $em;
        $this->processor = $processor;
        $this->auth = $auth;
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
            $pokemons = $this->em->getRepository(Pokemon::class)->findDefaultAndAlolaPokemons((int)$startAt);
            $learnmethod = $this->em->getRepository(MoveLearnMethod::class)->findOneBy(['name' => 'level-up']);
            $generations = $this->em->getRepository(Generation::class)->findAll();

            $pokemonIds = array_map(function ($value) {
                return $value->getId();
            }, $pokemons);

            $generationsIds = array_map(function ($value) {
                return $value->getId();
            }, $generations);

            $response = (new JsonResponse([
                'data' => [
                    'pokemons' => $pokemonIds,
                    'learnMethod' => $learnmethod->getId(),
                    'generations' => $generationsIds,
                ]
            ]));
            $response->headers->setcookie(new Cookie('token',$this->login()));
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
                    'data' => $data
                ]
            );
        } catch (\Exception $exception) {
            return new JsonResponse(
                [
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
        $csrf_Token = $this->auth->getCSRFToken($endPoint);
        return $csrf_Token;
    }

    /**
     * @Route("/admin/compare/upload", name="_upload_compare", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function uploadCompare(Request $request)
    {
        $cookies = $request->cookies;

        $title = $request->get('title');
        $token = $cookies->get('token');
        $section = $request->get('section');
        $wikiText = $request->get('wikitext');

        $params = [
            "action" => "edit",
            "section" => $section,
            "title" => $title,
            "text" => $wikiText,
            "token" => $token,
            "format" => "json",
            "bot" => true,
            "nocreate" => true,
        ];

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, 'https://www.pokepedia.fr/api.php' );
        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params ) );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_COOKIEJAR, "cookie.txt" );
        curl_setopt( $ch, CURLOPT_COOKIEFILE, "cookie.txt" );

        $output = curl_exec( $ch );
        curl_close( $ch );

        echo ( $output );
    }
}
