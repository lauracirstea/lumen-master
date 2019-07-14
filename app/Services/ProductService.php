<?php
/**
 * Created by PhpStorm.
 * User: Laura
 * Date: 11.07.2019
 * Time: 12:04
 */

namespace App\Services;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductService
{
    public function validateCreateRequest(Request $request)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'full_price' => 'required',
            'photo' => 'required',
            'quantity' => 'required'
        ];

        $messages = [
            'name.required' => 'errors.name.required',
            'description.required' => 'errors.description.required',
            'category_id.required' => 'errors.category_id.required',
            'full_price.required' => 'errors.full_price.required',
            'photo.required' => 'errors.photo.required',
            'quantity.required' => 'errors.quantity.required'

        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    public function validateUpdateRequest(Request $request)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'full_price' => 'required',
            'photo' => 'required',
            'quantity' => 'required'
        ];

        $messages = [
            'name.required' => 'errors.name.required',
            'description.required' => 'errors.description.required',
            'category_id.required' => 'errors.category_id.required',
            'full_price.required' => 'errors.full_price.required',
            'photo.required' => 'errors.photo.required',
            'quantity.required' => 'errors.quantity.required'
        ];

        return Validator::make($request->all(), $rules, $messages);
    }
}
