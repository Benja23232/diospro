<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Evento;
use App\Entity\Inscripcion;
use App\Entity\Localidad;
use App\Entity\Participante;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // ------------------------------------
        // 1. LOCALIDADES
        // ------------------------------------
        $localidadesData = [
            16 => 'Ameghino',
            22 => 'Pastoral',
            25 => 'Trenque Lauquen',
            28 => 'Tres Lomas',
        ];

        $localidades = [];
        foreach ($localidadesData as $id => $nombre) {
            $localidad = new Localidad();
            $localidad->setNombre($nombre);
            $manager->persist($localidad);
            $localidades[$id] = $localidad;
        }
        $manager->flush();

        // ------------------------------------
        // 2. ADMINISTRADORES
        // ------------------------------------
        $adminSuper = new Admin();
        $adminSuper->setEmail('superadmin@diospro.com');
        $adminSuper->setRoles(['ROLE_SUPER_ADMIN']);
        $adminSuper->setLocalidad($localidades[22]);
        $adminSuper->setPassword(
            $this->passwordHasher->hashPassword($adminSuper, 'test1234')
        );
        $manager->persist($adminSuper);

        $adminEstandar = new Admin();
        $adminEstandar->setEmail('admin@diospro.com');
        $adminEstandar->setRoles(['ROLE_ADMIN']);
        $adminEstandar->setLocalidad($localidades[25]);
        $adminEstandar->setPassword(
            $this->passwordHasher->hashPassword($adminEstandar, 'prueba1234')
        );
        $manager->persist($adminEstandar);

        // ------------------------------------
        // 3. PARTICIPANTES
        // ------------------------------------
        $participantesData = [
            ['Ana', 'García', '12345678', 'Femenino', '1990-05-15', 34, '555-1234'],
            ['Juan', 'Pérez', '87654321', 'Masculino', '1985-11-20', 39, '555-5678'],
            ['Sofía', 'Rodríguez', '11223344', 'Femenino', '2001-03-01', 23, '555-9012'],
            ['Carlos', 'López', '44332211', 'Masculino', '1978-08-25', 46, '555-3456'],
            ['Elena', 'Martínez', '55667788', 'Femenino', '1999-01-10', 25, '555-7890'],
        ];

        $participantes = [];
        foreach ($participantesData as $data) {
            $participante = new Participante();
            $participante->setNombre($data[0]);
            $participante->setApellido($data[1]);
            $participante->setDni($data[2]);
            $participante->setSexo($data[3]);
            $participante->setFechaNacimiento(new DateTime($data[4]));
            $participante->setEdad($data[5]);
            $participante->setCelular($data[6]);
            $participante->setDeQueParticipa('General');
            $participante->setObraSocialnumeroAfiliado('OSDE-0001');
            $participante->setObservaciones('Sin observaciones');
            $participante->setDieta('Normal');
            $participante->setMedicacion('Ninguna');
            $participante->setDificultades('Ninguna');
            $participante->setFichaMedicaFileName('ficha.pdf');
            $participante->setNombreCredencial($data[0] . $data[1]);
            $manager->persist($participante);
            $participantes[] = $participante; // ✅ guardamos en el array
        }

        // ------------------------------------
        // 4. EVENTOS
        // ------------------------------------
        $eventosData = [
            ['Conferencia de IA 2026', '2026-04-10 09:00:00', '2026-04-12 18:00:00', 'Análisis de tendencias en IA.'],
            ['Taller de Desarrollo Web', '2026-05-22 14:00:00', '2026-05-22 18:00:00', 'Sesión práctica sobre React y Next.js.'],
            ['Maratón de Programación', '2026-06-15 08:00:00', '2026-06-16 08:00:00', 'Competencia de 24 horas.'],
        ];

        $eventos = [];
        foreach ($eventosData as $data) {
            $evento = new Evento();
            $evento->setNombre($data[0]);
            $evento->setFechaInicio(new DateTime($data[1]));
            $evento->setFechaFin(new DateTime($data[2]));
            $evento->setDescripcion($data[3]);
            $manager->persist($evento);
            $eventos[] = $evento; // ✅ guardamos en el array
        }

        // ------------------------------------
        // 5. INSCRIPCIONES
        // ------------------------------------
        $inscripciones = [
            ['2025-11-01 10:00:00', $participantes[0], $eventos[0]],
            ['2025-11-01 10:05:00', $participantes[1], $eventos[0]],
            ['2025-11-02 11:30:00', $participantes[2], $eventos[1]],
            ['2025-11-03 09:15:00', $participantes[3], $eventos[2]],
            ['2025-11-04 14:45:00', $participantes[4], $eventos[0]],
            ['2025-11-05 16:00:00', $participantes[0], $eventos[2]],
            ['2025-11-06 08:30:00', $participantes[3], $eventos[1]],
        ];

        foreach ($inscripciones as $data) {
            $inscripcion = new Inscripcion();
            $inscripcion->setFechaInscripcion(new DateTime($data[0]));
            $inscripcion->setParticipante($data[1]);
            $inscripcion->setEvento($data[2]);
            $manager->persist($inscripcion);
        }

        // Guardar todo
        $manager->flush();
    }
}
