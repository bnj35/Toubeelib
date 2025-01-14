<?php

// namespace toubeelib\application\middlewares;

// use Psr\Http\Message\ServerRequestInterface;
// use Psr\Http\Server\RequestHandlerInterface;
// use Slim\Exception\HttpUnauthorizedException;
// use Slim\Routing\RouteContext;
// use toubeelib\core\services\rendez_vous\AuthorizationRdvServiceInterface;

// class AuthzRdv
// {
//     private AuthorizationRdvServiceInterface $authzRdvService;

//     public function __construct(AuthorizationRdvServiceInterface $authzRdvService)
//     {
//         $this->authzRdvService = $authzRdvService;
//     }

//     public function __invoke(ServerRequestInterface $rq, RequestHandlerInterface $next)
//     {
//         $user = $rq->getAttribute('auth');
//         $routeContext = RouteContext::fromRequest($rq);
//         $route = $routeContext->getRoute();
//         $RdvId = $route->getArguments()['rdv_id'];
//         if($this->authzRdvService->isGranted($user->id,1, $RdvId, 0 ))
//             return $next->handle($rq);
//         else if ($this->authzRdvService->isGranted($user->id,1, $RdvId, 10 ))
//             return $next->handle($rq);
//         else
//             throw new HttpUnauthorizedException($rq, 'Unauthorized');
//     }

// }