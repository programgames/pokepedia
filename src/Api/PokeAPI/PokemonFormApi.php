<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\EvolutionChain;
use App\Entity\Item;
use App\Entity\Pokemon;
use App\Entity\PokemonForm;
use App\Entity\PokemonSpecy;
use App\Entity\VersionGroup;
use Doctrine\ORM\EntityManagerInterface;

//extract and transform evolution chains information into entities from pokeapi
class PokemonFormApi
{
    private PokeAPIGraphQLClient $client;
    private EntityManagerInterface $entityManager;

    /**
     * PokemonSpeciesApi constructor.
     * @param PokeAPIGraphQLClient $client
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(PokeAPIGraphQLClient $client, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    public function getPokemonForms(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_pokemonform {
    form_name
    form_order
    is_battle_only
    is_default
    is_mega
    name
    pokemon_v2_pokemon {
      name
    }
    pokemon_v2_versiongroup {
      name
    }
  }
}

GRAPHQL;

        $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $forms = [];
        foreach ($content['data']['pokemon_v2_pokemonform'] as $form) {
            $formEntity = new PokemonForm();
            $pokemon = $this->entityManager->getRepository(Pokemon::class)
                ->findOneBy(
                    [
                        'name' => $form['pokemon_v2_pokemon']['name']
                    ]
                );
            $versionGroup = $this->entityManager->getRepository(VersionGroup::class)
                ->findOneBy(
                    [
                        'name' => $form['pokemon_v2_versiongroup']['name']
                    ]
                );
            $formEntity->setPokemon($pokemon);
            $formEntity->setVersionGroup($versionGroup);
            $formEntity->setFormName($form['form_name']);
            $formEntity->setFormOrder($form['form_order']);
            $formEntity->setIsBattleOnly($form['is_battle_only']);
            $formEntity->setIsDefault($form['is_default']);
            $formEntity->setIsMega($form['is_mega']);
            $formEntity->setName($form['name']);

            $forms[] = $formEntity;
        }

        return $forms;
    }
}
