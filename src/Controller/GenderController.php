<?php
namespace App\Controller;

use App\Entity\Gender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class GenderController extends AbstractController
{
    #[Route('/gender', methods: ['GET'])]
    public function getAll(SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $genders = $entityManager->getRepository(Gender::class)->findAll();
        return new JsonResponse($serializer->serialize($genders, 'json'), 200, [], true);
    }

    #[Route('/gender', methods: ['POST'])]
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $gender = new Gender();
        $gender->setName($data['name']);
        $entityManager->persist($gender);
        $entityManager->flush();
        return new JsonResponse($serializer->serialize($gender, 'json'), 200, [], true);
    }

    #[Route('/gender/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $gender = $entityManager->getRepository(Gender::class)->find($id);
        if ($gender) {
            $gender->setName($data['name']);
            $entityManager->flush();
            return new JsonResponse($serializer->serialize($gender, 'json'), 200, [], true);
        }
        return new JsonResponse('Gender not found', 404);
    }

    #[Route('/gender/{id}', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $gender = $entityManager->getRepository(Gender::class)->find($id);
        if ($gender) {
            $entityManager->remove($gender);
            $entityManager->flush();
            return new JsonResponse('Deleted successfully', 200);
        }
        return new JsonResponse('Gender not found', 404);
    }
}
