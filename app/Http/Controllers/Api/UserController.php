<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Services\CustomerService;
use App\Transformers\SuccessResource;
use Illuminate\Http\Request;
use App\Http\Requests\Api\Card\UpdateRequest;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * @var CustomerService
     */
    protected CustomerService $customerService;

    public function __construct(UserService $userService, CustomerService $customerService)
    {
        $this->userService = $userService;
        $this->customerService = $customerService;
    }

    public function index()
    {
        $result = $this->userService->listUser();

        return SuccessResource::make($result);
    }

    public function detail(int $id)
    {
        $result = $this->userService->detail($id);

        return SuccessResource::make($result);
    }

    /**
     * Update card
     *
     * @param Request $request
     */
    public function updateCard(Request $request)
    {
        $result = $this->customerService->updateCard($request);

        if ($result === true) {
            return response()->json(['success' => true, 'message' => 'The payment card has been updated successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'An error occurred while updating the payment card.']);
        }
    }

    /**
     * Update card
     *
     * @param Request $request
     */
    public function deleteCard(Request $request)
    {
        $result = $this->customerService->deleteCard($request);

        if ($result === true) {
            return response()->json(['success' => true, 'message' => 'The payment card has been updated successfully.']);
        } else {
            return response()->json(['error' => false, 'message' => 'An error occurred while updating the payment card.']);
        }
    }
 }

