<?php

namespace PHPMaker2022\civichub2;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * breadcrumblinksmovesp controller
 */
class BreadcrumblinksmovespController extends ControllerBase
{

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "Breadcrumblinksmovesp");
    }
}
