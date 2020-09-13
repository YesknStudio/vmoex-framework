<?php

namespace Yeskn\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Yeskn\MainBundle\Repository\UserRepository")
 */
class User implements UserInterface
{
    const NAME = '用户';

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
     * @ORM\Column(name="username", type="string", length=20, unique=true)
     * @Assert\Regex("/^\w+$/")
     * @Assert\Length(min="3", max="20")
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="nickname", type="string", length=24, unique=true)
     */
    private $nickname;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=128)
     * @Assert\Length(min="6", max="18")
     */
    private $password = '';

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=30, unique=true, nullable=true)
     */
    private $email;

    /**
     * @var boolean
     * @ORM\Column(name="is_email_verified", type="boolean", options={"default":false})
     */
    private $isEmailVerified = false;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @var string
     *
     * @ORM\Column(name="remark", type="string", length=255)
     */
    private $remark = '';

    /**
     * @var integer
     * @ORM\Column(name="gold", type="integer", options={"default":100})
     */
    private $gold = 100;

    /**
     * @var integer
     * @ORM\Column(name="sign_day", type="integer", options={"default": 0})
     */
    private $signDay = 0;

    /**
     * @ORM\Column(name="active_val", type="integer", options={"default":0})
     */
    private $activeVal = 0;

    /**
     * @ORM\Column(name="role", type="string")
     */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string")
     */
    private $salt = '';

    /**
     * @var \DateTime
     * @ORM\Column(name="changed_nickname_at", type="datetime", nullable=true)
     */
    private $changedNicknameAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="register_at", type="datetime")
     */
    private $registerAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="login_at", type="datetime")
     */
    private $loginAt;

    /**
     * @ORM\OneToMany(targetEntity="Yeskn\MainBundle\Entity\Post", mappedBy="author")
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="Yeskn\MainBundle\Entity\Comment", mappedBy="user")
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="Yeskn\MainBundle\Entity\User", mappedBy="following")
     */
    private $followers;

    /**
     * @var
     * @ORM\ManyToMany(targetEntity="Yeskn\MainBundle\Entity\User", inversedBy="followers")
     * @ORM\JoinTable(name="followers",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="following_user_id", referencedColumnName="id")}
     * )
     */
    private $following;

    /**
     * @ORM\ManyToMany(targetEntity="Yeskn\MainBundle\Entity\Comment", mappedBy="thumbUpUsers")
     */
    private $thumbUpComments;

    /**
     * @ORM\OneToMany(targetEntity="Yeskn\MainBundle\Entity\Active", mappedBy="user")
     */
    private $actives;

    /**
     * @ORM\OneToMany(targetEntity="Yeskn\MainBundle\Entity\Chat", mappedBy="user")
     */
    private $chats;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->following = new ArrayCollection();
        $this->actives = new ArrayCollection();
        $this->chats = new ArrayCollection();
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
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set nickname
     *
     * @param string $nickname
     *
     * @return User
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set registerAt
     *
     * @param \DateTime $registerAt
     *
     * @return User
     */
    public function setRegisterAt($registerAt)
    {
        $this->registerAt = $registerAt;

        return $this;
    }

    /**
     * Get registerAt
     *
     * @return \DateTime
     */
    public function getRegisterAt()
    {
        return $this->registerAt;
    }

    /**
     * Set loginAt
     *
     * @param \DateTime $loginAt
     *
     * @return User
     */
    public function setLoginAt($loginAt)
    {
        $this->loginAt = $loginAt;

        return $this;
    }

    /**
     * Get loginAt
     *
     * @return \DateTime
     */
    public function getLoginAt()
    {
        return $this->loginAt;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    public function getRoles()
    {
        return (array) $this->getRole();
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    public function eraseCredentials()
    {
        //unset($this->password);
        //unset($this->salt);
    }

    /**
     * @return string
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * @param string $remark
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;
    }

    /**
     * Follow another User
     *
     * @param User $user
     * @return void
     */
    public function follow(User $user)
    {
        $this->following[] = $user;

        $user->followedBy($this);
    }

    /**
     * Set followed by User
     *
     * @param User $user
     * @return void
     */
    private function followedBy(User $user)
    {
        $this->followers[] = $user;
    }

    /**
     * Return the Users this User is following
     *
     * @return ArrayCollection
     */
    public function following()
    {
        return $this->following;
    }

    /**
     * Return the User’s followers
     *
     * @return ArrayCollection
     */
    public function followers()
    {
        return $this->followers;
    }

    /**
     * Unfollow a User
     *
     * @param User $user
     * @return void
     */
    public function unfollow(User $user)
    {
        $this->following->removeElement($user);

        $user->unfollowedBy($this);
    }

    /**
     * Set unfollowed by a User
     *
     * @param User $user
     * @return void
     */
    private function unfollowedBy(User $user)
    {
        $this->followers->removeElement($user);
    }

    /**
     * Add comment
     *
     * @param \Yeskn\MainBundle\Entity\Comment $comment
     *
     * @return User
     */
    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \Yeskn\MainBundle\Entity\Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Add follower
     *
     * @param \Yeskn\MainBundle\Entity\User $follower
     *
     * @return User
     */
    public function addFollower(User $follower)
    {
        $this->followers[] = $follower;

        return $this;
    }

    /**
     * Remove follower
     *
     * @param \Yeskn\MainBundle\Entity\User $follower
     */
    public function removeFollower(User $follower)
    {
        $this->followers->removeElement($follower);
    }

    /**
     * Get followers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollowers()
    {
        return $this->followers;
    }

    /**
     * Add following
     *
     * @param \Yeskn\MainBundle\Entity\User $following
     *
     * @return User
     */
    public function addFollowing(User $following)
    {
        $this->following[] = $following;

        return $this;
    }

    /**
     * Remove following
     *
     * @param \Yeskn\MainBundle\Entity\User $following
     */
    public function removeFollowing(User $following)
    {
        $this->following->removeElement($following);
    }

    /**
     * Get following
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollowing()
    {
        return $this->following;
    }

    /**
     * Add post
     *
     * @param \Yeskn\MainBundle\Entity\Post $post
     *
     * @return User
     */
    public function addPost(Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \Yeskn\MainBundle\Entity\Post $post
     */
    public function removePost(Post $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * @return int
     */
    public function getGold()
    {
        return $this->gold;
    }

    /**
     * @param int $gold
     */
    public function setGold($gold)
    {
        $this->gold = $gold;
    }

    /**
     * @return int
     */
    public function getSignDay()
    {
        return $this->signDay;
    }

    /**
     * @param int $signDay
     */
    public function setSignDay($signDay)
    {
        $this->signDay = $signDay;
    }

    /**
     * @return mixed
     */
    public function getActiveVal()
    {
        return $this->activeVal;
    }

    /**
     * @param mixed $activeVal
     */
    public function setActiveVal($activeVal)
    {
        $this->activeVal = $activeVal;
    }

    /**
     * Add active
     *
     * @param \Yeskn\MainBundle\Entity\Active $active
     *
     * @return User
     */
    public function addActive(Active $active)
    {
        $this->actives[] = $active;

        return $this;
    }

    /**
     * Remove active
     *
     * @param \Yeskn\MainBundle\Entity\Active $active
     */
    public function removeActive(Active $active)
    {
        $this->actives->removeElement($active);
    }

    /**
     * Get actives
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActives()
    {
        return $this->actives;
    }

    /**
     * @return mixed
     */
    public function getChats()
    {
        return $this->chats;
    }

    /**
     * @param mixed $chats
     */
    public function setChats($chats)
    {
        $this->chats = $chats;
    }

    /**
     * @return mixed
     */
    public function getThumbUpComments()
    {
        return $this->thumbUpComments;
    }

    /**
     * @param mixed $thumbUpComments
     */
    public function setThumbUpComments($thumbUpComments)
    {
        $this->thumbUpComments = $thumbUpComments;
    }

    /**
     * @return bool
     */
    public function isEmailVerified()
    {
        return $this->isEmailVerified;
    }

    /**
     * @param bool $isEmailVerified
     */
    public function setIsEmailVerified($isEmailVerified)
    {
        $this->isEmailVerified = $isEmailVerified;
    }

    /**
     * @return \DateTime
     */
    public function getChangedNicknameAt(): ?\DateTime
    {
        return $this->changedNicknameAt;
    }

    /**
     * @param \DateTime $changedNicknameAt
     */
    public function setChangedNicknameAt(\DateTime $changedNicknameAt): void
    {
        $this->changedNicknameAt = $changedNicknameAt;
    }

    public function getAllowEditNicknameDays()
    {
        if ($this->getChangedNicknameAt()) {
            $dateDiff = $this->getChangedNicknameAt()->diff(new \DateTime());
            if ($dateDiff && $dateDiff->invert === 0 && $dateDiff->days < 180) {
                return 180 - $dateDiff->days;
            }
        }

        return 0;
    }
}
