<?php

namespace App\Controller;

use App\Entity\Church;
use App\Entity\Member;
use App\Entity\Address;
use App\Repository\MemberRepository;
use App\Service\DocumentValidatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;

class MemberController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MemberRepository $memberRepository,
        private DocumentValidatorService $documentValidator
    ) {}

    /**
     * Lista todos os membros de uma igreja específica baseada no internal code da igreja
     */
    public function index(Request $request, #[MapEntity(mapping: ['internalCode' => 'internalCode'])] Church $church): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);

        $paginator = $this->memberRepository->findPaginatedByChurch($church, $page, $limit);
        $totalItems = count($paginator);
        $totalPages = ceil($totalItems / $limit);

        $items = [];
        foreach ($paginator as $member) {
            $items[] = [
                'id' => $member->getId(),
                'name' => $member->getName(),
                'email' => $member->getEmail(),
                'document' => $member->getDocumentNumber(),
                'phone' => $member->getPhone(),
            ];
        }
        
        return $this->json([
            'items' => $items,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'church' => [
                'internalCode' => $church->getInternalCode(),
                'name' => $church->getName()
            ]
        ]);
    }

    /**
     * Cria um novo membro em uma igreja específica baseada no internal code da igreja
     */
    public function store(Request $request, #[MapEntity(mapping: ['internalCode' => 'internalCode'])] Church $church): JsonResponse
    {
        if (count($church->getMembers()) >= $church->getMembersLimit()) {
            return $this->json(
                ['error' => 'A igreja atingiu seu limite de membros!'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $data = $this->getRequestData($request);

        if ($validationError = $this->validateMemberData($data, $church)) {
            return $validationError;
        }

        $member = $this->createMemberFromData($data, $church);

        $this->entityManager->persist($member);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Membro criado com sucesso!',
            'memberId' => $member->getId()
        ], Response::HTTP_CREATED);
    }

    /**
     * Exibe os detalhes de um membro específico
     */
    public function show(Member $member): JsonResponse
    {
        return $this->json([
            'id' => $member->getId(),
            'name' => $member->getName(),
            'documentType' => $member->getDocumentType(),
            'documentNumber' => $member->getDocumentNumber(),
            'birthDate' => $member->getBirthDate()->format('d-m-Y'),
            'email' => $member->getEmail(),
            'phone' => $member->getPhone(),
            'church' => [
                'internalCode' => $member->getChurch()->getInternalCode(),
                'name' => $member->getChurch()->getName()
            ],
        ]);
    }

    /**
     * Atualiza os dados de um membro existente
     */
    public function update(Request $request, Member $member): JsonResponse
    {
        $data = $this->getRequestData($request);

        if ($validationError = $this->validateMemberData($data, $member->getChurch(), $member)) {
            return $validationError;
        }

        $this->updateMemberFromData($member, $data);

        $this->entityManager->flush();

        return $this->json(['message' => 'Membro editado com sucesso!']);
    }

    /**
     * Remove um membro do sistema
     */
    public function destroy(Member $member): JsonResponse
    {
        $this->entityManager->remove($member);
        $this->entityManager->flush();

        return $this->json(['message' => 'Membro excluido com sucesso!']);
    }

    /**
     * Decodifica e retorna os dados da requisição
     */
    private function getRequestData(Request $request): array
    {
        return json_decode($request->getContent(), true) ?? [];
    }

    /**
     * Valida os dados do membro
     */
    private function validateMemberData(array $data, Church $church, ?Member $currentMember = null): ?JsonResponse
    {
        if (!$this->documentValidator->validate($data['documentType'], $data['documentNumber'])) {
            return $this->json(
                ['error' => 'Formato de documento inválido!'],
                Response::HTTP_BAD_REQUEST
            );
        }

        // Valida duplicidade de email na igreja
        $existingMember = $this->memberRepository->findOneBy([
            'email' => $data['email'],
            'church' => $church
        ]);

        if ($existingMember && (!$currentMember || $existingMember->getId() !== $currentMember->getId())) {
            $message = $currentMember 
                ? 'Esse email já está sendo usado por outro membro nessa Igreja!'
                : 'Um membro com esse email já existe nessa igreja!';

            return $this->json(['error' => $message], Response::HTTP_CONFLICT);
        }

        // Valida duplicidade de número do documento (global no sistema)
        $existingDocument = $this->memberRepository->findOneBy([
            'documentNumber' => $data['documentNumber']
        ]);

        if ($existingDocument && (!$currentMember || $existingDocument->getId() !== $currentMember->getId())) {
            $documentLabel = $data['documentType'] === 'CPF' ? 'CPF' : 'CNPJ';
            $message = $currentMember 
                ? "Esse {$documentLabel} já foi registrado por outro membro!"
                : "Um membro com esse {$documentLabel} já existe!";

            return $this->json(['error' => $message], Response::HTTP_CONFLICT);
        }

        // Valida data de nascimento
        $birthDate = new \DateTimeImmutable($data['birthDate']);
        if ($birthDate > new \DateTimeImmutable()) {
            return $this->json(
                ['error' => 'Data de nascimento inválida, coloque uma data no passado!'],
                Response::HTTP_BAD_REQUEST
            );
        }

        return null;
    }

    /**
     * Cria uma nova instância de membro a partir dos dados
     */
    private function createMemberFromData(array $data, Church $church): Member
    {
        $member = new Member();
        $this->updateMemberFromData($member, $data);
        $member->setChurch($church);

        return $member;
    }

    /**
     * Atualiza os dados de um membro existente
     */
    private function updateMemberFromData(Member $member, array $data): void
    {
        $member->setName($data['name']);
        $member->setDocumentType($data['documentType']);
        $member->setDocumentNumber($data['documentNumber']);
        $member->setBirthDate(new \DateTimeImmutable($data['birthDate']));
        $member->setEmail($data['email']);
        $member->setPhone($data['phone']);

        $address = $member->getAddress() ?? new Address();
        $address->setStreet($data['address']['street']);
        $address->setNumber($data['address']['number']);
        $address->setComplement($data['address']['complement'] ?? null);
        $address->setCity($data['address']['city']);
        $address->setState($data['address']['state']);
        $address->setZipCode($data['address']['zipCode']);

        $member->setAddress($address);
    }
}