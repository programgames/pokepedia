<?php

namespace App\Controller;

use App\Entity\Generation;
use App\Entity\MoveLearnMethod;
use App\Entity\Pokemon;
use App\Processor\CompareProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompareController extends AbstractController
{
    private EntityManagerInterface $em;
    private CompareProcessor $processor;

    /**
     * CompareController constructor.
     * @param EntityManagerInterface $em
     * @param CompareProcessor $processor
     */
    public function __construct(EntityManagerInterface $em, CompareProcessor $processor)
    {
        $this->em = $em;
        $this->processor = $processor;
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

            return new JsonResponse([
                'data' => [
                    'pokemons' => $pokemonIds,
                    'learnMethod' => $learnmethod->getId(),
                    'generations' => $generationsIds,
                ]
            ]);
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
           $data =  $this->processor->compare($generation, $learnMethod, $pokemon);
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
}
