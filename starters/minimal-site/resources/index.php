<?php

declare(strict_types=1);

namespace App\Resources;

use MiGears\Template\Template;
use MiGears\Web\AbstractResource;
use MiGears\Web\Request;
use MiGears\Web\Response;
use Psr\Container\ContainerInterface;

final class index extends AbstractResource
{
    private Template $template;

    public function __construct(ContainerInterface $container)
    {
        $this->template = $container->get(Template::class);
    }

    public function GET(Request $request): Response
    {
        return Response::html($this->template->render('home', [
            'title' => 'miGears minimal site',
            'checks' => [
                'Routing: resources/index.php answers GET /',
                'Container: Template was resolved by type',
                'Templates: views/home.php was rendered',
                'JSON: resources/health.php answers GET /health',
            ],
        ]));
    }
}
