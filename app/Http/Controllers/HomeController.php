<?php declare(strict_types=1);
namespace App\Http\Controllers;

use Twig\Environment;
use App\Widget\Widget;
use Careminate\Http\Responses\Response;

class HomeController extends Controller
{ 
    public function __construct(private Widget $widget)
    {}

    public function index(): Response
    {
        // dd($this->container->get('twig'));
        // Rather than using dd() which is causing issues, let's render a template
        try {
            // Verify Twig is working by rendering a simple template
            $content = view('home.html.twig', [
                'widgetName' => $this->widget->name,
                'title' => 'Home Page'
            ]);
            
            return new Response("it works");
        } catch (\Exception $e) {
            // If there's an error with Twig, display a simple message
            $content = "<h1>Hello {$this->widget->name} from HomeController</h1>";
            $content .= "<p>Note: Twig error: {$e->getMessage()}</p>";
            
            return new Response($content);
        }
    }

    public function about(): Response
    {
        $content = "<h1>About Page</h1>";
        $content .= "<p>Welcome to the About page of our application.</p>";
        $content .= "<p>Widget name: {$this->widget->name}</p>";
        
        return new Response($content);
    }
}
