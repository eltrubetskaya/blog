<?php

namespace Veta\HomeworkBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as SymfonyConstraints;
use Veta\HomeworkBundle\Entity\Post;

/**
 * Category
 *
 * @ORM\Entity(repositoryClass="Veta\HomeworkBundle\Repository\CategoryRepository")
 * @UniqueEntity("title")
 * @UniqueEntity("slug")
 */
class Category
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="title", type="string", length=255, unique=true)
     *
     * @SymfonyConstraints\NotBlank()
     * @SymfonyConstraints\Length(
     *     min="3"
     * )
     * @SymfonyConstraints\Type(
     *     type="string"
     * )
     */
    private $title;

    /**
     * @var boolean
     * @ORM\Column(name="status", type="boolean")
     *
     * @SymfonyConstraints\Type(
     *     type="boolean"
     * )
     */
    private $status;

    /**
     * @var string
     * @ORM\Column(name="slug", type="string", length=255, unique=true, nullable=true)
     *
     * @Gedmo\Slug(fields={"title"})
     */
    private $slug;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Post", mappedBy="category", cascade={"persist","merge"})
     */
    private $posts;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Category
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Category
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add post
     *
     * @param Post $post
     *
     * @return Category
     */
    public function addPost(Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param Post $post
     */
    public function removePost(Post $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }
}