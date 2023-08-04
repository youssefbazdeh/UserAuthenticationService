<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use App\EnumType\Groupe;
use App\EnumType\Role;
use App\EnumType\Status;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/user')] 
class UserController extends AbstractController
{
    private $manager;
    private $user;
    private $passwordHasher;


    public function __construct(EntityManagerInterface $manager, UserRepository $user, UserPasswordHasherInterface $passwordHasher)
    {
        $this->manager = $manager;
        $this->user    = $user;
        $this->passwordHasher = $passwordHasher;
        
    }


    //Create User
    #[Route('/', name: 'user_create', methods: 'POST')]
    public function addUser(Request $request): Response
    {
        $jsonContent = $request->getContent();

        if (empty($jsonContent)) {
            return new JsonResponse([
                'status' => false,
                'message' => 'Empty request body',
            ]);
        }
    
        $data = json_decode($jsonContent, true);
    
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            // There was an error in JSON decoding
            return new JsonResponse([
                'status' => false,
                'message' => 'Invalid JSON format in request body',
            ]);
        }
    

        $email=$data['email'];

        $password=$data['password'];

        $username=$data['username'];

        $firstname=$data['firstname'];

        $lastname=$data['lastname'];

        $birthday = isset($data['birthday']) ? new DateTime($data['birthday']) : null;

        $phone=$data['phone'];

        //email verification if exists

        $email_exist=$this->user->findOneByEmail($email);

        if($email_exist)
        {
            return new JsonResponse
            (
                [
                    'status'=>false,
                    'message'=>'Cet email existe déjà, veuillez le changer'
                ]
            );
        }

        else
        {
            $user= new User();

            $hashedPassword = $this->passwordHasher->hashPassword($user, $password);

            $user->setEmail($email)->setPassword($hashedPassword)->setUsername($username)->setFirstname($firstname)->setLastname($lastname)->setBirthday($birthday)->setPhone($phone)
            ->setStatus(Status::Enabled)->setRole(Role::SR)->setGroupe(Groupe::ED)->setFlag("Active");

            $this->manager->persist($user);

            $this->manager->flush();

            return new JsonResponse
            (
                [
                    'status'=>true,
                    'message'=>'l\utilisateur créé avec succès'
                ]
            );
        }
    }

    //User list
    #[Route('/', name: 'get_user', methods: 'GET')]
    public function getAllUsers(): Response
    {
        $users=$this->user->findAll();
        return new JsonResponse
        (
            [
                    'status'=>true,
                    'users'=>$users
            ]
        );
    }


    //Delete user by username
    
    #[Route('/{id}', name: 'user_delete', methods: 'DELETE')]
    public function deleteUserByUsername(Request $request): JsonResponse
    {

        $userRepository = $this->manager->getRepository(User::class);
        $jsonContent = $request->getContent();
    
        if (empty($jsonContent))
        {
            return new JsonResponse([
                'Status' => false,
                'message' => 'Empty request body',
            ]);
        }
    
        $data = json_decode($jsonContent, true);
    
        if ($data == null && json_last_error() !== JSON_ERROR_NONE)
        {
            return new JsonResponse([
                'status' =>false,
                'message' =>'malformed Json data'
            ]);
        }
    
        $username = $data['username'];
        $user = $userRepository->findOneByUsername($username);
    
        if (!$user) 
        {
            return new JsonResponse([
                'status' => false,
                'message' => 'User not found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }
    
        // Update the user's flag to "desactivated"
        $user->setFlag('desactivated');
        $this->manager->flush();
    
        return new JsonResponse([
            'status'=>true,
            'message'=>'User desactivated successfully!'
        ]);
    }



    //Update User by username

    #[Route('/{id}', name: 'user_update', methods: 'PUT')]
    public function updateUserByUsername(Request $request): JsonResponse
    {
        $userRepository = $this->manager->getRepository(User::class);
        $jsonContent = $request->getContent();

        if (empty($jsonContent)) {
            return new JsonResponse([
                'status' => false,
                'message' => 'Empty request body',
            ]);
        }

        $data = json_decode($jsonContent, true);

        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            // There was an error in JSON decoding
            return new JsonResponse([
                'status' => false,
                'message' => 'Invalid JSON format in request body',
            ]);
        }

        $username = $data['username'];


        // Find the user by the given username
        $user = $userRepository->findOneByUsername($username);

        if (!$user) {
            return new JsonResponse([
                'status' => false,
                'message' => 'User not found.',
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        // Update user data based on the request body
        $user->setEmail($data['email']);
        $user->setFirstname($data['firstname']);
        $user->setLastname($data['lastname']);
        // Update other properties as needed

        $this->manager->flush();

        return new JsonResponse([
            'status' => true,
            'message' => 'User updated successfully.',
        ]);
    }


}
