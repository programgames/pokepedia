<?php

namespace App\Controller\Admin;

use App\Entity\EggGroup;
use App\Entity\Generation;
use App\Entity\Item;
use App\Entity\ItemName;
use App\Entity\Machine;
use App\Entity\Move;
use App\Entity\MoveLearnMethod;
use App\Entity\Pokemon;
use App\Entity\PokemonMove;
use App\Entity\PokemonName;
use App\Entity\PokemonSpecy;
use App\Entity\SpecyName;
use App\Entity\VersionGroup;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Pokepedia');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Pokemon', 'fa fa-home');
        yield MenuItem::linkToCrud('Pokemons', 'fa fa-tags', Pokemon::class);
        yield MenuItem::linkToCrud('Espèce de Pokémon', 'fa fa-tags', PokemonSpecy::class);
        yield MenuItem::linkToCrud('Nom des Espèce de Pokémon', 'fa fa-tags', SpecyName::class);
        yield MenuItem::linkToCrud('Noms des Pokemons', 'fa fa-tags', PokemonName::class);
        yield MenuItem::linkToCrud('Groupe d\'oeufs', 'fa fa-tags', EggGroup::class);
        yield MenuItem::section('Général', 'fa fa-tags');
        yield MenuItem::linkToCrud('Generations', 'fa fa-tags', Generation::class);
        yield MenuItem::linkToCrud('Groupes de Versions', 'fa fa-tags', VersionGroup::class);
        yield MenuItem::section('Objets', 'fa fa-tags');
        yield MenuItem::linkToCrud('Objets', 'fa fa-tags', Item::class);
        yield MenuItem::linkToCrud('Nom Objets', 'fa fa-tags', ItemName::class);
        yield MenuItem::linkToCrud('Capsules techniques', 'fa fa-tags', Machine::class);
        yield MenuItem::section('Attaques', 'fa fa-tags');
        yield MenuItem::linkToCrud('Attaques', 'fa fa-tags', Move::class);
        yield MenuItem::linkToCrud('Attaques par pokémon', 'fa fa-tags', PokemonMove::class);
        yield MenuItem::linkToCrud('Méthodes d\'apprentissage', 'fa fa-tags', MoveLearnMethod::class);
    }
}
