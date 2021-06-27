<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }

    public function navbarTop(CategoryRepository $categoryRepository): Response
    {
        return $this->render('layout/navbartop.html.twig', [
            'categories' => $categoryRepository->findBy([], ['id' => 'DESC'])
        ]);
    }

    /**
     * @Route("/change_locale", name="change_locale")
     */
    public function language(Request $request, string $_locale): Response
    {
        $request->setLocale($_locale);        

        return $this->redirectToRoute('app_index');
    }
}
