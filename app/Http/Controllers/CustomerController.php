<?php

namespace App\Http\Controllers;

use App\Domain\Customer\DTOs\CustomerDTO;
use App\Domain\Customer\Services\CustomerService;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function __construct(
        protected CustomerService $customerService
    ) {}

    public function index(): View
    {
        return view('customers');
    }

    public function data(): JsonResponse
    {
        $customers = $this->customerService->getAllCustomers();

        return response()->json([
            'data' => CustomerResource::collection($customers),
        ]);
    }

    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $dto = CustomerDTO::fromStoreRequest($request);
        $customer = $this->customerService->createCustomer($dto);

        return response()->json([
            'success' => true,
            'message' => 'Cliente criado com sucesso.',
            'customer' => new CustomerResource($customer),
        ], 201);
    }

    public function show(Customer $customer): JsonResponse
    {
        return response()->json([
            'customer' => new CustomerResource($customer),
        ]);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): JsonResponse
    {
        $dto = CustomerDTO::fromUpdateRequest($request);
        $customer = $this->customerService->updateCustomer($customer, $dto);

        return response()->json([
            'success' => true,
            'message' => 'Cliente atualizado com sucesso.',
            'customer' => new CustomerResource($customer),
        ]);
    }

    public function destroy(Customer $customer): JsonResponse
    {
        $this->customerService->deleteCustomer($customer);

        return response()->json([
            'success' => true,
            'message' => 'Cliente excluído com sucesso.',
        ]);
    }
}
