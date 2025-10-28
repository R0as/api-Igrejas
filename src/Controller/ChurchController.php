<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Church;
use App\Repository\ChurchRepository;
use App\Service\DocumentValidatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ChurchController extends AbstractController
{
    /**
     * Endpoint para listar todas as igrejas.
     */
    public function index(ChurchRepository $churchRepository): JsonResponse
    {

        $churches = $churchRepository->findAll();

        $data = [];
        foreach ($churches as $church) {
            $data[] = [
                'id' => $church->getId(),
                'name' => $church->getName(),
                'internalCode' => $church->getInternalCode(),
                'membersLimit' => $church->getMembersLimit(),
            ];
        }

        return $this->json($data);
    }

    /**
     * Endpoint para criar uma nova igreja.
     */

    public function store(Request $request, 
        EntityManagerInterface $entityManager,
        DocumentValidatorService $documentValidator
        ): JsonResponse 
        {
        $data = json_decode($request->getContent(), true);

        if (!$documentValidator->validate($data['ownerDocumentType'], $data['ownerDocumentNumber'])) {
            return $this->json(['error' => 'Formato de documento inválido!'], Response::HTTP_BAD_REQUEST);
        }

        $church = new Church();
        $address = new Address();
        
        $church->setName($data['name']);
        $church->setOwnerDocumentType($data['ownerDocumentType']);
        $church->setOwnerDocumentNumber($data['ownerDocumentNumber']);
        $church->setInternalCode($data['internalCode']);
        $church->setPhone($data['phone']);
        $church->setWebsite($data['website']);
        $church->setMembersLimit($data['membersLimit']);

        $address->setStreet($data['address']['street']);
        $address->setNumber($data['address']['number']);
        $address->setComplement($data['address']['complement']);
        $address->setCity($data['address']['city']);
        $address->setState($data['address']['state']);
        $address->setZipCode($data['address']['zipCode']);
        $church->setAddress($address);
        
        $entityManager->persist($church);

        $entityManager->flush();

        return $this->json([
            'message' => 'Igreja criada com sucesso!',
            'churchId' => $church->getId()
        ], Response::HTTP_CREATED);
    }
}