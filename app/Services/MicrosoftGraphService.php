<?php

namespace App\Services;

use Exception;
use Microsoft\Graph\Generated\Models\User;
use Microsoft\Graph\Generated\Users\UsersRequestBuilderGetRequestConfiguration;
use Microsoft\Graph\GraphServiceClient;
use Microsoft\Kiota\Authentication\Oauth\ClientCredentialContext;

class MicrosoftGraphService
{
    public GraphServiceClient $client;

    public function __construct(
        string $tenantId,
        string $clientId,
        string $clientSecret
    ) {

        $tokenRequestContext = new ClientCredentialContext(
            $tenantId,
            $clientId,
            $clientSecret
        );

        $this->client = new GraphServiceClient($tokenRequestContext);
    }

    /**
     * Search for a user by email
     *
     * @return User[]
     *
     * @throws Exception
     */
    public function searchForUserByEmail(string $needle, string $emailDomain = 'dfo-mpo.gc.ca'): array
    {

        $config = new UsersRequestBuilderGetRequestConfiguration;
        $headers = [
            'ConsistencyLevel' => 'eventual',
        ];
        $config->headers = $headers;
        $params = UsersRequestBuilderGetRequestConfiguration::createQueryParameters();
        $params->top = 20;
        $params->filter = "endswith(mail, '{$emailDomain}')";
        $params->filter .= " and startswith(mail, '{$needle}')";
        $params->count = true;
        $config->queryParameters = $params;
        $users = $this->client->users()->get($config)->wait();
        return $users->getValue();

    }
}
