<?php

namespace App\Controller;

use App\Entity\Matricula;
use App\Entity\User;
use App\Form\RegisterForm;
use Cassandra\Type\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserController extends AbstractController
{

    private $em;
    private $usuario_existente = false;


    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }


    #[Route('/alumnos_main', name: 'alumnos_main')]
    public function alumnos_main(): Response{
        $id = $this -> getUser() -> getId();
        return $this->render('user/alumno_main.html.twig', [
            'matriculas' => $this -> em -> getRepository(Matricula::class)->findBy([
                'user' => $id,
                'activa' => true
            ]),
            'fecha_actual' => new \DateTime()
        ]);
    }

    #[Route('/afertlogin', name: 'afertlogin')]
    public function afertlogin(Request $request): Response{

        if($this -> getUser()){
            $user = $this -> getUser();
            if(in_array('ROLE_ADMIN', $user -> getRoles(), true) AND $user -> isActivo()){
                return new RedirectResponse($this -> generateUrl('admin'));
            }

            if($user -> isActivo()){
                return new RedirectResponse($this -> generateUrl('alumnos_main'));
            }

            return new RedirectResponse($this -> generateUrl('login'));
        }else{
            return new RedirectResponse($this -> generateUrl('login'));
        }

    }

    #[Route('/admin/abrirModalRegistro', name: 'abrirModalRegistro')]
    public function abrirModalRegistro(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterForm::class, $user);

        return $this->render('modals/registro_modal.html.twig', [
            'form' => $form->createView(),
            'usuario_existente' => false
        ]);
    }


    #[Route('/admin/añadirUser', name: 'añadirUser')]
    public function añadirUser(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $datos = json_decode($request->getContent(), true);
        if (!$datos) {
            return new JsonResponse(['estado' => 'error']);
        }

        $existe = $this->em->getRepository(User::class)->findOneBy(['email' => $datos['email']]);
        if ($existe) {
            return new JsonResponse(['existe' => true]);
        }

        $user = new User();
        $user->setNombre($datos['nombre']);
        $user->setApellidos($datos['apellidos']);
        $user->setEmail($datos['email']);
        $user->setRoles(['ROLE_ALUMNO']);
        $user->setActivo(true);

        $hashedPassword = $passwordHasher->hashPassword($user, $datos['password']);
        $user->setPassword($hashedPassword);

        $this->em->persist($user);
        $this->em->flush();

        return new JsonResponse(['redirect' => $this->generateUrl('users')]);
    }


    #[Route('/admin', name: 'admin')]
    public function admin(): Response{
        return $this->render('user/admin.html.twig', [
            'hoal' => 'admin',
        ]);
    }



    #[Route('/admin/users', name: 'users')]
    public function users(): Response{

        $actualUser = $this -> getUser();
        $id = $actualUser->getId();

        return $this->render('user/usuarios.html.twig', [
            'id' => $id,
            'users' => $this -> em -> getRepository(User::class) -> findAllActives(),
        ]);
    }


    #[Route('/admin/abrirModal', name: 'abrirModal')]
    public function editarUser(Request $request): Response
    {
        $id = $request->query->get('id');
        $user_obtenido = $this -> em -> getRepository(User::class) -> find($id);

        return $this->render('modals/editar_user.html.twig', [
            'usuario' => $user_obtenido
        ]);
    }


    #[Route('/admin/updateUsuario', name: 'updateUsuario')]
    public function updateUsuario(Request $request): Response
    {
        $datos = json_decode($request->getContent(), true);
        $actualizar_user  = $this -> em -> getRepository(User::class) -> find($datos['id']);

        $actualizar_user->setEmail($datos['email']);
        $actualizar_user->setNombre($datos['nombre']);
        $actualizar_user->setApellidos($datos['apellidos']);
        $actualizar_user -> setRoles($datos['roles']);
        $actualizar_user -> setActivo($datos['activo']);


        try {
            $this->em->flush();
           //return new JsonResponse(true);
            return new JsonResponse(['redirect' => $this->generateUrl('users')]);
        }
        catch (\Exception $exception){
            return new Response($exception->getMessage());
        }

    }

    #[Route('/admin/deleteUser', name: 'deleteUser')]
    public function deleteUser(Request $request): Response
    {
        $id = json_decode($request->getContent(), true);
        $user_obtenido = $this -> em -> getRepository(User::class) -> find($id['id']);
        if ($user_obtenido) {
            $user_obtenido->setActivo(false);

            $this -> em -> getRepository(Matricula::class)-> desactivarMatriculasPorUsuarioId($user_obtenido->getId());
            $this->em->flush();
        }

        return new JsonResponse(['redirect' => $this->generateUrl('users')]);


    }

}
