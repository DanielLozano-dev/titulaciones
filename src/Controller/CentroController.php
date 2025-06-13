<?php

namespace App\Controller;

use App\Entity\Centro;
use App\Entity\Matricula;
use App\Entity\Titulacion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CentroController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    #[Route('/admin/titulaciones_por_centro', name: 'titulaciones_por_centro')]
    public function titulaciones_por_centro(Request $request): Response
    {
        $centros_id = $request->query->get('id');
        $centro = $this->em->getRepository(Centro::class)->find($centros_id);
        $titulaciones = $centro -> getTitulacions();
        $datos = [];

        foreach ($titulaciones as $titulacion) {
            $datos[] = [
                'id' => $titulacion->getId(),
                'nombre' => $titulacion->getNombre()
            ];
        }

        return new JsonResponse($datos);
    }



    #[Route('/admin/abrirModalCentro', name: 'abrirModalCentro')]
    public function abrirModalCentro(Request $request): Response
    {
        if( $request -> query -> get('id')){
            $id = $request->query->get('id');
            $centro_obtenido = $this -> em -> getRepository(Centro::class) -> find($id);
            $centros_titulacion = $centro_obtenido -> getTitulacions();

            $variables = [
                'centro' => $centro_obtenido,
                'titulaciones' => $centros_titulacion
            ];
        }else{
            $variables = [
                'titulaciones' => $this -> em -> getRepository(Titulacion::class) -> findAllActives()
            ];
        }


        return $this->render('modals/centro_modal.html.twig', $variables);
    }


    #[Route('/admin/updateGuardarCentro', name: 'updateGuardarCentro')]
    public function updateGuardarCentro(Request $request): Response
    {
        $datos = json_decode($request->getContent(), true);

        if ($datos['id'] != null) {
            $centro = $this->em->getRepository(Centro::class)->find($datos['id']);

            $centro->setNombre($datos['nombre']);
            $centro->setDireccion($datos['direccion']);
            $centro->setActivo($datos['activo']);

            // Limpiamos titulaciones previas
            $centro->getTitulacions()->clear();

            foreach ($datos['titulaciones'] as $idTitulacion) {
                $titulacion = $this->em->getRepository(Titulacion::class)->find($idTitulacion);
                if ($titulacion) {
                    $centro->addTitulacion($titulacion);
                }
            }

            try {
                $this->em->flush();
                return new JsonResponse(['redirect' => $this->generateUrl('centro')]);
            } catch (\Exception $exception) {
                return new Response($exception->getMessage());
            }

        } else {
            $centro = new Centro();

            $centro->setNombre($datos['nombre']);
            $centro->setDireccion($datos['direccion']);
            $centro->setActivo($datos['activo']);

            foreach ($datos['titulaciones'] as $idTitulacion) {
                $titulacion = $this->em->getRepository(Titulacion::class)->find($idTitulacion);
                if ($titulacion) {
                    $centro->addTitulacion($titulacion);
                }
            }

            try {
                $this->em->persist($centro);
                $this->em->flush();
                return new JsonResponse(['redirect' => $this->generateUrl('centro')]);
            } catch (\Exception $exception) {
                return new Response($exception->getMessage());
            }
        }
    }


    #[Route('/admin/centro', name: 'centro')]
    public function index(): Response
    {

        return $this->render('centro/index.html.twig', [
            'centros' => $this -> em -> getRepository(Centro::class) -> findAll(),
        ]);
    }

    #[Route('/admin/deleteCentro', name: 'deleteCentro')]
    public function deleteCentro(Request $request): Response
    {

        $id = json_decode($request->getContent(), true);
        $centro_obtenido = $this -> em -> getRepository(Centro::class) -> find($id['id']);
        if ($centro_obtenido) {
            $centro_obtenido-> setActivo(false);

            $this -> em -> getRepository(Matricula::class)-> desactivarMatriculasPorCentro($centro_obtenido->getId());
            $this->em->flush();
        }
        return new JsonResponse(['redirect' => $this->generateUrl('centro')]);
    }
}
