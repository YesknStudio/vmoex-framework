<?php

namespace Yeskn\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Yeskn\BlogBundle\Entity\Tag;
use Yeskn\BlogBundle\Entity\Category;
/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Yeskn\BlogBundle\Repository\PostRepository")
 */
class Post
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="excerpt", type="string", length=300, nullable=true)
     */
    private $excerpt;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="Yeskn\BlogBundle\Entity\User",inversedBy="posts")
     * @ORM\JoinColumn(name="authorId" , referencedColumnName="id")
     */
    private $author;

    /**
     * @var
     * @ORM\ManyToMany(targetEntity="Yeskn\BlogBundle\Entity\Tag",inversedBy="posts")
     */
    private $tags;

    /**
     * @var
     * @ORM\ManyToMany(targetEntity="Yeskn\BlogBundle\Entity\Category",inversedBy="posts")
     */
    private $categories;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="Yeskn\BlogBundle\Entity\Comment", mappedBy="post")
     */
    private $comments;

    /**
     * @var
     * @ORM\Column(name="views",type="integer")
     */
    private $views = 9;

    /**
     * @var string
     * @ORM\Column(name="cover",type="string",length=100,nullable=true)
     */
    private $cover = '/upload/cover/sample.jpg';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="isDeleted", type="boolean")
     */
    private $isDeleted;

    /**
     * @ORM\Column(name="isTop",type="boolean")
     */
    private $isTop;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=10)
     */
    private $status;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return int
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
     * @return Post
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
     * Set excerpt
     *
     * @param string $excerpt
     *
     * @return Post
     */
    public function setExcerpt($excerpt)
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    /**
     * Get excerpt
     *
     * @return string
     */
    public function getExcerpt()
    {
        return $this->excerpt;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set author
     *
     * @param integer $author
     *
     * @return Post
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return int
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Post
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Post
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     *
     * @return Post
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return bool
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Post
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add tag
     *
     * @param \Yeskn\BlogBundle\Entity\Tag $tag
     *
     * @return Post
     */
    public function addTag(Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \Yeskn\BlogBundle\Entity\Tag $tag
     */
    public function removeTag(Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Add category
     *
     * @param \Yeskn\BlogBundle\Entity\Category $category
     *
     * @return Post
     */
    public function addCategory(Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \Yeskn\BlogBundle\Entity\Category $category
     */
    public function removeCategory(Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add Comment
     *
     * @param Comment $comment
     * @return $this
     */
    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove Comment
     * @param Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        $this->categories->removeElement($comment);
    }

    /**
     * Get Comments
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set cover
     *
     * @param string $cover
     *
     * @return Post
     */
    public function setCover($cover)
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * Get cover
     *
     * @return string
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * Set views
     *
     * @param integer $views
     *
     * @return Post
     */
    public function setViews($views)
    {
        $this->views = $views;

        return $this;
    }

    /**
     * Get views
     *
     * @return integer
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * @ORM\PostLoad()
     */
    public function increaseView()
    {
        //$this->views++;
    }

    /**
     * Set isTop
     *
     * @param boolean $isTop
     *
     * @return Post
     */
    public function setIsTop($isTop)
    {
        $this->isTop = $isTop;

        return $this;
    }

    /**
     * Get isTop
     *
     * @return boolean
     */
    public function getIsTop()
    {
        return $this->isTop;
    }
}
