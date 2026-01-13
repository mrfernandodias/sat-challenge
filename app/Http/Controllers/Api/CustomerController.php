<?php

namespace App\Http\Controllers\Api;

use App\Domain\Customer\DTOs\CustomerDTO;
use App\Domain\Customer\Services\CustomerService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    public function __construct(
        protected CustomerService $customerService
    ) {}

    public function index(): JsonResponse
    {
        $customers = $this->customerService->getAllCustomers();

        return response()->json([
            'data' => CustomerResource::collection($customers),
        ]);
    }

    public function show(Customer $customer): JsonResponse
    {
        return response()->json([
            'data' => new CustomerResource($customer),
        ]);
    }

    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $dto = CustomerDTO::fromStoreRequest($request);
        $customer = $this->customerService->createCustomer($dto);

        return response()->json([
            'message' => 'Cliente criado com sucesso.',
            'data' => new CustomerResource($customer),
        ], 201);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): JsonResponse
    {
        $dto = CustomerDTO::fromUpdateRequest($request);
        $customer = $this->customerService->updateCustomer($customer, $dto);

        return response()->json([
            'message' => 'Cliente atualizado com sucesso.',
            'data' => new CustomerResource($customer),
        ]);
    }

    public function destroy(Customer $customer): JsonResponse
    {
        $this->customerService->deleteCustomer($customer);

        return response()->json([
            'message' => 'Cliente excluído com sucesso.',
        ]);
    }
}
