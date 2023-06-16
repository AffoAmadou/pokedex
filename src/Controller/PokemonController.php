<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Entity\Gender;
use App\Entity\Category;
use App\Entity\Type;
use App\Entity\Weakness;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PokemonController extends AbstractController
{
    #[Route('/pokemon', methods: ['POST'])]
    public function postPokemon(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $payload = json_decode($request->getContent(), true);

        $pokemon = new Pokemon();

        $pokemon->setNumber($payload['number']);
        $pokemon->setName($payload['name']);
        $pokemon->setHeight($payload['height']);
        $pokemon->setWeight($payload['weight']);

        $gender = (new Gender())->setName($payload['gender']);
        $pokemon->setGender($gender);

        $category = (new Category())->setName($payload['category']);
        $pokemon->setCategory($category);

        foreach ($payload['types'] as $typePayload) {
            $type = (new Type())->setName($typePayload['name']);
            $pokemon->addType($type);
        }

        foreach ($payload['weaknesses'] as $weaknessPayload) {
            $weakness = (new Type())->setName($weaknessPayload['name']);
            $pokemon->addWeakness($weakness);
        }

        $pokemon->setNextEvolution($payload['nextEvolution']);
        $pokemon->setPreviousEvolution($payload['previousEvolution']);

        $entityManager->persist($pokemon);
        $entityManager->flush();

        return new JsonResponse(
            $serializer->serialize($pokemon, 'json'),
            Response::HTTP_CREATED,
            [],
            true
        );
    }

    #[Route('/pokemon/{id}', methods: ['PUT'])]
    public function putPokemon(int $id, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManagerInterface)
    {
        $payload = json_decode($request->getContent(), true);
        $repository = $entityManagerInterface->getRepository(Pokemon::class);
        $pokemon = $repository->find($id);

        if (!$pokemon) {
            return new JsonResponse(
                json_encode(['error' => 'Pokemon not found']),
                Response::HTTP_NOT_FOUND,
                [],
                true
            );
        }

        $pokemon->setNumber($payload['number']);
        $pokemon->setName($payload['name']);
        $pokemon->setHeight($payload['height']);
        $pokemon->setWeight($payload['weight']);

        $pokemon->getGender()->setName($payload['gender']);
        $pokemon->getCategory()->setName($payload['category']);

        foreach ($pokemon->getTypes() as $type) {
            $pokemon->removeType($type);
        }

        foreach ($payload['types'] as $typePayload) {
            $type = (new Type())->setName($typePayload['name']);
            $pokemon->addType($type);
        }

        foreach ($pokemon->getWeaknesses() as $weakness) {
            $pokemon->removeWeakness($weakness);
        }

        foreach ($payload['weaknesses'] as $weaknessPayload) {
            $weakness = (new Type())->setName($weaknessPayload['name']);
            $pokemon->addWeakness($weakness);
        }

        $pokemon->setNextEvolution($payload['nextEvolution']);
        $pokemon->setPreviousEvolution($payload['previousEvolution']);

        $entityManagerInterface->flush();

        return new JsonResponse(
            $serializer->serialize($pokemon, 'json'),
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/pokemon/{id}', methods: ['DELETE'])]
    public function deletePokemon(int $id, EntityManagerInterface $entityManagerInterface)
    {
        $repository = $entityManagerInterface->getRepository(Pokemon::class);
        $pokemon = $repository->find($id);

        if (!$pokemon) {
            return new JsonResponse(
                json_encode(['error' => 'Pokemon not found']),
                Response::HTTP_NOT_FOUND,
                [],
                true
            );
        }

        $entityManagerInterface->remove($pokemon);
        $entityManagerInterface->flush();

        return new JsonResponse(
            json_encode(['success' => 'Pokemon deleted']),
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/pokemon', methods: ['GET'])]
    public function getPokemon(SerializerInterface $serializer, EntityManagerInterface $entityManagerInterface)
    {
        $repository = $entityManagerInterface->getRepository(Pokemon::class);
        $pokemon = $repository->findAll();

        return new JsonResponse(
            $serializer->serialize($pokemon, 'json'),
            Response::HTTP_OK,
            [],
            true
        );
    }
}
