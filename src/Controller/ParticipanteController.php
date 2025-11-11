<?php

namespace App\Controller;

use App\Entity\Participante;
use App\Form\ParticipanteForm;
use App\Repository\ParticipanteRepository;
use App\Repository\EventoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security;
use App\Form\BuscarPorDniForm;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/participante')]
final class ParticipanteController extends AbstractController
{        
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route(name: 'app_participante_index', methods: ['GET'])]
    public function index(ParticipanteRepository $participanteRepository): Response
    {
        $admin = $this->security->getUser();

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $participantes = $participanteRepository->findAll();
        } else {
            $participantes = $participanteRepository->findBy([
                'localidad' => $admin->getLocalidad()
            ]);
        }
         
        usort($participantes, function ($a, $b) {
            $locA = $a->getLocalidad()?->getNombre() ?? '';
            $locB = $b->getLocalidad()?->getNombre() ?? '';
            return strcmp($locA, $locB);
        });        
 

        return $this->render('participante/index.html.twig', [
            'participantes' => $participantes,
        ]);
    }

    #[Route('/new', name: 'app_participante_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $admin = $this->security->getUser();
        $participante = new Participante();
        
        if ($admin && $admin->getLocalidad()) {
            $participante->setLocalidad($admin->getLocalidad());
        }

        $form = $this->createForm(ParticipanteForm::class, $participante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Lógica añadida para manejar la subida del archivo de la ficha médica
            $fichaMedicaFile = $form->get('fichaMedicaFile')->getData();

            if ($fichaMedicaFile) {
                $originalFilename = pathinfo($fichaMedicaFile->getClientOriginalName(), PATHINFO_FILENAME);
                // Usamos SluggerInterface para crear un nombre de archivo seguro
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$fichaMedicaFile->guessExtension();

                try {
                    // Movemos el archivo al directorio configurado en services.yaml
                    $fichaMedicaFile->move(
                        $this->getParameter('fichas_medicas_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Manejar la excepción si la subida falla
                    $this->addFlash('error', 'No se pudo subir la ficha médica.');
                    return $this->redirectToRoute('app_participante_new');
                }

                // Guardamos el nombre del archivo en la entidad para la base de datos
                $participante->setFichaMedicaFileName($newFilename);
            }
             
            dump($participante->getEvento());
            $evento = $participante->getEvento();

          
            
            $entityManager->persist($participante);
            $entityManager->flush();

            $this->addFlash('success', 'Participante creado correctamente.');

            return $this->redirectToRoute('app_participante_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('participante/new.html.twig', [
            'participante' => $participante,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/buscar', name: 'app_participante_buscar', methods: ['GET', 'POST'])]
    public function buscarPorDni(Request $request, ParticipanteRepository $participanteRepository): Response
    {
        $form = $this->createForm(BuscarPorDniForm::class);
        $form->handleRequest($request);
        $participante = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $dni = $form->get('dni')->getData();
            $participante = $participanteRepository->findOneBy(['dni' => $dni]);

            if (!$participante) {
                $this->addFlash('danger', 'No se encontró ningún participante con ese DNI.');
            }
        }

        return $this->render('participante/buscar.html.twig', [
            'form' => $form->createView(),
            'participante' => $participante,
        ]);
    }

    #[Route('/{id}', name: 'app_participante_show', methods: ['GET'])]
    public function show(Participante $participante): Response
    {
        return $this->render('participante/show.html.twig', [
            'participante' => $participante,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_participante_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Participante $participante, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $oldFilename = $participante->getFichaMedicaFileName();
    
        $form = $this->createForm(ParticipanteForm::class, $participante);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $fichaMedicaFile = $form->get('fichaMedicaFile')->getData();
    
            if ($fichaMedicaFile) {
                // Borramos el archivo antiguo si existe
                if ($oldFilename) {
                    $oldPath = $this->getParameter('fichas_medicas_directory') . '/' . $oldFilename;
                    if (is_file($oldPath)) {
                        @unlink($oldPath);
                    }
                }
    
                $originalFilename = pathinfo($fichaMedicaFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $fichaMedicaFile->guessExtension();
    
                try {
                    $fichaMedicaFile->move(
                        $this->getParameter('fichas_medicas_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'No se pudo subir la ficha médica.');
                    return $this->redirectToRoute('app_participante_edit', ['id' => $participante->getId()]);
                }
    
                $participante->setFichaMedicaFileName($newFilename);
            } else {
                // Conservamos el archivo anterior
                $participante->setFichaMedicaFileName($oldFilename);
            }
    
            $entityManager->flush();
    
            $this->addFlash('success', 'Participante actualizado correctamente.');
            return $this->redirectToRoute('app_participante_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('participante/edit.html.twig', [
            'participante' => $participante,
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/{id}', name: 'app_participante_delete', methods: ['POST'])]
    public function delete(Request $request, Participante $participante, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $participante->getId(), $request->request->get('_token'))) {
            // Lógica para eliminar el archivo del servidor cuando se borra el participante
            if ($participante->getFichaMedicaFileName()) {
                $fileToDelete = $this->getParameter('fichas_medicas_directory') . '/' . $participante->getFichaMedicaFileName();
                if (file_exists($fileToDelete)) {
                    unlink($fileToDelete);
                }
            }
            
            // Eliminamos el participante. Doctrine se encarga automáticamente
            // de eliminar la relación en la tabla intermedia con los eventos.
            // Por lo tanto, el bucle foreach original no es necesario.
            $entityManager->remove($participante);
            $entityManager->flush();

            $this->addFlash('success', 'Participante eliminado correctamente.');
        }

        return $this->redirectToRoute('app_participante_index', [], Response::HTTP_SEE_OTHER);
    }
}
