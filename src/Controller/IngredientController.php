<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

use Knp\Component\Pager\PaginatorInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientController extends AbstractController
{
 
    /**
     * This controller dispay all ingredients
     *
     * @param IngredientRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */

    #[Route('/ingredient', name: 'app_ingredient', methods: ['GET'])]
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        
        // dd($ingredients); c'est égale à un super console log dans le navigateur (visualiser les données BDD)

        $ingredients = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients
        ]);
    }

    /**
     * This controller show a form which create an ingredient
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/ingredient/nouveau', 'ingredient.new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager
        ): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
     
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();
            // dd($form);
            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre ingredient a été créé avec succès!'
            );

            return $this->redirectToRoute('app_ingredient');
        }

        return $this->render('pages/ingredient/new.html.twig', [
            'form' =>  $form->createView(),
        ]);
    }

    
        //? technique de recherche directement avec l'ingredient(bonne pratique symfony)
    #[Route('/ingredient/edition/{id}', 'ingredient.edit', methods: ['GET', 'POST'])]
    public function edit( 
        Ingredient  $ingredient,
        Request $request,
        EntityManagerInterface $manager
        ) : Response
    {
        // dd( $ingredient);
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);
     
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();
            // dd($form);
            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre ingredient a été modifié avec succès!'
            );

            return $this->redirectToRoute('app_ingredient');
        }

        return $this->render('pages/ingredient/edit.html.twig', [
            'form' => $form->createView()
        ]);
        //?------------------------------------------------------

        // ? technique de recherche par id
    // #[Route('/ingredient/edition/{id}', 'ingredient.edit', methods: ['GET', 'POST'])]
    // public function edit(IngredientRepository $repository, int $id) : Response
    // {
    //     $ingredient = $repository ->findOneBy(["id" => $id]);
    //     $form = $this->createForm(IngredientType::class, $ingredient);

    //     return $this->render('pages/ingredient/edit.html.twig', [
    //         'form' => $form->createView()
    //     ]);
        //? -----------------------------   
    }

    #[Route('/ingredient/suppression/{id}', 'ingredient.delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, Ingredient $ingredient) : Response 
    {

        if (!$ingredient) {
            $this->addFlash(
                'warning',
                'Votre ingredient n\'a pas été touvé.'
            );
            
            return $this->redirectToRoute('app_ingredient');
        }

        $manager->remove($ingredient);
        $manager->flush();

        $this->addFlash(
            'success',
            'Votre ingredient a été supprimé avec succès!'
        );
        
        return $this->redirectToRoute('app_ingredient');
    }
}
