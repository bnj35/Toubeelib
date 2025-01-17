<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;

use toubeelib\application\actions\AbstractGatewayAction;

class GatewayRdvAction extends AbstractGatewayAction
{

    public __construct(Client $client)
    {
        $this->client = $client;
    }

public function __invoke(ServerRequestInterface $request): ResponseInterface
{
$method = $request->getMethod();
$path = $request->getUri()->getPath();
$options = ['query' => $request->getQueryParams()];
if ($method === 'POST' || $method === 'PUT' || $method === 'PATCH') {
$options['json'] = $request->getParsedBody();
}
$auth = $request->getHeader('Authorization') ?? null;
if (!empty($auth)) {
$options['headers'] = ['Authorization' => $auth];
}
try {
return $this->remote_service->request($method, $path, $options);
} catch (ConnectException | ServerException $e) {
throw new HttpInternalServerErrorException($request, " … ");
} catch (ClientException $e ) {
match($e->getCode()) {
401 => throw new HttpUnauthorizedException($request, " … "),
403 => throw new HttpForbiddenException($request, " … "),
404 => throw new HttpNotFoundException($request, " … ")
};
}
}
}