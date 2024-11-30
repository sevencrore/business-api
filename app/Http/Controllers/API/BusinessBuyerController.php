<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\BusinessBuyer;
use App\Http\Resources\BusinessBuyerResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class BusinessBuyerController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        $items = BusinessBuyer::paginate(2);  // Adjust pagination as needed

        $data = [
            'data' => BusinessBuyerResource::collection($items), // Format the items using resource
            'pagination' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
                'next_page_url' => $items->nextPageUrl(),
                'prev_page_url' => $items->previousPageUrl()
            ]
        ];

        // Return the response with formatted data and pagination info
        return $this->sendResponse($data, 'Business Buyers retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'business_id' => 'required|exists:businesses,id',
            'company_name' => 'required|string',
            'location' => 'required|string',
            'contact_details' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $businessBuyer = BusinessBuyer::create($input);

        return $this->sendResponse(new BusinessBuyerResource($businessBuyer), 'Business Buyer created successfully.',JSON_PRETTY_PRINT);
    }

  





    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): JsonResponse
    {
        $businessBuyer = BusinessBuyer::find($id);

        if (is_null($businessBuyer)) {
            return $this->sendError('Business Buyer not found.');
        }

        return $this->sendResponse(new BusinessBuyerResource($businessBuyer), 'Business Buyer retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'business_id' => 'required|exists:businesses,id',
            'company_name' => 'required|string',
            'location' => 'required|string',
            'contact_details' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $businessBuyer = BusinessBuyer::find($id);

        if (is_null($businessBuyer)) {
            return $this->sendError('Business Buyer not found.');
        }

        // Update fields
        $businessBuyer->business_id = $input['business_id'];
        $businessBuyer->company_name = $input['company_name'];
        $businessBuyer->location = $input['location'];
        $businessBuyer->contact_details = $input['contact_details'];
        $businessBuyer->save();

        return $this->sendResponse(new BusinessBuyerResource($businessBuyer), 'Business Buyer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): JsonResponse
    {
        $businessBuyer = BusinessBuyer::find($id);

        if (is_null($businessBuyer)) {
            return $this->sendError('Business Buyer not found.');
        }

        $businessBuyer->delete();

        return $this->sendResponse([], 'Business Buyer deleted successfully.');
    }

    /**
     * Delete multiple Business Buyers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->input('ids');

        if (empty($ids) || !is_array($ids)) {
            return $this->sendError('Invalid IDs provided.');
        }

        $count = BusinessBuyer::whereIn('id', $ids)->delete();

        return $this->sendResponse(null, "{$count} Business Buyers deleted successfully.");
    }



    public function getByBusinessId($business_id): JsonResponse
{
    // Fetch all records matching the provided business_id
    $businessBuyers = BusinessBuyer::where('business_id', $business_id)->get();

    // Check if any records are found
    if ($businessBuyers->isEmpty()) {
        return $this->sendError('No records found for the provided business_id.');
    }

    // Format the response using the BusinessBuyerResource
    return $this->sendResponse(
        BusinessBuyerResource::collection($businessBuyers),
        'Business Buyers retrieved successfully.'
    );
}



}


