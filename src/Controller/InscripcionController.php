<?php

namespace App\Controller;


use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\Inscripcion;
use App\Form\InscripcionForm;
use App\Repository\InscripcionRepository;
use App\Entity\Evento;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\EventoRepository;

#[Route('/inscripcion')]
final class InscripcionController extends AbstractController
{
    #[Route(name: 'app_inscripcion_index', methods: ['GET'])]
    public function index(InscripcionRepository $inscripcionRepository): Response
    {
        return $this->render('inscripcion/index.html.twig', [
            'inscripcions' => $inscripcionRepository->findAll(),
        ]);
    }

    /*
    #[Route('/new', name: 'app_inscripcion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $inscripcion = new Inscripcion();    
        $form = $this->createForm(InscripcionForm::class, $inscripcion);
        $form->handleRequest($request);
    
        // Obtener todos los eventos disponibles
        $eventos = $entityManager->getRepository(Evento::class)->findAll();
    
        // 🔹 Validación al enviar el formulario
        if ($form->isSubmitted() && $form->isValid()) {
            $evento = $inscripcion->getEvento();
    
            if ($evento) {
                // Forzar que Doctrine recargue el evento desde la BD
                $entityManager->refresh($evento);
    
                // Validación de acceso: si no es Super Admin y está cerrado, bloquear
                if (
                    !$this->isGranted('ROLE_SUPER_ADMIN') &&
                    !$evento->isInscripcionAbiertaParaAdmins()
                ) {
                    // 🔸 En lugar de flash, pasamos una variable
                    return $this->render('inscripcion/new.html.twig', [
                        'inscripcion' => $inscripcion,
                        'form' => $form->createView(),
                        'eventos' => $eventos,
                        'mostrarAlerta' => true, // 🔹 clave para el alert
                    ]);
                }
            }
    
            // Si pasa la validación, persistimos
            $entityManager->persist($inscripcion);
            $entityManager->flush();
    
            $this->addFlash('success', 'Inscripción creada con éxito.');
            return $this->redirectToRoute('app_inscripcion_new', [], Response::HTTP_SEE_OTHER);
        }
    
        // Render inicial o si el formulario no es válido
        return $this->render('inscripcion/new.html.twig', [
            'inscripcion' => $inscripcion,
            'form' => $form->createView(),
            'eventos' => $eventos,
            'mostrarAlerta' => false, // 🔹 default: no mostrar alert
        ]);
    }
    
    funcion new que funciona

    
    #[Route('/new', name: 'app_inscripcion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $inscripcion = new Inscripcion();    
        $form = $this->createForm(InscripcionForm::class, $inscripcion);
        $form->handleRequest($request);

        // Obtener todos los eventos disponibles
        $eventos = $entityManager->getRepository(Evento::class)->findAll();

        // 🔹 Validación al enviar el formulario
        if ($form->isSubmitted() && $form->isValid()) {
            $evento = $inscripcion->getEvento();

            if ($evento) {
                // Forzar que Doctrine recargue el evento desde la BD
                $entityManager->refresh($evento);

                // Validación de acceso: si no es Super Admin y está cerrado, bloquear
                if (
                    !$this->isGranted('ROLE_SUPER_ADMIN') &&
                    !$evento->isInscripcionAbiertaParaAdmins()
                ) {
                    // 🔸 En lugar de flash, pasamos una variable
                    return $this->render('inscripcion/new.html.twig', [
                        'inscripcion' => $inscripcion,
                        'form' => $form->createView(),
                        'eventos' => $eventos,
                        'mostrarAlerta' => true, // 🔹 clave para el alert
                    ]);
                }
            }

            // 🔹 NUEVO: actualizar el campo "deQueParticipa" del participante
            $deQueParticipa = $form->get('deQueParticipa')->getData();
            $participante = $inscripcion->getParticipante();

            if ($deQueParticipa && $participante) {
                $participante->setDeQueParticipa($deQueParticipa);
                $entityManager->persist($participante);
            }

            // Si pasa la validación, persistimos
            $entityManager->persist($inscripcion);
            $entityManager->flush();

            $this->addFlash('success', 'Inscripción creada con éxito.');
            return $this->redirectToRoute('app_inscripcion_new', [], Response::HTTP_SEE_OTHER);
        }

        // Render inicial o si el formulario no es válido
        return $this->render('inscripcion/new.html.twig', [
            'inscripcion' => $inscripcion,
            'form' => $form->createView(),
            'eventos' => $eventos,
            'mostrarAlerta' => false, // 🔹 default: no mostrar alert
        ]);
    }
    */


