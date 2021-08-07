<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\ContestEffect;
use App\Entity\ContestType;
use App\Entity\Generation;
use App\Entity\Move;
use App\Entity\MoveDamageClass;
use App\Entity\MoveTarget;
use App\Entity\Type;
use Doctrine\ORM\EntityManagerInterface;

//extract and transform move move information into entities from pokeapi
class MoveApi
{
    private PokeAPIGraphQLClient $client;

    public function __construct(EntityManagerInterface $em, PokeAPIGraphQLClient $client)
    {
        $this->em = $em;
        $this->client = $client;
    }

    public function getMoves(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_move {
    accuracy
    move_effect_chance
    name
    pokemon_v2_contesttype {
      name
    }
    pokemon_v2_generation {
      name
    }
    pokemon_v2_movedamageclass {
      name
    }
    pokemon_v2_contesteffect {
      jam
      appeal
    }
    pokemon_v2_movetarget {
      name
    }
    power
    pp
    priority
    pokemon_v2_type {
      name
    }
  }
}

GRAPHQL;

        $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $moves = [];
        foreach ($content['data']['pokemon_v2_move'] as $move) {
            $moveEntity = new Move();
            $moveEntity->setName($move['name']);
            $moveEntity->setAccuracy($move['accuracy']);
            $moveEntity->setMoveEffectChance($move['move_effect_chance']);
            if($move['pokemon_v2_contesttype']) {
                $contestType = $this->em->getRepository(ContestType::class)
                    ->findOneBy(['name' => $move['pokemon_v2_contesttype']['name']]);
                $moveEntity->setContestType($contestType);
            }
            if($move['pokemon_v2_generation']) {
                $generation = $this->em->getRepository(Generation::class)
                    ->findOneBy(['name' => $move['pokemon_v2_generation']['name']]);
                $moveEntity->setGeneration($generation);
            }
            if($move['pokemon_v2_movedamageclass']) {
                $damageClass = $this->em->getRepository(MoveDamageClass::class)
                    ->findOneBy(['name' => $move['pokemon_v2_movedamageclass']['name']]);
                $moveEntity->setMoveDamageClass($damageClass);
            }
            if($move['pokemon_v2_contesteffect']) {
                $contestEffect = $this->em->getRepository(ContestEffect::class)
                    ->findOneBy([
                        'jam' => $move['pokemon_v2_contesteffect']['jam'],
                        'appeal' => $move['pokemon_v2_contesteffect']['appeal'],
                    ]);
                $moveEntity->setContestEffect($contestEffect);
            }
            if($move['pokemon_v2_movetarget']) {
                $target = $this->em->getRepository(MoveTarget::class)
                    ->findOneBy([
                        'name' => $move['pokemon_v2_movetarget']['name'],
                    ]);
                $moveEntity->setMoveTarget($target);
            }
            if($move['pokemon_v2_type']) {
                $type = $this->em->getRepository(Type::class)
                    ->findOneBy([
                        'name' => $move['pokemon_v2_type']['name'],
                    ]);
                $moveEntity->setType($type);
            }
            $moveEntity->setPower($move['power']);
            $moveEntity->setPp($move['pp']);
            $moveEntity->setPower($move['power']);
            $moveEntity->setPriority($move['priority']);
            $moves[] = $moveEntity;
        }

        return $moves;
    }
}
