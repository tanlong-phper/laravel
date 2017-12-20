<?php

namespace App\Http\Requests\Organization\Department;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;

class HouseVerificationRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		if (Auth::user()->hasPermission('create_department')) {
			return true;
		}
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
				'name' => 'required',
				'parent_id' => 'required|numeric',
				'leader' => 'numeric',
				'buttman' => 'numeric',
				'grade' => 'required'
		];
	}

	/**
	 * Error message
	 * @return array
	 */
	public function messages()
	{
		return [
				'name.required' => '部门名称为必填项',
				'parent_id.required' => '上级部门为必填项',
				'parent_id.numeric' => '上级部门 ID 必须为数字',
				'leader.numeric' => '部门领导 ID 必须为数字',
				'buttman.numeric' => '部门对接人 ID 必须为数字',
				'grade.required' => '部门职系为必填'
		];
	}

	/**
	 * Error Response
	 * @param array $errors
	 * @return JsonResponse
	 */
	public function response(array $errors)
	{
		return new JsonResponse([
				'status' => 'error',
				'data' => $errors
		], 422);
	}
}