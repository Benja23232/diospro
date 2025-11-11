<?php

namespace App\Controller;

use App\Entity\Localidad;
use App\Form\LocalidadForm;
use App\Repository\LocalidadRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/localidad')]
final class LocalidadController extends AbstractController
{
    #[Route(name: 'app_localidad_index', methods: ['GET'])]
    public function index(LocalidadRepository $localidadRepository): Response
    {
        return $this->render('localidad/index.html.twig', [
            'localidads' => $localidadRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_localidad_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $localidad = new Localidad();
        $form = $this->createForm(LocalidadForm::class, $localidad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($localidad);
            $entityManager->flush();

            return $this->redirectToRoute('app_localidad_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('localidad/new.html.twig', [
            'localidad' => $localidad,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_localidad_show', methods: ['GET'])]
    public function show(Localidad $localidad): Response
    {
        return $this->render('localidad/show.html.twig', [
            'localidad' => $localidad,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_localidad_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Localidad $localidad, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LocalidadForm::class, $localidad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_localidad_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('localidad/edit.html.twig', [
            'localidad' => $localidad,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_localidad_delete', methods: ['POST'])]
    public function delete(Request $request, Localidad $localidad, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$localidad->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($localidad);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_localidad_index', [], Response::HTTP_SEE_OTHER);
    }
}
