<?php

namespace App\Controller\api;

use App\DTO\PaginationDTO;
use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;


class RecipesController extends AbstractController
{

    #[Route('/api/recipes', methods: ['GET'])]
    public function index(
        RecipeRepository $recipeRepository,
        #[MapQueryString()]
        PaginationDTO $paginationDTO)
    {
        $recipes = $recipeRepository->pagineRecipes($paginationDTO->page);
        return $this->json($recipes, 200, [], [
            'groups' => ['recipes.index']
        ]);
    }

    #[Route('/api/recipes/{id}', requirements: ['id' => Requirement::DIGITS])]
    public function show(Recipe $recipe)
    {
        return $this->json($recipe, 200, [], [
            'groups' => ['recipes.index', 'recipes.show']
        ]);
    }

    #[Route('/api/recipes/', methods: ['POST'])]
    public function create(
        #[MapRequestPayload(
            'json',
            serializationContext: ['groups' => ['recipes.create']],
        )]
        Recipe $recipe,
        EntityManagerInterface $em
    ) {
        $recipe->setCreatedAt(new \DateTimeImmutable());
        $recipe->setUpdatedAt(new \DateTimeImmutable());
        $em->persist($recipe);
        $em->flush();
        return $this->json($recipe, 201, [], [
            'groups' => ['recipes.index', 'recipes.show']
        ]);
    }
}
