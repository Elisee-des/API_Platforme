<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as Resquest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiUsersController extends AbstractController
{
    /**
     * @Route("/api/create_users", name="api_users_create", methods={"POST"})
     */
    public function create_users(Resquest $request, ManagerRegistry $managerRegistry): Response
    {
        $data = $request->getContent();
        $datajson = json_decode($data);

        if (
            property_exists($datajson, "username") && 
            property_exists($datajson, "password") && 
            property_exists($datajson, "email")
        ) {
            $user = new Users();
            $user->setUsername($datajson->username)
                ->setPassword($datajson->password)
                ->setEmail($datajson->email);
    
            $em = $managerRegistry->getManager();
            $em->persist($user);
            $em->flush();
    
    
            return $this->json(["Success" => true, "data" => $data], 200);

        }
        return $this->json(["Success" => false, "data" => $data], 401);
    }

    /**
     * @Route("/api/update_users", name="api_update_users", methods={"PUT"})
     */
    public function update_users($id, Request $request, ManagerRegistry $managerRegistry, UsersRepository $usersRepo): Response
    {
        $user = $usersRepo->find($id);



        $user = $request->getContent();
        $datajson = json_decode($user);


        $user = new Users();
        $user->setUsername($datajson->username)
            ->setPassword($datajson->password)
            ->setEmail($datajson->email);

        $em = $managerRegistry->getManager();
        $em->persist($user);
        $em->flush();


        return $this->json(["Success" => true, "data" => $user]);
    }
}