 /*   #[Route('/new', name: 'app_inscripcion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
      $inscripcion = new Inscripcion();    
      $form = $this->createForm(InscripcionForm::class, $inscripcion);
      $form->handleRequest($request);

      // Obtener todos los eventos disponibles
      $eventos = $entityManager->getRepository(Evento::class)->findAll();

      // 🔹 Validación al enviar el formulario
      if ($form->isSubmitted() && $form->isValid()) {
          $evento = $inscripcion->getEvento();

          if ($evento) {
              // Forzar que Doctrine recargue el evento desde la BD
              $entityManager->refresh($evento);

              // Validación de acceso: si no es Super Admin y está cerrado, bloquear
              if (
                  !$this->isGranted('ROLE_SUPER_ADMIN') &&
                  !$evento->isInscripcionAbiertaParaAdmins()
              ) {
                return $this->render('inscripcion/new.html.twig', [
                    'inscripcion' => $inscripcion,
                    'form' => $form->createView(),
                    'eventos' => $eventos,
                    'mostrarAlerta' => true,
                ]);
            }
        }

        // 🔹 Actualizar el campo "deQueParticipa" del participante
        $deQueParticipa = $form->get('deQueParticipa')->getData();
        $participante = $inscripcion->getParticipante();

        if ($deQueParticipa && $participante) {
            $participante->setDeQueParticipa($deQueParticipa);
            $entityManager->persist($participante);
        }

        // 🔹 Reemplazar ficha médica anterior si se sube una nueva
         @var UploadedFile $fichaFile 
        $fichaFile = $form->get('fichaMedicaFile')->getData();

        if ($fichaFile && $participante) {
            $originalFilename = pathinfo($fichaFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$fichaFile->guessExtension();

            try {
                $fichaFile->move(
                    $this->getParameter('fichas_medicas_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                $this->addFlash('error', 'No se pudo guardar la ficha médica.');
            }

            // Reemplazar la ficha médica del participante
            $participante->setFichaMedicaFilename($newFilename);
            $entityManager->persist($participante);
        }

        // Persistir la inscripción
        $entityManager->persist($inscripcion);
        $entityManager->flush();

        $this->addFlash('success', 'Inscripción creada con éxito.');
        return $this->redirectToRoute('app_inscripcion_new', [], Response::HTTP_SEE_OTHER);
    }

    // Render inicial o si el formulario no es válido
    return $this->render('inscripcion/new.html.twig', [
        'inscripcion' => $inscripcion,
        'form' => $form->createView(),
        'eventos' => $eventos,
        'mostrarAlerta' => false,
    ]);
}
*/

/*
#[Route('/new', name: 'app_inscripcion_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
{
    $inscripcion = new Inscripcion();    
    $form = $this->createForm(InscripcionForm::class, $inscripcion);
    $form->handleRequest($request);

    $eventos = $entityManager->getRepository(Evento::class)->findAll();

    if ($form->isSubmitted() && $form->isValid()) {

        // 🔹 Buscar participante por DNI ingresado
        $dni = $form->get('dniParticipante')->getData();
        $participante = $entityManager->getRepository(\App\Entity\Participante::class)
            ->findOneBy(['dni' => $dni]);

        if (!$participante) {
            $form->get('dniParticipante')->addError(
                new \Symfony\Component\Form\FormError('No se encontró un participante con ese DNI.')
            );
            return $this->render('inscripcion/new.html.twig', [
                'inscripcion' => $inscripcion,
                'form' => $form->createView(),
                'eventos' => $eventos,
                'mostrarAlerta' => false,
            ]);
        }

        // Asociar participante encontrado
        $inscripcion->setParticipante($participante);

        // 🔹 Validación de evento (tu lógica actual)
        $evento = $inscripcion->getEvento();
        if ($evento) {
            $entityManager->refresh($evento);
            if (
                !$this->isGranted('ROLE_SUPER_ADMIN') &&
                !$evento->isInscripcionAbiertaParaAdmins()
            ) {
                return $this->render('inscripcion/new.html.twig', [
                    'inscripcion' => $inscripcion,
                    'form' => $form->createView(),
                    'eventos' => $eventos,
                    'mostrarAlerta' => true,
                ]);
            }
        }

        // 🔹 Guardar "deQueParticipa"
        $deQueParticipa = $form->get('deQueParticipa')->getData();
        if ($deQueParticipa) {
            $participante->setDeQueParticipa($deQueParticipa);
            $entityManager->persist($participante);
        }

        // 🔹 Guardar ficha médica
         @var UploadedFile $fichaFile 
        $fichaFile = $form->get('fichaMedicaFile')->getData();
        if ($fichaFile) {
            $originalFilename = pathinfo($fichaFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$fichaFile->guessExtension();

            try {
                $fichaFile->move(
                    $this->getParameter('fichas_medicas_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                $this->addFlash('error', 'No se pudo guardar la ficha médica.');
            }

            $participante->setFichaMedicaFilename($newFilename);
            $entityManager->persist($participante);
        }

        // 🔹 Guardar inscripción
        $entityManager->persist($inscripcion);
        $entityManager->flush();

        $this->addFlash('success', 'Inscripción creada con éxito.');
        return $this->redirectToRoute('app_inscripcion_new', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('inscripcion/new.html.twig', [
        'inscripcion' => $inscripcion,
        'form' => $form->createView(),
        'eventos' => $eventos,
        'mostrarAlerta' => false,
    ]);
}*/
#[Route('/new', name: 'app_inscripcion_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
{
    $inscripcion = new Inscripcion();    
    $form = $this->createForm(InscripcionForm::class, $inscripcion);
    $form->handleRequest($request);

    $eventos = $entityManager->getRepository(Evento::class)->findAll();

    if ($form->isSubmitted() && $form->isValid()) {

        // 🔹 Buscar participante por DNI ingresado
        $dni = $form->get('dniParticipante')->getData();
        $participante = $entityManager->getRepository(\App\Entity\Participante::class)
            ->findOneBy(['dni' => $dni]);

        if (!$participante) {
            $form->get('dniParticipante')->addError(
                new \Symfony\Component\Form\FormError('No se encontró un participante con ese DNI.')
            );
            return $this->render('inscripcion/new.html.twig', [
                'inscripcion' => $inscripcion,
                'form' => $form->createView(),
                'eventos' => $eventos,
                'mostrarAlerta' => false,
            ]);
        }

        // Asociar participante encontrado
        $inscripcion->setParticipante($participante);

        // Setear nombre y apellido en los campos de solo lectura
     
        // 🔹 Validación de evento (tu lógica actual)
        $evento = $inscripcion->getEvento();
        if ($evento) {
            $entityManager->refresh($evento);
            if (
                !$this->isGranted('ROLE_SUPER_ADMIN') &&
                !$evento->isInscripcionAbiertaParaAdmins()
            ) {
                return $this->render('inscripcion/new.html.twig', [
                    'inscripcion' => $inscripcion,
                    'form' => $form->createView(),
                    'eventos' => $eventos,
                    'mostrarAlerta' => true,
                ]);
            }
        }

        // 🔹 Guardar "deQueParticipa"
        $deQueParticipa = $form->get('deQueParticipa')->getData();
        if ($deQueParticipa) {
            $participante->setDeQueParticipa($deQueParticipa);
            $entityManager->persist($participante);
        }

        // 🔹 Guardar ficha médica
        /** @var UploadedFile $fichaFile */
        $fichaFile = $form->get('fichaMedicaFile')->getData();
        if ($fichaFile) {
            $originalFilename = pathinfo($fichaFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$fichaFile->guessExtension();

            try {
                $fichaFile->move(
                    $this->getParameter('fichas_medicas_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                $this->addFlash('error', 'No se pudo guardar la ficha médica.');
            }

            $participante->setFichaMedicaFilename($newFilename);
            $entityManager->persist($participante);
        }

        // 🔹 Guardar inscripción
        $entityManager->persist($inscripcion);
        $entityManager->flush();

        $this->addFlash('success', 'Inscripción creada con éxito.');
        return $this->redirectToRoute('app_inscripcion_new', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('inscripcion/new.html.twig', [
        'inscripcion' => $inscripcion,
        'form' => $form->createView(),
        'eventos' => $eventos,
        'mostrarAlerta' => false,
    ]);
}

#[Route('/buscar-participante', name: 'buscar_participante', methods: ['GET'])]
public function buscarParticipante(Request $request, EntityManagerInterface $em): Response
{
    $dni = $request->query->get('dni');
    $participante = $em->getRepository(\App\Entity\Participante::class)
        ->findOneBy(['dni' => $dni]);

    if (!$participante) {
        return $this->json(['found' => false]);
    }

    return $this->json([
        'found' => true,
        'nombre' => $participante->getNombre(),
        'apellido' => $participante->getApellido()
    ]);
}


    
     #[Route('/{id}', name: 'app_inscripcion_show', methods: ['GET'])]
    public function show(Inscripcion $inscripcion): Response
    {
        return $this->render('inscripcion/show.html.twig', [
            'inscripcion' => $inscripcion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_inscripcion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Inscripcion $inscripcion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InscripcionForm::class, $inscripcion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_inscripcion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('inscripcion/edit.html.twig', [
            'inscripcion' => $inscripcion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_inscripcion_delete', methods: ['POST'])]
    public function delete(Request $request, Inscripcion $inscripcion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$inscripcion->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($inscripcion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_inscripcion_index', [], Response::HTTP_SEE_OTHER);
    }
}
