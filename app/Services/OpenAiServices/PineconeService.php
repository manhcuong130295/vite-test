<?php

namespace App\Services\OpenAiServices;

use App\Exceptions\ApiException;
use GuzzleHttp\Client;
use Illuminate\Http\Response;

class PineconeService
{
    /**
     * @var array
     */
    protected array $config;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var array
     */
    protected $index;

    /**
     * @var String
     */
    protected string $pineconeIndex;

    /**
     * PineconeService constructor.
     */
    public function __construct()
    {
        $this->config = config('services');
        $this->url = "https://controller." . $this->config['pinecone']['environment'] . ".pinecone.io/databases";
        $this->client = new Client([
            'headers' => [
                'Api-Key' => $this->config['pinecone']['api_key'],
                'Accept' => 'text/plain',
                'Content-Type' => 'application/json'
            ],
        ]);
        $this->pineconeIndex = $this->config['pinecone']['pinecone_index'];
        $this->index = $this->describeIndex($this->pineconeIndex);
    }

    /**
     * Describe the index
     *
     * @param string $nameIndex
     *
     * @return array
     */
    public function describeIndex(string $nameIndex): array
    {
        $response = $this->client->request('GET', $this->url . "/$nameIndex");
        $body = $response->getBody()->getContents();

        return json_decode($body, true);
    }

    /**
     * Get index host
     *
     * @param array $index
     *
     * @return string
     */
    public function getIndexUrl(array $index): string
    {
        return 'https://' . $index['status']['host'];
    }

    /**
     * Save vector
     *
     * @param array $param
     *
     * @return bool
     */
    public function save(array $params): bool
    {
        $host = $this->getIndexUrl($this->index);
        $response = $this->client->request('POST', $host . '/vectors/upsert', ['json' => $params]);

        return $response->getStatusCode() == Response::HTTP_OK;
    }

    /**
     * Query vector to get id and text
     *
     * @param array $vectors
     * @param string $projectId
     *
     * @return array
     */
    public function query(array $vectors, string $projectId): array
    {
        $host = $this->getIndexUrl($this->index);

        $params = [
            'vector' => $vectors,
            'topK' => 3,
            'includeMetadata' => true,
            'filter' => [
                'project_id' => "$projectId"
            ]
        ];

        $response = $this->client->request('POST', $host . '/query', ['json' => $params]);
        $response = json_decode($response->getBody()->getContents(), true);

        return $response;
    }

    /**
     * Delete vector
     *
     * @param int $id
     *
     * @return bool
     */
    public function delete(int $id): bool
    {
        $host = $this->getIndexUrl($this->index);
        
        $params = [
            'deleteAll' => false,
            'ids' => ["$id"]
        ];
        $response = $this->client->request('POST', $host . '/vectors/delete', ['json' => $params]);

        return $response->getStatusCode() == Response::HTTP_OK;
    }
}
