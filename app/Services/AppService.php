<?php

namespace App\Services;

use App\AppHelper;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class AppService
 * @package App\Services
 */
class AppService
{
    /**
     * @var
     */
    protected $repository;

    /**
     * @param int $limit
     * @return mixed
     */
    public function all($limit = 20):mixed
    {
        return $this->repository->paginate($limit);
    }

    /**
     * @param array $data
     * @param bool $skipPresenter
     * @return mixed
     */
    public function create(array $data,bool $skipPresenter = false):mixed
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        return $skipPresenter ? $this->repository->skipPresenter()->create($data) : $this->repository->create($data);
    }

    /**
     * @param int $id
     * @param bool $skip_presenter
     * @return mixed
     */
    public function find(int $id, bool $skip_presenter = false):mixed
    {
        try {
            if ($skip_presenter) {
                return $this->repository->skipPresenter()->find($id);
            }
            return $this->repository->find($id);
        }catch (\Exception){
           return ['error'=>'true','message' => 'Não encontrado'];
        }

    }

    /**
     * @param array $data
     * @param int $id
     * @param bool $skipPresenter
     * @return mixed
     */
    public function update(array $data, int $id, bool $skipPresenter = false):mixed
    {
        try {
            if (isset($data['password']) && !empty($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }
            return $skipPresenter ? $this->repository->skipPresenter()->update($data, $id) : $this->repository->update($data, $id);
        } catch (\Exception) {
            return ['error' => 'true', 'message' => 'Não Modificado'];
        }
    }

    /**
     * @param array $data
     * @param bool $first
     * @param bool $presenter
     * @return mixed
     */
    public function findWhere(array $data, bool $first = false, bool $presenter = false):mixed
    {
        if ($first) {
            return $this->repository->skipPresenter()->findWhere($data)->first();
        }
        if ($presenter) {
            return $this->repository->findWhere($data);
        }
        return $this->repository->skipPresenter()->findWhere($data);

    }

    /**
     * @param array $data
     * @return mixed
     */
    public function findLast(array $data):mixed
    {
        return $this->repository->skipPresenter()->findWhere($data)->last();
    }

    /**
     * Remove the specified resource from storage using softDelete.
     =
     * @param int $id
     * @return array
     */
    public function delete(int $id):array
    {
        return ['success' => (boolean)$this->repository->delete($id)];
    }


    /**
     * @param string $value
     * @return string
     */
    public function removeSpaces(string $value):string
    {
        return AppHelper::removeSpaces($value);
    }

    /**
     * @param string $value
     * @return mixed
     */
    public function removeSpecialCharacters(string $value):mixed
    {
        return AppHelper::removeSpecialCharacters($value);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function removeAccentuation($value):mixed
    {
        return AppHelper::removeAccentuation($value);
    }

    /**
     * @param $date
     * @return false|string
     */
    public function formatDateDB($date):false|string
    {
        return AppHelper::formatDateDB($date);
    }

    /**
     * @param $value
     * @return int|null
     */
    public function getAgeByDateBirth($value):int|null
    {
        return AppHelper::getAgeByDateBirth($value);
    }


    /**
     * @return Client
     */
    protected function getHttpClient():Client
    {
        return new Client(['verify' => false]);
    }

    /**
     * @param string $resource
     * @param string $method
     * @param array $params
     * @param bool $json
     * @return array
     */
    public function sendCapitalGiroBff(string $resource, string $method, array $params, bool $json = false): array
    {
        $urlParams = '';

        if($method == 'GET'){
            $urlParams = $params;
            $params = [];
        }

        $endpoint = $this->baseUrlBff() . $resource . $urlParams;

        $options = [
            'headers' => [
                'Accept'  	    => '*/*',
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer '.env('CREDIT_ESTIMATE_CALCULATOR_TOKEN', 'credit-estimate-calculator-token'),
                'apikey'        => env('CAPITAL_GIRO_BFF_API_KEY', 'capital-giro-bff-api-key')
            ]
        ];

        //if ($json) $options['body'] = json_encode($params); else{ $options['form_params'] = $params;}
        return $this->send($json, $params, $options, $method, $endpoint);
    }

    /**
     * @return string
     */
    private function baseUrlBff():string
    {
        return env('CREDIT_ESTIMATE_CALCULATOR_URL', 'https://capital-de-giro-bff.fretepago.dev.br/');
    }

    /**
     * @param string $resource
     * @param string $method
     * @param array $params
     * @param bool $json
     * @return array
     */
    public function sendCore(string $resource, string $method, array $params, bool $json = false): array
    {
        $urlParams = '';

        if ($method == 'GET') {
            $urlParams = $params;
            $params = [];
        }

        $endpoint = env('CORE_API_URL') . $resource . $urlParams;

        $options = [
            'headers' => [
                'Accept' => '*/*',
                'Content-Type' => 'application/json'
            ]
        ];

        return $this->send($json, $params, $options, $method, $endpoint);
    }

    /**
     * @param bool $json
     * @param array $params
     * @param array $options
     * @param string $method
     * @param string $endpoint
     * @return array
     */
    public function send(bool $json, array $params, array $options, string $method, string $endpoint): array
    {
        if ($json) {
            $options['body'] = json_encode($params);
        } else {
            $options['json'] = $params;
        }

        try {
            $response = $this->getHttpClient()->request($method, $endpoint, $options);
            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }
}
