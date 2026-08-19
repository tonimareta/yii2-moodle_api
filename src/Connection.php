<?php

namespace tonimareta\moodle;

use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\httpclient\Client;
use yii\httpclient\CurlTransport;
use yii\httpclient\Exception;
use yii\httpclient\Request;
use yii\web\HttpException;

class Connection extends Model
{
    public const REQUEST_URI = '/webservice/rest/server.php';

    /**
     * @var string
     */
    public string $url;

    /**
     * @var string
     */
    public string $token;

    /**
     * @var string
     */
    public string $format = Client::FORMAT_JSON;

    /**
     * @var string
     */
    public string $method = 'POST';

    /**
     * @var string
     */
    public string $function = '';

    /**
     * @var array
     */
    public array $params = [];

    /**
     * @var Client
     */
    protected Client $client;

    /**
     * @return void
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        parent::init();

        if (!$this->url || !$this->token) {
            throw new InvalidConfigException('The "url" or "token" property must be set in web config.');
        }

        $this->url = preg_replace('/\/$/', '', $this->url);

        $this->client = new Client([
            'baseUrl' => $this->buildBaseUrl(),
            'transport' => CurlTransport::class,
        ]);
    }

    /**
     * @return $this
     */
    public static function connect(string $function, array $params = []): static
    {
        return (new static())->setFunction($function)->setParams($params)->send();
    }

    /**
     * @return mixed
     * @throws InvalidConfigException
     */
    public function send(): mixed
    {
        $data = $this->getData();
        return $this->prepareData($data);
    }

    /**
     * @param string $function
     * @return $this
     */
    public function setFunction(string $function): static
    {
        $this->function = trim($function);
        return $this;
    }

    public function setMethod(string $method): static
    {
        $this->method = trim(strtoupper($method));
        return $this;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams(array $params): static
    {
        $this->params = $params;
        $this->params['wsfunction'] = $this->function;
        return $this;
    }

    /**
     * @return string
     */
    protected function buildBaseUrl(): string
    {
        return $this->url . self::REQUEST_URI . '?' . $this->buildQueryString([
            'wstoken' => $this->token,
            'moodlewsrestformat' => $this->format,
        ]);
    }

    /**
     * @param array $params
     * @return string
     */
    protected function buildQueryString(array $params): string
    {
        return http_build_query($params, '', '&');
    }

    /**
     * @param array $data
     * @return $this
     * @throws HttpException
     */
    protected function findException(array $data): static
    {
        if (!empty($data['exception'])) {
            throw new HttpException(500, implode(PHP_EOL, $data));
        }

        return $this;
    }

    /**
     * @return mixed
     * @throws InvalidConfigException
     * @throws Exception
     */
    protected function getData(): mixed
    {
        $response = $this->makeRequest()->send();
        $this->reset();

        if (!$response->getIsOk()) {
            throw new HttpException($response->getStatusCode(), 'Request for url: ' . $url . ' failed with data: ' . $response->getContent());
        }

        return $response->getData();
    }

    /**
     * @return Request
     * @throws InvalidConfigException
     */
    protected function makeRequest(): Request
    {
        $url = $this->buildQueryString($this->params);

        return $this->client->createRequest()
            ->setUrl($url)
            ->setMethod($this->method);
    }

    /**
     * @param array $data
     * @return array
     */
    protected function prepareData(array $data): array
    {
        return $data;
    }

    /**
     * @return void
     */
    protected function reset(): void
    {
        $this->function = '';
        $this->params = [];
    }
}