<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Business;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\BusinessResource;
use Illuminate\Http\JsonResponse;
// use Illuminate\Support\Facades\DB;
use App\Models\BusinessBuyer;
use App\Http\Resources\BusinessBuyerResource;


class BusinessController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        
        //$items = Bussiness::paginate(1); 
        
        $items = Business::paginate(10)->appends(['sort' => 'name']);

        $data = [
            'data' => BusinessResource::collection($items->items()), // Format the items
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
        return $this->sendResponse($data, 'Business retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request): JsonResponse
    // {
    //     $input = $request->all();

    //     $validator = Validator::make($input, [
    //         'business_name' => 'required',
    //         'address' => 'required',
    //         'city' => 'required',
    //         'postal_code' => 'nullable|string',
    //         'phone_number' => 'required',
    //         'website' => 'required',
    //         'description' => 'nullable|string',
    //         'keywords' => 'required',
    //         'is_approved' => 'nullable|string',
    //         'contact_person_name'=>'string|nullable',
    //         'contact_person_email'=>'string|nullable',
    //     ]);

    //     if($validator->fails()){
    //         return $this->sendError('Validation Error.', $validator->errors());       
    //     }

    //     $business = Business::create($input);

    //     return $this->sendResponse(new BusinessResource($business), 'Business created successfully.');
    // } 

    public function store(Request $request): JsonResponse
    {
        $input = $request->all();
    
        // Validate the main business fields
        $validator = Validator::make($input, [
            'business_name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'postal_code' => 'nullable|string',
            'phone_number' => 'required',
            'website' => 'required',
            'description' => 'nullable|string',
            'keywords' => 'required',
            'is_approved' => 'nullable|string',
            'contact_person_name' => 'string|nullable',
            'contact_person_email' => 'string|nullable',
            'prospective_buyers' => 'nullable|array', // Ensure prospective_buyers is an array
            'prospective_buyers.*.companyName' => 'required_with:prospective_buyers|string',
            'prospective_buyers.*.location' => 'required_with:prospective_buyers|string',
            'prospective_buyers.*.contactDetails' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
    
        try {
            // Wrap the entire operation in a database transaction
            // DB::beginTransaction();
    
            // Create the business
            $business = Business::create([
                'business_name' => $input['business_name'],
                'address' => $input['address'],
                'city' => $input['city'] ?? null,
                'postal_code' => $input['postal_code'] ?? null,
                'phone_number' => $input['phone_number'] ?? null,
                'website' => $input['website'],
                'description' => $input['description'] ?? null,
                'keywords' => $input['keywords'] ?? null,
                'is_approved' => $input['is_approved'] ?? 0,
                'contact_person_name' => $input['contact_person_name'],
                'contact_person_email' => $input['contact_person_email'],
            ]);
    
            // If prospective buyers exist, loop through them and save them to the database
            if (!empty($input['prospective_buyers'])) {
                foreach ($input['prospective_buyers'] as $buyer) {
                    BusinessBuyer::create([
                        'business_id' => $business->id,
                        'company_name' => $buyer['companyName'],
                        'location' => $buyer['location'],
                        'contact_details' => $buyer['contactDetails'] ?? null,
                    ]);
                }
            }
    
            // Commit the transaction
            // DB::commit();
    
            return $this->sendResponse(new BusinessResource($business), 'Business and prospective buyers created successfully.');
    
        } catch (\Exception $e) {
            // Rollback the transaction in case of any error
            // DB::rollBack();
    
            return $this->sendError('An error occurred while saving the data.', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }
    



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): JsonResponse
    {
        $business = Business::find($id);

        if (is_null($business)) {
            return $this->sendError('Business not found.');
        }

        return $this->sendResponse(new BusinessResource($business), 'Business retrieved successfully.',JSON_PRETTY_PRINT);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Business $business): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'business_name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'postal_code' => 'nullable',
            'phone_number' => 'required',
            'website' => 'required',
            'description' => 'nullable',
            'keywords' => 'required',
            'is_approved' => 'required',
            'contact_person_name'=>'string|nullable',
            'contact_person_email'=>'string|nullable',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $business->business_name = $input['business_name'];
        $business->address = $input['address'];
        $business->city = $input['city'];
        $business->postal_code = $input['postal_code'];
        $business->phone_number = $input['phone_number'];
        $business->website = $input['website'];
        $business->description = $input['description'];
        $business->keywords = $input['keywords'];
        $business->is_approved = $input['is_approved'];
        $business->contact_person_name = $input['contact_person_name'];
        $business->contact_person_email = $input['contact_person_email'];
        $business->save();

        return $this->sendResponse(new BusinessResource($business), 'Business updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy(Business $business): JsonResponse
    // {
    //     $business->delete();

    //     return $this->sendResponse([], 'Business deleted successfully.');
    // }

    public function edit($id): JsonResponse
    {
        $business = Business::find($id);

        if (is_null($business)) {
            return $this->sendError('Business not found.');
        }

        return $this->sendResponse(new BusinessResource($business), 'Business data retrieved successfully.');
    }






    public function destroy($id)
    {
        $business = Business::find($id);

        if ($business) {
            // Perform a soft delete
            $business->delete();

            // If you want to hard delete instead, use the line below:
            // $business->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Business deleted successfully.',
                'data' => new BusinessResource($business),
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Business not found.',
        ], 404);
    }

    // public function restore($id)
    // {
    //     $business = Business::withTrashed()->find($id);

    //     if ($business) {
    //         $business->restore(); // Restores the soft-deleted record
    //         return $this->sendResponse(new BusinessResource($business), 'Business restored successfully.');
    //     }

    //     return $this->sendError('Business not found.');
    // }

    // public function forceDelete($id)
    // {
    //     $business = Business::withTrashed()->find($id);

    //     if ($business) {
    //         $business->forceDelete(); // Permanently deletes the record
    //         return $this->sendResponse(new BusinessResource($business), 'Business permanently deleted.');
    //     }

    //     return $this->sendError('Business not found.');
    // }

    // public function trashed()
    // {
    //     $trashedBusinesses = Business::onlyTrashed()->get(); // Retrieves only soft-deleted records

    //     return $this->sendResponse($trashedBusinesses, 'Trashed businesses retrieved successfully.');
    // }
    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (empty($ids) || !is_array($ids)) {
            return $this->sendError('Invalid IDs provided.');
            
        }
    
        $count = Business::whereIn('id', $ids)->delete(); // Soft deletes the records

        return $this->sendResponse(null, "{$count} businesses deleted successfully.");
    }


    public function restoreMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (empty($ids) || !is_array($ids)) {
            return $this->sendError('Invalid IDs provided.');
        }

        $count = Business::withTrashed()->whereIn('id', $ids)->restore(); // Restores the soft-deleted records

        return $this->sendResponse(null, "{$count} businesses restored successfully.");
    }
    public function forceDeleteMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (empty($ids) || !is_array($ids)) {
            return $this->sendError('Invalid IDs provided.');
        }

        $count = Business::withTrashed()->whereIn('id', $ids)->forceDelete(); // Permanently deletes the records

        return $this->sendResponse(null, "{$count} businesses permanently deleted.");
    }
    public function trashedMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (empty($ids) || !is_array($ids)) {
            return $this->sendError('Invalid IDs provided.');
        }

        $trashedBusinesses = Business::onlyTrashed()->whereIn('id', $ids)->get(); // Retrieves only specified soft-deleted records

        return $this->sendResponse($trashedBusinesses, 'Trashed businesses retrieved successfully.');
    }


}
