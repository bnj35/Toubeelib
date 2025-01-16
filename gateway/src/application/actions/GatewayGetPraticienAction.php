<?php
    namespace toubeelib\application\actions;

    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Message\ResponseInterface as Response;

    use toubeelib\domain\praticien\PraticienRepository;
    use guzzlehttp\Client;

    class GatewayGetPraticienAction extends GatewayAbstractAction
    {
        private PraticienRepository $praticienRepository;

        public function __construct(PraticienRepository $praticienRepository)
        {
            $this->praticienRepository = $praticienRepository;
        }

        public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, Client $client): ResponseInterface
        {
            $praticiens = $this->praticienRepository->getPraticiens();
            $response = [
                "type" => "collection",
                "locale" => "fr-FR",
                "praticiens" => $praticiens
            ];
            return JsonRenderer::render($rs, 200, $response);
        }
    }