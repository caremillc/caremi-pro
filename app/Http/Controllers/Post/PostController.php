<?php declare(strict_types=1);
namespace App\Http\Controllers\Post;

use App\Entity\Post;
use App\Repository\PostMapper;
use App\Repository\PostRepository;
use App\Http\Controllers\Controller;
use Careminate\Support\FileUploader;
use Careminate\Http\Responses\Response;


class PostController extends Controller
{ 
      public function __construct(
        private PostMapper $postMapper,
        private PostRepository $postRepository
    ){}
     
    public function index(): Response
    {
        // Retrieve all posts from the repository
        $posts = $this->postRepository->findAll();

        // Render the view and pass the posts data to it
        return view('posts/index.html.twig', compact('posts'));
    }

    public function create(): Response
    {
        // Your logic here
        return view('posts/create.html.twig');
    }

    public function store(): Response
    {
        $title = $this->request->input('title') ?? null;
        $description = $this->request->input('description') ?? null;
        $imagePath = null;

        if (empty($title) || empty($description)) {
            return new Response("<h1>Error: Title and description are required.</h1>", 400);
        }

        // Use the helper function to handle file upload
        if (isset($_FILES['image'])) {
            $imagePath = FileUploader::store($_FILES['image'], storage_path('app/public/images'));

            if ($imagePath === null) {
                return new Response("<h1>Image upload failed.</h1>", 400);
            }
        }

        // Create the post
        $post = Post::create(null,$title, $description, $imagePath, null);

        $this->postMapper->save($post);
        // Debugging output (remove after testing)

        return Response::redirect("/posts");
    }
    
   public function show(int $id): Response
    {
        $post = $this->postRepository->findById($id);

        return view('posts/show.html.twig', compact('post'));
    }

    public function edit(int $id): Response
    {
        // Your logic here
         $post = $this->postRepository->findById($id);
        return view('posts/edit.html.twig', compact('post'));
    }

    public function update(int $id): Response
    {
        // Your logic here
        return new Response("<h1>Update Post with ID: $id</h1>");
    }

    public function delete(int $id): Response
    {
        // Your logic here
        return new Response("<h1>Delete Post with ID: $id</h1>");
    }
}
