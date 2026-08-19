<?php

namespace ngmu\moodle;

use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\httpclient\Client;
use yii\httpclient\CurlTransport;

class WSClient extends Model
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
    public string $format;

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
     * @return string
     */
    protected function buildBaseUrl(): string
    {
        return $this->url . self::REQUEST_URI . '?' . http_build_query([
            'wstoken' => $this->token,
            'moodlewsrestformat' => $this->format ?? Client::FORMAT_JSON,
        ]);
    }
}