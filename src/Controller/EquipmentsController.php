<?php

namespace App\Controller;

use App\DTO\EquipmentDTO;
use App\Entity\Equipment;
use App\Helper\DataTablesJsonFormatter;
use App\Service\EquipmentService;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EquipmentsController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ){}

    #[Route('/equipments', 'app_equipments_render', methods: ['GET'])]
    public function equipmentsTableRender(): Response
    {
        return $this->render(view: 'equipmentsList.html.twig', response: new Response(Response::HTTP_OK));
    }

    #[Route('/equipments/load', 'app_equipments_list_render', methods: ['GET'])]
    public function equipmentsTableListRender(Request $request): JsonResponse
    {
        $equipments = $this->entityManager->getRepository(Equipment::class)->findAll();
        $formated = DataTablesJsonFormatter::format(
            $this->formatEquipmentData($equipments),
            $request->query->get('draw')
        );

        return new JsonResponse($formated, status: Response::HTTP_OK);
    }

    #[Route('/equipment', 'app_register_equipment_form', methods: ['GET'])]
    public function registerEquipmentRender(): Response
    {
        return $this->render('registerEquipmentForm.html.twig', response: new Response(Response::HTTP_OK));
    }

    #[Route('/equipment', 'app_register_equipment_request', methods: ['POST'])]
    public function registerEquipmentRequest(Request $request, EquipmentService $equipmentService): Response
    {
        $equipmentDTO = EquipmentDTO::fromRequest($request);

        try {
            $validatedEquipment = $equipmentService->register($equipmentDTO, $this->entityManager);;
        } catch (BadRequestException $exception) {
            return $this->render('registerEquipmentFailedForm.html.twig', $this->formatDataToFailedForm($equipmentDTO), new Response(Response::HTTP_BAD_REQUEST));
        }

        if ( $equipmentDTO->getSubmitType() === 'backToRegister' ) {
            return $this->redirectToRoute('app_register_equipment_form')->setStatusCode(code: Response::HTTP_OK);

        }
        return $this->redirectToRoute('app_equipments_render' )->setStatusCode(code: Response::HTTP_OK);
    }

    private function formatDataToFailedForm(EquipmentDTO $equipmentDTO): array
    {
        return [
            'description' => $equipmentDTO->getDescription(),
            'donator'     => $equipmentDTO->getDonator(),
            'receiptDate' => $equipmentDTO->getReceiptDate()
        ];
    }

    private function formatEquipmentData(array $equipments): array
    {
        $formated = [];

        foreach ($equipments as $equipment) {
            $formated[] = [
                'description'  => $equipment->getDescription(),
                'receiptDate'  => $equipment->getReceiptDate()->format('d-m-Y'),
                'donator'      => $equipment->getDonator()->getName(),
                'availability' => $equipment->getAvailability() === 1 ? 'Disponível' : 'Doado',
            ];
        }

        return $formated;
    }
}