<?php

namespace NextcloudApiWrapper;

use Symfony\Component\HttpClient\HttpClient;

class Connection
{
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';

    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @param string $basePath The base path to nextcloud api, IE. 'http://nextcloud.mydomain.com/ocs/'
     * @param string $username The username of the user performing api calls
     * @param string $password The password of the user performing api calls
     */
    public function __construct($basePath, $username, $password)
    {
        $this->httpClient = HttpClient::create(
            [
                'base_uri' => $basePath,
            ]
        );
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Performs a simple request
     * @param $verb
     * @param $path
     * @param null $params
     * @return NextcloudResponse
     * @throws NCException
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function request($verb, $path, $params = null)
    {

        $params = $params === null ? $this->getBaseRequestParams() : $params;
        $response = $this->httpClient->request($verb, $path, $params);

        return new NextcloudResponse($response);
    }

    /**
     * Performs a request adding the application/x-www-form-urlencoded header
     * @param $verb
     * @param $path
     * @param array $params
     * @return NextcloudResponse
     * @throws NCException
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function pushDataRequest($verb, $path, array $params = [])
    {

        $params = empty($params) ? $this->getBaseRequestParams() : $params;
        $params['headers']['Content-Type'] = 'application/x-www-form-urlencoded';

        return $this->request($verb, $path, $params);
    }

    /**
     * Performs a request sending form data.
     * Required header automatically added by CURl
     * @param $verb
     * @param $path
     * @param array $formParams
     * @return NextcloudResponse
     * @throws NCException
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function submitRequest($verb, $path, array $formParams)
    {

        return $this->request(
            $verb,
            $path,
            array_merge(
                $this->getBaseRequestParams(),
                [
                    'body' => $formParams,
                ]
            )
        );
    }

    /**
     * Returns the base request parameters required by nextcloud to
     * answer api calls
     * @return array
     */
    protected function getBaseRequestParams()
    {

        return [
            'headers' => [
                'OCS-APIRequest' => 'true',
            ],

            'basic_auth' => [
                $this->username,
                $this->password,
            ],
        ];
    }
}