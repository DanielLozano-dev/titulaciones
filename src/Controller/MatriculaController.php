<?php

namespace App\Controller;

use App\Entity\Centro;
use App\Entity\Matricula;
use App\Entity\Titulacion;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MatriculaController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    #[Route('/admin/AsignarMatricula', name: 'AsignarMatricula')]
    public function AsignarMatricula(Request $request): Response
    {
        $datos = json_decode($request->getContent(), true);


        $centro = $this->em->getRepository(Centro::class)->find($datos['centro']);
        $user = $this->em->getRepository(User::class)->find($datos['id_user']);
        $titulacion = $this->em->getRepository(Titulacion::class)->find($datos['titulacion']);
        $fecha_fin = \DateTime::createFromFormat('Y-m-d', $datos['fecha_fin']);
        $fecha_inicio = \DateTime::createFromFormat('Y-m-d', $datos['fecha_inicio']);

        $matricula = new Matricula();
        $matricula->setCentro($centro);
        $matricula->setUser($user);
        $matricula->setTitulacion($titulacion);
        $matricula->setFechaFin($fecha_fin);
        $matricula -> setFechaIncio($fecha_inicio);


        try {
            $this->em->persist($matricula);
            $this->em->flush();
            return new JsonResponse([
                'redirect' => $this->generateUrl('MostrarMatriculas', ['id' => $datos['id_user']]),
            ]);
        } catch (\Exception $exception) {
            return new Response($exception->getMessage());
        }


    }



    #[Route('/admin/abrirModalAsignarMatricula', name: 'abrirModalAsignarMatricula')]
    public function abrirModalAsignarMatricula(Request $request): Response
    {
        $id_user = $request->query->get('id');
        $user = $this->em->getRepository(User::class)->find($id_user);
        $centros = $this -> em -> getRepository(Centro::class) -> findBy(['activo' => true]);


        return $this->render('modals/añadir_matricula_modal.html.twig', [
            'user' => $user,
            'centros' => $centros
        ]);
    }



    #[Route('/admin/MostrarMatriculas', name: 'MostrarMatriculas')]
    public function MostrarMatriculas(Request $request): Response
    {
        $id_user = $request->query->get('id');

        $matrculas_encontradas = $this -> em -> getRepository(Matricula::class) -> findAllByAlumnoId($id_user);
        $nombre_user = $this -> em -> getRepository(User::class) -> find($id_user);

        return $this->render('modals/mostrar_matriculas_modal.html.twig', [
            'matriculas' => $matrculas_encontradas,
            'user' => $nombre_user ->getNombre(),
            'user_id' => $nombre_user ->getId()
        ]);
    }


    #[Route('/admin/Detalle-matricula', name: 'Detalle-matricula')]
    public function Detalle_matricula(Request $request): Response
    {

        $matrculas_encontrada = $this->em->getRepository(Matricula::class)->findOneBy([
            'id' => $request->query->get('id')
        ]);
        $centro_matricula = $matrculas_encontrada -> getCentro();
        $titulacion = $matrculas_encontrada -> getTitulacion();


        return $this->render('matricula/detalle_matricula.html.twig', [
            'matricula' => $matrculas_encontrada,
            'centro' => $centro_matricula ->getNombre(),
            'titulacion' => $titulacion -> getNombre(),
        ]);
    }

    #[Route('/admin/matricula', name: 'app_matricula')]
    public function index(): Response
    {
        return $this->render('matricula/index.html.twig', [
            'controller_name' => 'MatriculaController',
        ]);
    }
}
