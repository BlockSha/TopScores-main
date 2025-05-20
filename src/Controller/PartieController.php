<?php

namespace App\Controller;

use App\Entity\Partie;
use App\Form\PartieForm;
use App\Repository\PartieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/partie')]
#[IsGranted('ROLE_USER')]
final class PartieController extends AbstractController
{
    #[Route(name: 'app_partie_index', methods: ['GET'])]
    public function index(PartieRepository $partieRepository): Response
    {
        $parties = $partieRepository->findBy(['user' => $this->getUser()]);
        return $this->render('partie/index.html.twig', [
            'parties' => $parties,
        ]);
    }

    #[Route('/new', name: 'app_partie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $partie = new Partie($this->getUser());
        $form = $this->createForm(PartieForm::class, $partie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($partie);
            $entityManager->flush();

            return $this->redirectToRoute('app_partie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('partie/new.html.twig', [
            'partie' => $partie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_partie_show', methods: ['GET'])]
    public function show(Partie $partie): Response
    {
        return $this->render('partie/show.html.twig', [
            'partie' => $partie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_partie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Partie $partie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PartieForm::class, $partie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_partie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('partie/edit.html.twig', [
            'partie' => $partie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_partie_delete', methods: ['POST'])]
    public function delete(Request $request, Partie $partie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$partie->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($partie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_partie_index', [], Response::HTTP_SEE_OTHER);
    }
}
