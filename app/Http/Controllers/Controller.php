<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var $service
     */
    protected $service;

    /** @var  ValidatorInterface $validator */
    protected $validator;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $limit = $request->query->get('limit', 15);
        if (!is_numeric($limit)) $limit = 15;
        return response()->json($this->service->all($limit));
    }

    /**
     * Display the specified resource.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): \Illuminate\Http\JsonResponse
    {
        $response = $this->service->find($id);
        if (isset($response['error']))  return response()->json($response,404);
        return response()->json($this->service->find($id));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidatorException
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validator?->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
        return response()->json( $this->service->create($request->all()));
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws ValidatorException
     */
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        if (!is_numeric($id))  return response()->json(['error'=> true,'message'=> 'Indentificação Inválida'],422);
        $this->validator?->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
        return response()->json($this->service->update($request->all(), $id));
    }

    /**
     * Restore the specified resource from storage.
     * @param $id
     * @return array
     */
    public function restore($id): \Illuminate\Http\JsonResponse
    {
        if (!is_numeric($id))  return response()->json(['error'=> true,'message'=> 'Indentificação invalida'],422);
        return $this->service->restore($id);
    }

    /**
     * Remove the specified resource from storage.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        if (!is_numeric($id))  return response()->json(['error'=> true,'message'=> 'Indentificação invalida'],422);
        return response()->json($this->service->delete($id), 200);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function findWhere(array $data):mixed
    {
        return $this->service->findWhere($data);
    }


    /**
     * @return Client
     */
    protected function getHttpClient():Client
    {
        return new Client(['verify' => false]);
    }
}
