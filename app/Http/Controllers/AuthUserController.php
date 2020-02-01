<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthUserController extends Controller
{

	public function show(Request $request)
	{
		return response(
			UserResource::make($request->user()),
			Response::HTTP_OK,
			['Content-Type' => 'application/vnd.api+json']
		);
	}
}
