<?php

namespace App\Controller;

use App\Entity\Evento;
use App\Form\EventoForm;
use App\Repository\EventoRepository;
use App\Repository\ParticipanteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

#[Route('/evento')]
final class EventoController extends AbstractController
{
    #[Route(name: 'app_evento_index', methods: ['GET'])]
    public function index(EventoRepository $eventoRepository): Response
    {
        return $this->render('evento/index.html.twig', [
            'eventos' => $eventoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_evento_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evento = new Evento();
        $form = $this->createForm(EventoForm::class, $evento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($evento);
            $entityManager->flush();

            return $this->redirectToRoute('app_evento_index');
        }

        return $this->render('evento/new.html.twig', [
            'evento' => $evento,
            'form' => $form->createView(),
        ]);
    }

    /* #[Route('/{id}/exportar', name: 'app_evento_exportar_excel')]
    public function exportarExcel(int $id, EventoRepository $eventoRepository): Response
    {
        // El método find() ya carga las relaciones lazy-loaded. No es necesario un método especial.
        $evento = $eventoRepository->find($id);

        if (!$evento) {
            throw $this->createNotFoundException('Evento no encontrado.');
        }
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Participantes del evento: ' . $evento->getNombre());
        $sheet->mergeCells('A1:L1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $sheet->fromArray([
            'Apellido', 'Nombre', 'Nombre de credencial', 'DNI', 'Edad', 'Sexo', 'Fecha de nacimiento',
            'Celular', 'De que participa', 'Localidad', 'Dieta', 'Dificultades', 'Medicación','Obra social N° afiliado','Observaciones','Tutor 1', 'Teléfono Tutor 1', 'Tutor 2', 'Teléfono Tutor 2'
        ], null, 'A2');
        
        $row = 3;
        foreach ($evento->getInscripcions() as $inscripcion) {
            $p = $inscripcion->getParticipante();
            $sheet->setCellValue('A' . $row, $p->getApellido());
            $sheet->setCellValue('B' . $row, $p->getNombre());
            $sheet->setCellValue('C' . $row, $p->getNombreCredencial()); // Ahora justo después de nombre
            $sheet->setCellValue('D' . $row, $p->getDni());
            $sheet->setCellValue('E' . $row, $p->getEdad());
            $sheet->setCellValue('F' . $row, $p->getSexo());
            $sheet->setCellValue('G' . $row, $p->getFechaNacimiento()?->format('d/m/Y'));
            $sheet->setCellValue('H' . $row, $p->getCelular());
            $sheet->setCellValue('I' . $row, $p->getDeQueParticipa());
            $sheet->setCellValue('J' . $row, $p->getLocalidad()?->getNombre());
        
            $dieta = $p->getDieta();
            $sheet->setCellValue('K' . $row, $dieta === 'otro' ? $p->getOtraDieta() : ucfirst($dieta ?? 'No especificada'));
        
            $dificultad = $p->getDificultades();
            $sheet->setCellValue('L' . $row, $dificultad === 'otro' ? $p->getOtrasDificultades() : ucfirst($dificultad ?? 'No especificada'));
        
            $medicacion = $p->getMedicacion();
            $sheet->setCellValue('M' . $row, $medicacion === 'otro' ? $p->getOtraMedicacion() : ucfirst($medicacion ?? 'No especificada'));

            $sheet->setCellValue('N' . $row, $p->getObraSocialnumeroAfiliado());
            $sheet->setCellValue('O' . $row, $p->getObservaciones());
            $sheet->setCellValue('P' . $row, $p->getTutor1());
            $sheet->setCellValue('Q' . $row, $p->getTelefonoTutor1());
            $sheet->setCellValue('R' . $row, $p->getTutor2());
            $sheet->setCellValue('S' . $row, $p->getTelefonoTutor2());
            
        
            $row++;
        }
        
        foreach (range('A', 'S') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        

        $writer = new Xlsx($spreadsheet);
        $response = new StreamedResponse(fn() => $writer->save('php://output'));
        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'evento_' . $evento->getNombre() . '.xlsx');

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }
    */

    #[Route('/{id}/exportar', name: 'app_evento_exportar_excel')]
    public function exportarExcel(int $id, EventoRepository $eventoRepository): Response
    {
        $evento = $eventoRepository->find($id);

        if (!$evento) {
            throw $this->createNotFoundException('Evento no encontrado.');
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Participantes del evento: ' . $evento->getNombre());
        $sheet->mergeCells('A1:S1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $sheet->fromArray([
            'Apellido', 'Nombre', 'Nombre de credencial', 'DNI', 'Edad', 'Sexo', 'Fecha de nacimiento',
            'Celular', 'De que participa', 'Localidad', 'Dieta', 'Dificultades', 'Medicación',
            'Obra social N° afiliado', 'Observaciones', 'Tutor 1', 'Teléfono Tutor 1', 'Tutor 2', 'Teléfono Tutor 2'
        ], null, 'A2');

        // 🔄 Unificamos participantes desde Inscripción y desde relación directa
        $participantesMap = [];

        foreach ($evento->getInscripcions() as $inscripcion) {
            $p = $inscripcion->getParticipante();
            $participantesMap[$p->getId()] = $p;
        }

        foreach ($evento->getParticipantes() as $p) {
            $participantesMap[$p->getId()] = $p;
        }

        $participantes = array_values($participantesMap);

        // 🔠 Ordenamos por nombre de localidad
        usort($participantes, fn($a, $b) => strcmp(
            $a->getLocalidad()?->getNombre() ?? '',
            $b->getLocalidad()?->getNombre() ?? ''
        ));

        $row = 3;
        foreach ($participantes as $p) {
            $sheet->setCellValue('A' . $row, $p->getApellido());
            $sheet->setCellValue('B' . $row, $p->getNombre());
            $sheet->setCellValue('C' . $row, $p->getNombreCredencial());
            $sheet->setCellValue('D' . $row, $p->getDni());
            $sheet->setCellValue('E' . $row, $p->getEdad());
            $sheet->setCellValue('F' . $row, $p->getSexo());
            $sheet->setCellValue('G' . $row, $p->getFechaNacimiento()?->format('d/m/Y'));
            $sheet->setCellValue('H' . $row, $p->getCelular());
            $sheet->setCellValue('I' . $row, $p->getDeQueParticipa());
            $sheet->setCellValue('J' . $row, $p->getLocalidad()?->getNombre());

            $dieta = $p->getDieta();
            $sheet->setCellValue('K' . $row, $dieta === 'otro' ? $p->getOtraDieta() : ucfirst($dieta ?? 'No especificada'));

            $dificultad = $p->getDificultades();
            $sheet->setCellValue('L' . $row, $dificultad === 'otro' ? $p->getOtrasDificultades() : ucfirst($dificultad ?? 'No especificada'));

            $medicacion = $p->getMedicacion();
            $sheet->setCellValue('M' . $row, $medicacion === 'otro' ? $p->getOtraMedicacion() : ucfirst($medicacion ?? 'No especificada'));

            $sheet->setCellValue('N' . $row, $p->getObraSocialnumeroAfiliado());
            $sheet->setCellValue('O' . $row, $p->getObservaciones());
            $sheet->setCellValue('P' . $row, $p->getTutor1());
            $sheet->setCellValue('Q' . $row, $p->getTelefonoTutor1());
            $sheet->setCellValue('R' . $row, $p->getTutor2());
            $sheet->setCellValue('S' . $row, $p->getTelefonoTutor2());

            $row++;
        }

        foreach (range('A', 'S') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $response = new StreamedResponse(fn() => $writer->save('php://output'));
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'evento_' . $evento->getNombre() . '.xlsx'
        );

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }
    

    #[Route('/{id}', name: 'app_evento_show', methods: ['GET'])]
    public function show(Evento $evento, ParticipanteRepository $repo): Response
    {    
        /** @var Admin $admin */
        $admin = $this->getUser();

        // Obtenemos participantes según el evento y rol
        $participantes = $repo->findByEventoAndAdmin($evento, $admin);

        usort($participantes, function ($a, $b) {
            $locA = $a->getLocalidad()?->getNombre() ?? '';
            $locB = $b->getLocalidad()?->getNombre() ?? '';
            return strcmp($locA, $locB);
        });
        

        return $this->render('evento/show.html.twig', [
            'evento' => $evento,
            'participantes' => $participantes,
        ]   );
    }


    #[Route('/{id}/edit', name: 'app_evento_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evento $evento, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventoForm::class, $evento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_evento_index');
        }

        return $this->render('evento/edit.html.twig', [
            'evento' => $evento,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_evento_delete', methods: ['POST'])]
    public function delete(Request $request, Evento $evento, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $evento->getId(), $request->request->get('_token'))) {
            // El Evento ya no tiene un método getParticipantes()
            // Se debe eliminar primero la entidad de unión Inscripcion
            foreach ($evento->getInscripcions() as $inscripcion) {
                $entityManager->remove($inscripcion);
            }
            
            $entityManager->remove($evento);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evento_index');
    }

   #[Route('/evento/{id}/toggle-inscripcion', name: 'evento_toggle_inscripcion', methods: ['POST'])]
public function toggleInscripcion(
    Request $request,
    Evento $evento,
    EntityManagerInterface $entityManager
): Response {
    $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

    if (!$this->isCsrfTokenValid('toggle_insc_'.$evento->getId(), $request->request->get('_token'))) {
        throw $this->createAccessDeniedException();
    }

    $evento->setInscripcionAbiertaParaAdmins(
        !$evento->isInscripcionAbiertaParaAdmins()
    );

    $entityManager->flush();

    $estado = $evento->isInscripcionAbiertaParaAdmins() ? 'abiertas' : 'cerradas';
    $this->addFlash('success', "Inscripciones ahora $estado para admins.");

    return $this->redirectToRoute('app_evento_index');
}

    
}
