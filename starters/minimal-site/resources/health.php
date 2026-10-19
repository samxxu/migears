<?php

declare(strict_types=1);

namespace App\Resources;

use MiGears\Web\AbstractResource;
use MiGears\Web\Request;
use MiGears\Web\Response;

final class health extends AbstractResource
{
    public function GET(Request $request): Response
    {
        return Response::json([
            'ok' => true,
            'framework' => 'miGears',
        ]);
    }
}
