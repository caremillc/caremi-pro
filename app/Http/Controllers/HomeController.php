<?php  declare(strict_types=1);
namespace App\Http\Controllers;

use Twig\Environment;
use App\Widget\Widget;
use Careminate\Http\Responses\Response;

class HomeController
{
    public function __construct(private Widget $widget, private Environment $twig)
    {
    }

    public function index(): Response
    {
        dd($this->twig);

        $content = "<h1>Hello {$this->widget->name}</h1>";

        return new Response($content);
    }
}