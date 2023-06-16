<?php
namespace App\Controller;

use App\Entity\Type;
use App\Entity\Weakness;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class WeaknessController extends AbstractController
{
    #[Route('/weakness', methods: ['GET'])]
    public function getAll(SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $weaknesses = $entityManager->getRepository(Weakness::class)->findAll();
        return new JsonResponse($serializer->serialize($weaknesses, 'json'), 200, [], true);
    }

    #[Route('/weakness', methods: ['POST'])]
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $weakness = new Type();
        $weakness->setName($data['name']);
        $entityManager->persist($weakness);
        $entityManager->flush();
        return new JsonResponse($serializer->serialize($weakness, 'json'), 200, [], true);
    }

    #[Route('/weakness/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $weakness = $entityManager->getRepository(Weakness::class)->find($id);
        if ($weakness) {
            $weakness->setName($data['name']);
            $entityManager->flush();
            return new JsonResponse($serializer->serialize($weakness, 'json'), 200, [], true);
        }
        return new JsonResponse('Weakness not found', 404);
    }

    #[Route('/weakness/{id}', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $weakness = $entityManager->getRepository(Weakness::class)->find($id);
        if ($weakness) {
            $entityManager->remove($weakness);
            $entityManager->flush();
            return new JsonResponse('Deleted successfully', 200);
        }
        return new JsonResponse('Weakness not found', 404);
    }
}
