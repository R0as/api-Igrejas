<?php

namespace App\Controller;

use App\Entity\Member;
use App\Repository\ChurchRepository;
use App\Repository\MemberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


class TransferController extends AbstractController
{
    public function transfer(
        Request $request,
        Member $member,
        EntityManagerInterface $entityManager,
        ChurchRepository $churchRepository,
        MemberRepository $memberRepository
    ): JsonResponse {

        $data = json_decode($request->getContent(), true);
        $newChurchInternalCode = $data['newChurchInternalCode'] ?? null;

        if (!$newChurchInternalCode) {
            return $this->json(['error' => 'O código da nova igreja é necessário.'], Response::HTTP_BAD_REQUEST);
        }

        $newChurch = $churchRepository->findOneBy(['internalCode' => $newChurchInternalCode]);
        
        if (!$newChurch) {
            return $this->json(['error' => 'Igreja de destino não encontrada.'], Response::HTTP_NOT_FOUND);
        }

        if ($member->getChurch()->getId() === $newChurch->getId()) {
            return $this->json(['error' => 'O membro já está nessa igreja.'], Response::HTTP_BAD_REQUEST);
        }

        $existingMember = $memberRepository->findOneBy(['email' => $member->getEmail(), 'church' => $newChurch]);
        if ($existingMember) {
            return $this->json(['error' => 'Um membro com esse email já existe na igreja de destino.'], Response::HTTP_CONFLICT);
        }
        
        // 5. REGRA: Verificar o limite de membros na igreja de destino
        if (count($newChurch->getMembers()) >= $newChurch->getMembersLimit()) {
            return $this->json(['error' => 'A igreja de destino já atingiu seu limite de membros.'], Response::HTTP_BAD_REQUEST);
        }

        if ($member->getLastTransferDate()) {
            $today = new \DateTimeImmutable();
            $interval = $today->diff($member->getLastTransferDate());
            if ($interval->days < 10) {
                $daysRemaining = 10 - $interval->days;
                return $this->json(['error' => "Membro não pode ser transferido por {$daysRemaining} dia(s)."], Response::HTTP_BAD_REQUEST);
            }
        }

        $member->setChurch($newChurch);
        $member->setLastTransferDate(new \DateTimeImmutable());

        $entityManager->flush();

        return $this->json(['message' => 'Membro transferido com sucesso.']);
    }
}