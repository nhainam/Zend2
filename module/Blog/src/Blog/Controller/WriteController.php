<?php
// Filename: /module/Blog/src/Blog/Controller/WriteController.php
namespace Blog\Controller;

use Blog\Form\PostForm;
use Blog\Service\PostServiceInterface;
use Zend\Form\FormInterface;
use Zend\Mvc\Controller\AbstractActionController;

class WriteController extends AbstractActionController
{
    /**
     * @var \Blog\Service\PostServiceInterface
     */
    protected $postService;

    /**
     * @var \Zend\Form\FormInterface
     */
    protected $postForm;

    /**
     * WriteController constructor.
     * @param PostServiceInterface $postService
     * @param PostForm $postForm
     */
    public function __construct(
        PostServiceInterface $postService,
        FormInterface $postForm
    ) {
        $this->postService = $postService;
        $this->postForm = $postForm;
    }

    /**
     * @return mixed
     */
    public function addAction()
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            $this->postForm->setData($request->getPost());

            if ($this->postForm->isValid()) {
                try {
//                    \Zend\Debug\Debug::dump($this->postForm->getData());die;
                    $this->postService->savePost($this->postForm->getData());

                    return $this->redirect()->toRoute('blog');
                } catch (\Exception $ex) {
                    // Some DB Error happened, log it and let the user know
                }
            }
        }

        return ['form'  => $this->postForm];
    }
    /**
     * @return mixed
     */
    public function editAction()
    {
        $request = $this->getRequest();
        $post = $this->postService->findPost($this->params('id'));

        $this->postForm->bind($post);

        if ($request->isPost()) {
            $this->postForm->setData($request->getPost());

            if ($this->postForm->isValid()) {
                try {
                    $this->postService->savePost($post);

                    return $this->redirect()->toRoute('blog');
                } catch (\Exception $ex) {
                    die($ex->getMessage());
                }
            }
        }

        return ['form' => $this->postForm];

    }
}