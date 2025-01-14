<?php

// namespace toubeelib\application\middlewares;

// use Psr\Http\Message\ServerRequestInterface;
// use Psr\Http\Server\RequestHandlerInterface;
// use Slim\Exception\HttpUnauthorizedException;
// use Slim\Routing\RouteContext;
// use toubeelib\core\services\praticien\AuthorizationPraticienServiceInterface;

// class AuthzPraticien
// {

//     private AuthorizationPraticienServiceInterface $authzPraticienService;

//     public function __construct(AuthorizationPraticienServiceInterface $authzPraticienService)
//     {
//         $this->authzPraticienService = $authzPraticienService;
//     }

//     public function __invoke(ServerRequestInterface $rq, RequestHandlerInterface $next)
//     {
//         $user = $rq->getAttribute('auth');
//         $routeContext = RouteContext::fromRequest($rq) ;
//         $route = $routeContext->getRoute();
//         $praticienId = $route->getArguments()['praticien_id'] ;
//         if($this->authzPraticienService->isGranted($user->id,1,$praticienId, 10 ))
//             return $next->handle($rq);
//         else
//             throw new HttpUnauthorizedException($rq, 'Unauthorized');
//     }

// }