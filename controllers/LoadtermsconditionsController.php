<?php

namespace PHPMaker2022\civichub2;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * loadtermsconditions controller
 */
class LoadtermsconditionsController extends ControllerBase
{

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "Loadtermsconditions");
    }
}
