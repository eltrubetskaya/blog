<?php

namespace Veta\HomeworkBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslatable;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as SymfonyConstraints;
use Veta\HomeworkBundle\Entity\Translation\TagTranslation;

/**
 * @ORM\Entity(repositoryClass="Veta\HomeworkBundle\Repository\TagRepository")
 * @Gedmo\TranslationEntity(class="Veta\HomeworkBundle\Entity\Translation\TagTranslation")
 * @UniqueEntity("title")
 * @UniqueEntity("slug")
 */
class Tag extends AbstractPersonalTranslatable implements TranslatableInterface
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
     *
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, unique=true)
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
     * @var string
     * @ORM\Column(name="slug", type="string", length=255, unique=true, nullable=true)
     *
     * @Gedmo\Slug(fields={"title"})
     */
    private $slug;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Veta\HomeworkBundle\Entity\Post", mappedBy="tags", cascade={"persist","detach","merge"})
     */
    private $posts;

    /**
     * @Gedmo\Locale
     */
    protected $locale;

    /**
     * @ORM\OneToMany(
     *   targetEntity="Veta\HomeworkBundle\Entity\Translation\TagTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    protected $translations;

    /**
     * Tag constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->posts = new ArrayCollection();
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
     * @return Tag
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
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param $locale
     */
    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }
}
