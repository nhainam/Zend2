<?php
// Filename: /module/Blog/src/Blog/Controller/ListController.php
namespace Blog\Controller;

use Blog\Service\PostServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;

class ListController extends AbstractActionController
{
    /**
     * @var \Blog\Service\PostServiceInterface
     */
    protected $postService;

    public function __construct(PostServiceInterface $postService)
    {
        $this->postService = $postService;
    }

    public function indexAction()
    {
        return ['posts' => $this->postService->findAllPosts()];
    }

    public function detailAction()
    {
        $id = $this->params()->fromRoute('id');

        try {
            $post = $this->postService->findPost($id);
        } catch (\InvalidArgumentException $ex) {
            return $this->redirect()->toRoute('blog');
        }

        return ['post' => $post];
    }
}