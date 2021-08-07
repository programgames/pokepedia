<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\Generation;
use App\Entity\PokemonForm;
use App\Entity\PokemonFormName;
use App\Entity\Region;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

//extract and transform generation information into entities from pokeapi
class PokemonFormNameApi
{
    private PokeAPIGraphQLClient $client;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(PokeAPIGraphQLClient $client,EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->client = $client;
    }

    public function getPokemonFormNames(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_pokemonformname(where: {language_id: {_in: [5, 9]}}) {
    pokemon_v2_pokemonform {
      name
    }
    language_id
    name
    pokemon_name
  }
}


GRAPHQL;



        $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $pokemonFormNames = [];
        foreach ($content['data']['pokemon_v2_pokemonformname'] as $pokemonFormName) {
            $pokemonFormNameEntity = new PokemonFormName();
            $pokemonFormNameEntity->setName($pokemonFormName['name']);
            $pokemonFormNameEntity->setLanguage($pokemonFormName['language_id']);
            $pokemonFormNameEntity->setPokemonName($pokemonFormName['pokemon_name']);
            $form = $this->em->getRepository(PokemonForm::class)
                ->findOneBy(['name' => $pokemonFormName['pokemon_v2_pokemonform']['name']]);
            $pokemonFormNameEntity->setPokemonForm($form);
            $pokemonFormNames[] = $pokemonFormNameEntity;
        }

        return $pokemonFormNames;
    }
}
