<?php
namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CategoryController extends AbstractController
{
    #[Route('/category', methods: ['GET'])]
    public function getAll(SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $categories = $entityManager->getRepository(Category::class)->findAll();
        return new JsonResponse($serializer->serialize($categories, 'json'), 200, [], true);
    }

    #[Route('/category', methods: ['POST'])]
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $category = new Category();
        $category->setName($data['name']);
        $entityManager->persist($category);
        $entityManager->flush();
        return new JsonResponse($serializer->serialize($category, 'json'), 200, [], true);
    }

    #[Route('/category/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $category = $entityManager->getRepository(Category::class)->find($id);
        if ($category) {
            $category->setName($data['name']);
            $entityManager->flush();
            return new JsonResponse($serializer->serialize($category, 'json'), 200, [], true);
        }
        return new JsonResponse('Category not found', 404);
    }

    #[Route('/category/{id}', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $category = $entityManager->getRepository(Category::class)->find($id);
        if ($category) {
            $entityManager->remove($category);
            $entityManager->flush();
            return new JsonResponse('Deleted successfully', 200);
        }
        return new JsonResponse('Category not found', 404);
    }
}
