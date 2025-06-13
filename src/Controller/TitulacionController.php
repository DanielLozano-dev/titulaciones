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

final class TitulacionController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    #[Route('/admin/abrirModalTitulacion', name: 'abrirModalTitulacion')]
    public function abrabrirModalTitulacionirModal(Request $request): Response
    {
        if( $request -> query -> get('id')){
            $id = $request->query->get('id');
            $titulacion_obtenida = $this -> em -> getRepository(Titulacion::class) -> find($id);
            $centros_titulacion = $titulacion_obtenida -> getCentros();

            $variables = [
                'titulacion' => $titulacion_obtenida,
                'centros' => $centros_titulacion
            ];
        }else{
            $variables = [
                'centros' => $this -> em -> getRepository(Centro::class) -> findAllActives()
            ];
        }


        return $this->render('modals/titulacion_modal.html.twig', $variables);
    }


    #[Route('/admin/updateGuardarTitulacion', name: 'updateGuardarTitulacion')]
    public function updateGuardarTitulacion(Request $request): Response{
        $datos = json_decode($request->getContent(), true);
        if($datos['id'] != null){
            $actualizar_titulacion = $this -> em -> getRepository(Titulacion::class)->find($datos['id']);
            $actualizar_titulacion -> setNombre($datos['nombre']);
            $actualizar_titulacion->getCentros()->clear();
            foreach ($datos['centros'] as $idCentro) {
                $centro = $this->em->getRepository(Centro::class)->find($idCentro);
                if ($centro) {
                    $$actualizar_titulacion->addCentro($centro);
                }
            }
            $actualizar_titulacion -> setActiva($datos['activa']);
            $actualizar_titulacion -> setHoras($datos['horas']);
            $actualizar_titulacion -> setPrecio($datos['precio']);

            try {
                $this->em->flush();
                //return new JsonResponse(true);
                return new JsonResponse(['redirect' => $this->generateUrl('titulacion')]);
            }
            catch (\Exception $exception){
                return new Response($exception->getMessage());
            }
        }else{
            $nueva_titulacion = new Titulacion();
            $nueva_titulacion -> setNombre($datos['nombre']);
            $nueva_titulacion -> setActiva($datos['activa']);
            $nueva_titulacion -> setHoras($datos['horas']);
            $nueva_titulacion -> setPrecio($datos['precio']);
            foreach($datos['centros'] as $centro){
                $nueva_titulacion -> addCentro($this -> em -> getRepository(Centro::class)->find($centro));
            }

            try{
                $this->em->persist($nueva_titulacion);
                $this->em->flush();
                return new JsonResponse(['redirect' => $this->generateUrl('titulacion')]);
            }
            catch (\Exception $exception){
                return new Response($exception->getMessage());
            }
        }

    }

    #[Route('/admin/titulacion', name: 'titulacion')]
    public function index(): Response
    {

        return $this->render('titulacion/index.html.twig', [
            'titulaciones' => $this -> em -> getRepository(Titulacion::class) -> findAll(),
        ]);
    }


    #[Route('/admin/deleteTitulacion', name: 'deleteTitulacion')]
    public function deleteTitulacion(Request $request): Response
    {

        $id = json_decode($request->getContent(), true);
        $titulacion_obtenida = $this -> em -> getRepository(Titulacion::class) -> find($id['id']);
        if ($titulacion_obtenida) {
            $titulacion_obtenida->setActiva(false);

            $this -> em -> getRepository(Matricula::class)-> desactivarMatriculasPorTitulacion($titulacion_obtenida->getId());
            $this->em->flush();
        }
        return new JsonResponse(['redirect' => $this->generateUrl('titulacion')]);
    }
}
