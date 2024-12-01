<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\CategoryFullResource;
use App\Http\Resources\API\CategoryResource;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
    public function getCategoryList(Request $request)
    {

        $user = auth()->user();

        if (!$user) {
            abort(401);
        }

        $service = Service::where('service_type', 'service')->with(['providers', 'category', 'serviceRating'])->orderBy('created_at', 'desc');

        $category = Category::onlyTrashed()->get();
        $categoriesID = $category->pluck('id');
        $service = $service->whereNotIn('category_id', $categoriesID);

        $service = $service->where('status', 1);

        $service->whereHas('providers', function ($a) use ($user) {
            $a->where('city_id', $user->city_id);
        });

        $service->orderBy('created_at', 'desc');

        $activeCats = $service->pluck('category_id')->toArray();

        $category = Category::whereIn('id', $activeCats)->with('subCategories')->where('status', 1);

//        if (auth()->user() !== null) {
//            if (auth()->user()->hasRole('admin')) {
//                $category = new Category();
//                $category = $category->withTrashed();
//            }
//        }

        if ($request->has('is_featured')) {
            $category->where('is_featured', $request->is_featured);
        }

        $per_page = config('constant.PER_PAGE_LIMIT');
        if ($request->has('per_page') && !empty($request->per_page)) {
            if (is_numeric($request->per_page)) {
                $per_page = $request->per_page;
            }
            if ($request->per_page === 'all') {
                $per_page = $category->count();
            }
        }

        $category = $category->orderBy('name', 'asc')->paginate($per_page);
        $items = CategoryResource::collection($category);
        $response = [
            'pagination' => [
                'total_items' => $items->total(),
                'per_page' => $items->perPage(),
                'currentPage' => $items->currentPage(),
                'totalPages' => $items->lastPage(),
                'from' => $items->firstItem(),
                'to' => $items->lastItem(),
                'next_page' => $items->nextPageUrl(),
                'previous_page' => $items->previousPageUrl(),
            ],
            'data' => $items,
        ];

        return comman_custom_response($response);
    }

    public function getCategoryFullList(Request $request)
    {
        $category = Category::with('subCategories')->where('status', 1);
        if (auth()->user() !== null) {
            if (auth()->user()->hasRole('admin')) {
                $category = new Category();
                $category = $category->withTrashed();
            }
        }
        if ($request->has('is_featured')) {
            $category->where('is_featured', $request->is_featured);
        }

        $per_page = config('constant.PER_PAGE_LIMIT');
        if ($request->has('per_page') && !empty($request->per_page)) {
            if (is_numeric($request->per_page)) {
                $per_page = $request->per_page;
            }
            if ($request->per_page === 'all') {
                $per_page = $category->count();
            }
        }

        $category = $category->orderBy('name', 'asc')->paginate($per_page);
        $items = CategoryFullResource::collection($category);

        $response = [
            'pagination' => [
                'total_items' => $items->total(),
                'per_page' => $items->perPage(),
                'currentPage' => $items->currentPage(),
                'totalPages' => $items->lastPage(),
                'from' => $items->firstItem(),
                'to' => $items->lastItem(),
                'next_page' => $items->nextPageUrl(),
                'previous_page' => $items->previousPageUrl(),
            ],
            'data' => $items,
        ];

        return comman_custom_response($response);
    }

}
