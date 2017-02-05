<?php

namespace Veta\HomeworkBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslatable;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as SymfonyConstraints;
use Veta\HomeworkBundle\Entity\Comment;
use Veta\HomeworkBundle\Entity\Category;
use Veta\HomeworkBundle\Entity\Tag;
use Veta\HomeworkBundle\Entity\Translation\PostTranslation;

/**
 * @ORM\Entity(repositoryClass="Veta\HomeworkBundle\Repository\PostRepository")
 * @Gedmo\TranslationEntity(class="Veta\HomeworkBundle\Entity\Translation\PostTranslation")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity("title")
 * @UniqueEntity("slug")
 */
class Post extends AbstractPersonalTranslatable implements TranslatableInterface
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
     * @Gedmo\Translatable
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
     * @Gedmo\Translatable
     * @var string
     * @ORM\Column(name="description", type="text", length=65535)
     *
     * @SymfonyConstraints\NotBlank()
     * @SymfonyConstraints\Length(
     *     min="3"
     * )
     * @SymfonyConstraints\Type(
     *     type="string"
     * )
     */
    private $description;

    /**
     * @Gedmo\Translatable
     * @var string
     * @ORM\Column(name="text", type="text", length=65535)
     *
     * @SymfonyConstraints\NotBlank()
     * @SymfonyConstraints\Length(
     *     min="3"
     * )
     * @SymfonyConstraints\Type(
     *     type="string"
     * )
     */
    private $text;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Timestampable(on="create")
     *
     * @SymfonyConstraints\DateTime()
     */
    private $dateCreate;

    /**
     * @var boolean
     * @ORM\Column(name="enabled", type="boolean")
     *
     * @SymfonyConstraints\Type(
     *     type="boolean"
     * )
     */
    private $enabled;

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
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="post", cascade={"persist","merge"})
     */
    private $comments;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="posts", cascade={"persist","merge"})
     */
    private $category;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="posts", cascade={"persist","detach","merge"})
     */
    private $tags;

    /**
     * @var integer
     * @ORM\Column(name="likes", type="integer", nullable=true)
     *
     */
    private $likes;

    /**
     * @Gedmo\Locale
     */
    protected $locale;

    /**
     * @ORM\OneToMany(
     *   targetEntity="Veta\HomeworkBundle\Entity\Translation\PostTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    protected $translations;

    const SERVER_PATH_TO_IMAGE_FOLDER = 'uploads/posts';

    /**
     * @var string $image
     * @SymfonyConstraints\File( maxSize = "1024k", mimeTypesMessage = "Please upload a valid Image")
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * Post constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->dateCreate = new \DateTime;
    }


    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $title
     *
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $description
     *
     * @return Post
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $text
     *
     * @return Post
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param \DateTime $dateCreate
     *
     * @return Post
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * @param boolean $enabled
     *
     * @return Post
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
        ;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param Comment $comment
     *
     * @return Post
     */
    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * @param Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * @return Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param Category $category
     *
     * @return Post
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Tag $tag
     *
     * @return Post
     */
    public function addTag(Tag $tag = null)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * @param Tag $tag
     */
    public function removeTag(Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * @return Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param integer $likes
     *
     * @return Post
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;

        return $this;
    }

    /**
     * @return integer
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @param $locale
     */
    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @param $image
     */
    public function setImage($image = null)
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }

    public function getFullImagePath()
    {
        return null === $this->image ? null : $this->getUploadRootDir(). $this->image;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded documents should be saved
        return self::SERVER_PATH_TO_IMAGE_FOLDER."/";
    }

    protected function getTmpUploadRootDir()
    {
        // the absolute directory path where uploaded documents should be saved
        return self::SERVER_PATH_TO_IMAGE_FOLDER."/tmp/";
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function uploadImage()
    {
        // the file property can be empty if the field is not required
        if ((null === $this->image)) {
            if (!$this->id) {
                return;
            }
            $this->setImage($_FILES['getImage']);
        } else {
            $fileName = uniqid().$this->image->getClientOriginalName();
            if (!$this->id) {
                $this->image->move($this->getTmpUploadRootDir(), $fileName);
            } else {
                $this->image->move($this->getUploadRootDir(), $fileName);
            }
            $this->setImage($fileName);
        }
    }

    /**
     * Lifecycle callback to upload the file to the server
     */
    public function lifecycleFileUpload()
    {
        $this->uploadImage();
    }
    /**
     * @ORM\PostPersist()
     */
    public function moveImage()
    {
        if (null === $this->image) {
            return;
        }
        if (!is_dir($this->getUploadRootDir())) {
            mkdir($this->getUploadRootDir());
        }
        copy($this->getTmpUploadRootDir().$this->image, $this->getFullImagePath());
        unlink($this->getTmpUploadRootDir().$this->image);
    }

    /**
     * @ORM\PreRemove()
     */
    public function removeImage()
    {
        unlink($this->getFullImagePath());
        rmdir($this->getUploadRootDir());
    }
}
