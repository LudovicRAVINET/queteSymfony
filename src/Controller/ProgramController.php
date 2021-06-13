<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Form\ProgramType;
use App\Form\CommentType;
use App\Service\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
     * @Route("/programs", name="program_")
     */
class ProgramController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();
        
        return $this->render('program/index.html.twig', [
            'programs' => $programs,
         ]);
    }

     /**
     * @Route("/new", name="new")
     */
    public function new(Request $request, Slugify $slugify): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Deal with the submitted data
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($program);
            $entityManager->flush();
            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/new.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/{slug}", methods={"GET"}, name="show")
     */
    public function show(Program $program): Response
    {
        $seasons = $program->getSeasons();

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons
        ]);
    }

    /**
     * @Route("/{slug}/seasons/{season}", methods={"GET"}, name="season_show")
     * 
     */
    public function showSeason(Program $program, Season $season): Response
    {
        $episodes = $season->getEpisodes();

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes
        ]);
    }

    /**
     * @Route("/{program_slug}/seasons/{season}/episodes/{episode_slug}", methods={"GET","POST"}, name="episode_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program_slug": "slug"}})
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episode_slug": "slug"}})
     */
    public function showEpisode(Request $request, Program $program, Season $season, Episode $episode): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Deal with the submitted data
            $comment->setAuthor($this->getUser());
            $comment->setEpisode($episode);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            /*var_dump($program->getSlug());
            var_dump($season->getId());
            var_dump($episode->getSlug()); die;*/
            return $this->redirectToRoute('program_episode_show', [
                'program_slug' => $program->getSlug(),
                'season' => $season->getId(),
                'episode_slug' => $episode->getSlug()
            ]);
        }

        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
            'form' => $form->createView()
        ]);
    }
}
