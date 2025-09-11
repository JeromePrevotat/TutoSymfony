<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RecipeController extends AbstractController
{
    #[Route('/recipes/', name: 'recipe.index')]
    public function index(Request $request, RecipeRepository $recipeRepository, EntityManagerInterface $em): Response
    {
        $recipes = $recipeRepository->findAll();
        // $recipes = $recipeRepository->findWithDurationLowerThan(20);
        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes
        ]);
    }

    #[Route(
        '/recipes/{slug}-{id}',
        name: 'recipe.show',
        requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+']
    )]
    public function show(Request $request, string $slug, int $id, RecipeRepository $recipeRepository): Response
    {
        $recipe = $recipeRepository->find($id);
        if ($recipe->getSlug() !== $slug) {
            return $this->redirectToRoute('recipe.show', [
                'id' => $id,
                'slug' => $recipe->getSlug()
            ]);
        }
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    #[Route(
        '/recipes/{id}/edit',
        name: 'recipe.edit',
        requirements: ['id' => '\d+'],
        methods: ['GET', 'POST']
    )]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Modification successful');
            return $this->redirectToRoute('recipe.index');
        }
        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form
        ]);
    }

    #[Route(
        '/recipes/add',
        name: 'recipe.add'
    )]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success', 'Creation successful');
            return $this->redirectToRoute('recipe.index');
        }
        return $this->render('recipe/add.html.twig', [
            'form' => $form
        ]);
    }

    #[Route(
        '/recipes/{id}/delete',
        name: 'recipe.delete',
        requirements: ['id' => '\d+'],
        methods: ['DELETE']
    )]
    public function delete(Request $request, Recipe $recipe, EntityManagerInterface $em): Response
    {
        $em->remove($recipe);
        $em->flush();
        $this->addFlash('success', 'Deletion successful');
        return $this->redirectToRoute('recipe.index');
    }
}
