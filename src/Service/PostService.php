<?php
/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Post service.
 */

namespace App\Service;

use App\Entity\Post;
use App\Interface\PostServiceInterface;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\QueryBuilder;

set_time_limit(0);
/**
 * Class PostService.
 */
class PostService implements PostServiceInterface
{
    /**
     * Post repository.
     */
    private PostRepository $postRepository;

    /**
     * Constructor.
     *
     * @param PostRepository $postRepository post repository
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }


    /**
     * Save entity.
     *
     * @param Post $post Post entity
     */
    public function save($post): void
    {
        $this->postRepository->save($post);
    }

    /**
     * Delete entity.
     *
     * @param Post $post Post entity
     */
    public function delete($post): void
    {
        $this->postRepository->delete($post);
    }

    /**
     * Find All.
     */
    public function findAll(): array
    {
        return $this->postRepository-> queryAll();

    }

    /**
     * Find one.
     */
    public function find($id): Post
    {
        return $this->postRepository->find($id);

    }
}
