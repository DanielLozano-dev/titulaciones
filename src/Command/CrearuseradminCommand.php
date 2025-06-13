<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'crearuseradmin',
    description: 'Permite crear un usuario administrador',
    aliases: ['app:mkuseradmin'],
)]
class CrearuseradminCommand extends Command
{
    private $userRepository;

    private $entityManager;
    private $passwordHasher;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email del nuevo administrador')
            ->addArgument('password', InputArgument::REQUIRED, 'Contraseña del nuevo usuario')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('email');
        $arg2 = $input->getArgument('password');


        if($arg1 AND $arg2){
            $user = $this->userRepository->findOneBy(['email' => $arg1]);
            if($user){
                $io -> error('El usuario ya existe, Quieres sorbre escribirlo ?');
                return Command::FAILURE;
            }

            $helper = $this->getHelper('question');
            $nombre_pregunta = new Question('Porfavor dime el nombre del administrador: ');
            $apellido_preguta = new Question('Porfavor dime el apellido del administrador: ');

            $nombre =  $helper->ask($input, $output, $nombre_pregunta);
            $apellido =  $helper->ask($input, $output, $apellido_preguta);



            $user = new User();
            $user->setEmail($arg1);
            $user->setPassword($this->passwordHasher->hashPassword($user, $arg2));
            $user ->setRoles(['ROLE_ADMIN']);
            $user->setNombre($nombre);
            $user -> setApellidos($apellido);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $io -> success('El usuario se ha creado correctamente');
        }else{
            $io -> error('No has introducido el email o la contraseña del usuario');
        }


        return Command::SUCCESS;
    }
}
