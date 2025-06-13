<?php

namespace App\Controller;

use App\Entity\Matricula;
use App\Entity\Tareas;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TareaController extends AbstractController
{
    private $em;


    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    #[Route('/tarea', name: 'tarea')]
    public function index(Request $request): Response
    {
        $id = $request->query->get('id');
        $matricula = $this -> em -> getRepository(Matricula::class)-> find($id);
        $tareas = $this -> em -> getRepository(Tareas::class) -> findBy(['matricula' => $matricula]);

        return $this->render('tarea/index.html.twig', [
            'matricula' => $matricula,
            'tareas' => $tareas,
        ]);
    }

    #[Route('/modalEditar', name: 'modal_editar_tarea')]
    public function modalEditarTarea(Request $request): Response
    {
        $id = $request->query->get('id');
        $tarea = $this->em->getRepository(Tareas::class)->find($id);

        return $this->render('modals/editar_tarea_modal.html.twig', [
            'tarea' => $tarea,
            'matricula' => $tarea->getMatricula()
        ]);
    }



    #[Route('/updateGuardarTarea', name: 'updateGuardarTarea')]
    public function updateGuardarTarea(Request $request): Response
    {
        $datos = json_decode($request->getContent(), true);
        $id_matricula = $datos['id_matricula'];
        $matricula = $this -> em -> getRepository(Matricula::class)-> find($id_matricula);
        if($datos['tarea_id'] != null){
            $tarea = $this->em->getRepository(Tareas::class)->find($datos['tarea_id']);

            if (!$tarea) {
                return new JsonResponse(['error' => 'Tarea no encontrada']);

            }

            $tarea->setDescripcion($datos['descripcion']);
            $tarea->setCompletada($datos['completada']);

            try {
                $this->em->flush();
                return new RedirectResponse($this->generateUrl('tarea', [
                    'id' => $matricula->getId()
                ]));
            } catch (\Exception $e) {
                return new Response($e->getMessage(), 500);
            }
        }

        $descripcion_tarea = $datos['descripcion'];

        $tarea = new Tareas($descripcion_tarea);
        $tarea->setMatricula($matricula);
        $this->em->persist($tarea);
        $this->em->flush();


        return new RedirectResponse($this->generateUrl('tarea', [
            'id' => $matricula->getId()
        ]));


    }


    #[Route('/eliminarTarea', name: 'eliminarTarea')]
    public function eliminarTarea(Request $request): Response
    {
        $id = $request->query->get('id_tarea');
        $tarea = $this->em->getRepository(Tareas::class)->find($id);

        if (!$tarea) {
            throw $this->createNotFoundException('Tarea no encontrada');
        }

        $matricula = $tarea->getMatricula();

        $this->em->remove($tarea);
        $this->em->flush();

        return new RedirectResponse($this->generateUrl('tarea', [
            'id' => $matricula->getId()
        ]));
    }

    #[Route('/completarTarea', name: 'completarTarea')]
    public function completarTarea(Request $request): Response
    {
        $id = $request->query->get('id_tarea');
        $tarea = $this->em->getRepository(Tareas::class)->find($id);

        $tarea -> setCompletada(true);

        if (!$tarea) {
            throw $this->createNotFoundException('Tarea no encontrada');
        }

        $matricula = $tarea->getMatricula();

        $this->em->flush();

        return new RedirectResponse($this->generateUrl('tarea', [
            'id' => $matricula->getId()
        ]));
    }




}
