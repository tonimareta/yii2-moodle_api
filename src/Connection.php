<?php

namespace tonimareta\moodle;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
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
    public string $url = '';

    /**
     * @var string
     */
    public string $token = '';

    /**
     * @var string
     */
    public string $format = Client::FORMAT_JSON;

    /**
     * @var string
     */
    public string $method = 'post';

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

        $this->client = new Client(['baseUrl' => $this->url]);
    }

    /**
     * @return mixed
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function send(): mixed
    {
        $data = $this->getData();

        if (!is_array($data)) {
            return true;
        }

        $this->checkException($data);

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

    /**
     * @param string $method
     * @return $this
     */
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
        return $this;
    }

    /**
     * @return string
     */
    protected function buildFullUrl(): string
    {
        if (!$this->function) {
            throw new InvalidParamException('The "function" property can not be empty. Use setFunction("function") method.');
        }

        return self::REQUEST_URI . '?' . $this->buildQueryString([
            'wstoken' => $this->token,
            'moodlewsrestformat' => $this->format,
            'wsfunction' => $this->function,
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
     * @throws Exception
     */
    protected function checkException(array $data): static
    {
        if (!empty($data['exception'])) {
            throw new Exception(implode(PHP_EOL, $data), 500);
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
        $request = $this->makeRequest();
        $response = $request->send();
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
        $url = $this->buildFullUrl();

        return $this->client->createRequest()
            ->setUrl($url)
            ->setMethod($this->method)
            ->setData($this->params);
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