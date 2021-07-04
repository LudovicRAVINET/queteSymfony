<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Actor;
use App\Form\ActorType;
use App\Repository\ActorRepository;
use Symfony\Component\HttpFoundation\Request;

class ActorController extends AbstractController
{
    /**
     * @Route("/actor", name="actor")
     */
    public function index(ActorRepository $actorRepository): Response
    {
        $actors = $actorRepository->findAll();
        
        return $this->render('actor/index.html.twig', [
            'actors' => $actors,
        ]);
    }

    /**
     * @Route("/actor/show/{id}", name="actor_show", methods={"GET"})
     */
    public function show(Actor $actor): Response
    {
        $programs = $actor->getPrograms();

        return $this->render('actor/show.html.twig', [
            'actor' => $actor,
            'programs' => $programs
        ]);
    }

     /**
     * @Route("/actor/new", name="actor_new")
     */
    public function new(Request $request): Response
    {
        $actor = new Actor();
        $form = $this->createForm(ActorType::class, $actor);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Deal with the submitted data
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($actor);
            $entityManager->flush();

            // Once the form is submitted, valid and the data inserted in database, you can define the success flash message
            $this->addFlash('success', 'The new actor has been created');

            return $this->redirectToRoute('actor');
        }

        return $this->render('actor/new.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
