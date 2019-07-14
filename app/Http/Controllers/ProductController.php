<?php
/**
 * Created by PhpStorm.
 * User: Laura
 * Date: 11.07.2019
 * Time: 12:16
 */

namespace App\Http\Controllers;

use App\Helpers\ErrorCodes;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /** @var ProductService */
    protected $productService;

    /**
     * ProductController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->productService = new ProductService();
    }

    /**
     * Create a product
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        try {
            /** @var \Illuminate\Validation\Validator $validator */
            $validator = $this->productService->validateCreateRequest($request);

            if (!$validator->passes()) {
                return $this->returnError($validator->messages(), ErrorCodes::REQUEST_ERROR);
            }

            $sale_price = 0;
            $quantity = $request->get('quantity');

            if($quantity >= 100){
                $sale_price =$request->get('full_price') - $request->get('full_price') / 100*10 ;
            }
            if($quantity < 100){
                $sale_price =$request->get('full_price');
            }


            $product = new Product([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'category_id' => $request->get('category_id'),
                'full_price' => $request->get('full_price'),
                'photo' => $request->get('photo'),
                'quantity' => $request->get('quantity'),
                'sale_price' =>  $sale_price,
            ]);

            $product->save();

            return $this->returnSuccess($product);
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage(), ErrorCodes::FRAMEWORK_ERROR);
        }
    }

    /**
     * Get all products
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll(Request $request)
    {
        try {
            $pagParams = $this->getPaginationParams($request);

            $products = Product::where('id', '!=', null);

            $paginationData = $this->getPaginationData($products, $pagParams['page'], $pagParams['limit']);

            $products = $products->offset($pagParams['offset'])->limit($pagParams['limit'])->get();

            return $this->returnSuccess($products, $paginationData);
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage(), ErrorCodes::FRAMEWORK_ERROR);
        }
    }

    /**
     * Get one product
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        try {
            $product = Product::where('id', $id)->first();

            if (!$product) {
                return $this->returnError('errors.product.not_found', ErrorCodes::NOT_FOUND_ERROR);
            }

            return $this->returnSuccess($product);
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage(), ErrorCodes::FRAMEWORK_ERROR);
        }
    }

    /**
     * Update a category
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        try {
            $product = Product::where('id', $id)->first();

            if (!$product) {
                return $this->returnError('errors.product.not_found', ErrorCodes::NOT_FOUND_ERROR);
            }

            /** @var \Illuminate\Validation\Validator $validator */
            $validator = $this->productService->validateUpdateRequest($request);

            if (!$validator->passes()) {
                return $this->returnError($validator->messages(), ErrorCodes::REQUEST_ERROR);
            }

            $product->name = $request->get('name');
            $product->description = $request->get('description');
            $product->category_id = $request->get('category_id');
            $product->full_price = $request->get('full_price');
            $product->photo = $request->get('photo');
            $product->quantity = $request->get('quantity');
            $product->sale_price = $request->get('sale_price');

            $product->save();

            return $this->returnSuccess($product);
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage(), ErrorCodes::FRAMEWORK_ERROR);
        }
    }

    /**
     * Delete a product
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        try {
            $product = Product::where('id', $id)->first();

            if (!$product) {
                return $this->returnError('errors.product.not_found', ErrorCodes::NOT_FOUND_ERROR);
            }

            $product->delete();

            return $this->returnSuccess();
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage(), ErrorCodes::FRAMEWORK_ERROR);
        }
    }

}