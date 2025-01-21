<?php

namespace App\Auth\Actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;

class ValidateTokenAction
{
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs): ResponseInterface
    {
        $authHeader = $rq->getHeader('Authorization');
        if (empty($authHeader)) {
            return $rs->withStatus(401)->write('Authorization header is missing');
        }

        $token = str_replace('Bearer ', '', $authHeader[0]);
        $secretKey = getenv('JWT_SECRET');

        try {
            JWT::decode($token, $secretKey, ['HS256']);
            return $rs->withStatus(200)->write('Token is valid');
        } catch (ExpiredException $e) {
            return $rs->withStatus(401)->write('Token has expired');
        } catch (SignatureInvalidException $e) {
            return $rs->withStatus(401)->write('Invalid token signature');
        } catch (BeforeValidException $e) {
            return $rs->withStatus(401)->write('Token is not yet valid');
        } catch (\Exception $e) {
            return $rs->withStatus(401)->write('Invalid token');
        }
    }
}