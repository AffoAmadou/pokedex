<?php
namespace App\Controller;

use App\Entity\Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class TypeController extends AbstractController
{
    #[Route('/type', methods: ['GET'])]
    public function getAll(SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $types = $entityManager->getRepository(Type::class)->findAll();
        return new JsonResponse($serializer->serialize($types, 'json'), 200, [], true);
    }

    #[Route('/type', methods: ['POST'])]
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $type = new Type();
        $type->setName($data['name']);
        $entityManager->persist($type);
        $entityManager->flush();
        return new JsonResponse($serializer->serialize($type, 'json'), 200, [], true);
    }

    #[Route('/type/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $type = $entityManager->getRepository(Type::class)->find($id);
        if ($type) {
            $type->setName($data['name']);
            $entityManager->flush();
            return new JsonResponse($serializer->serialize($type, 'json'), 200, [], true);
        }
        return new JsonResponse('Type not found', 404);
    }

    #[Route('/type/{id}', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $type = $entityManager->getRepository(Type::class)->find($id);
        if ($type) {
            $entityManager->remove($type);
            $entityManager->flush();
            return new JsonResponse('Deleted successfully', 200);
        }
        return new JsonResponse('Type not found', 404);
    }
}
