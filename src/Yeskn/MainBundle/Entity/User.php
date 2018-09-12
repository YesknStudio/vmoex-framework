<?php

namespace Yeskn\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Yeskn\WpcraftBundle\Repository\UserRepository")
 */
class User implements UserInterface
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
     * @ORM\Column(name="username", type="string", length=20, unique=true)
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
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=30, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="site", type="string", length=100, nullable=true)
     */
    private $site;

    /**
     * @var string
     *
     * @ORM\Column(name="signature", type="string", length=200, nullable=true)
     */
    private $signature;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registerAt", type="datetime")
     */
    private $registerAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="loginAt", type="datetime")
     */
    private $loginAt;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @var string
     *
     * @ORM\Column(name="remark", type="string", length=255, nullable=true)
     */
    private $remark;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=10)
     */
    private $type;

    /**
     * @ORM\Column(type="string", unique=true ,length=128)
     */
    private $apiKey;

    /**
     * @ORM\Column(name="active_val", type="integer", options={"default":0})
     */
    private $activeVal = 0;

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
     * @ORM\OneToMany(targetEntity="Yeskn\BlogBundle\Entity\Post", mappedBy="author")
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="Yeskn\BlogBundle\Entity\Comment", mappedBy="user")
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="Yeskn\BlogBundle\Entity\Comment", mappedBy="thumbUpUsers")
     */
    private $thumbUpComments;

    /**
     * @ORM\OneToMany(targetEntity="Yeskn\BlogBundle\Entity\Chat", mappedBy="user")
     */
    private $chats;

    /**
     * @ORM\OneToMany(targetEntity="Yeskn\BlogBundle\Entity\Active", mappedBy="user")
     */
    private $actives;

    /**
     * @ORM\ManyToMany(targetEntity="Yeskn\BlogBundle\Entity\User", mappedBy="following")
     */
    private $followers;

    /**
     * @var
     * @ORM\ManyToMany(targetEntity="Yeskn\BlogBundle\Entity\User", inversedBy="followers")
     * @ORM\JoinTable(name="followers",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="following_user_id", referencedColumnName="id")}
     * )
     */
    private $following;

    /**
     * @ORM\OneToMany(targetEntity="Yeskn\BlogBundle\Entity\Message", mappedBy="sender")
     */
    private $sentMessages;

    /**
     * @ORM\OneToMany(targetEntity="Yeskn\BlogBundle\Entity\Message", mappedBy="receiver")
     */
    private $receivedMessages;


    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->chats = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->following = new ArrayCollection();
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
     * Set site
     *
     * @param string $site
     *
     * @return User
     */
    public function setSite($site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return string
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set signature
     *
     * @param string $signature
     *
     * @return User
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;

        return $this;
    }

    /**
     * Get signature
     *
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
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
        if (empty($this->avatar)) {
//            $identicon = new \Identicon\Identicon();
//            $this->avatar = $identicon->getImageDataUri($this->username);
            $this->avatar = 'avatar@1x.png';
        }
        return $this->avatar;
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
     * Set type
     *
     * @param string $type
     *
     * @return User
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getRoles()
    {
        $type = $this->getType();
        switch($type){
            case 'admin' :
                return ['ROLE_ADMIN'];
            case 'user':
                return ['ROLE_USER'];
            default:
                return ['none'];
        }

    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Set apiKey
     *
     * @param string $apiKey
     *
     * @return User
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get apiKey
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Add post
     *
     * @param \Yeskn\BlogBundle\Entity\Post $post
     *
     * @return User
     */
    public function addPost(\Yeskn\BlogBundle\Entity\Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \Yeskn\BlogBundle\Entity\Post $post
     */
    public function removePost(\Yeskn\BlogBundle\Entity\Post $post)
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
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChats()
    {
        return $this->chats;
    }

    /**
     * @param Chat $chat
     * @return User
     */
    public function addChat(Chat $chat)
    {
        $this->chats[] = $chat;

        return $this;
    }

    /**
     * @param Chat $chat
     */
    public function removeChat(Chat $chat)
    {
        $this->chats->removeElement($chat);
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
     * @param \Yeskn\BlogBundle\Entity\Comment $comment
     *
     * @return User
     */
    public function addComment(\Yeskn\BlogBundle\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \Yeskn\BlogBundle\Entity\Comment $comment
     */
    public function removeComment(\Yeskn\BlogBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Add follower
     *
     * @param \Yeskn\BlogBundle\Entity\User $follower
     *
     * @return User
     */
    public function addFollower(\Yeskn\BlogBundle\Entity\User $follower)
    {
        $this->followers[] = $follower;

        return $this;
    }

    /**
     * Remove follower
     *
     * @param \Yeskn\BlogBundle\Entity\User $follower
     */
    public function removeFollower(\Yeskn\BlogBundle\Entity\User $follower)
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
     * @param \Yeskn\BlogBundle\Entity\User $following
     *
     * @return User
     */
    public function addFollowing(\Yeskn\BlogBundle\Entity\User $following)
    {
        $this->following[] = $following;

        return $this;
    }

    /**
     * Remove following
     *
     * @param \Yeskn\BlogBundle\Entity\User $following
     */
    public function removeFollowing(\Yeskn\BlogBundle\Entity\User $following)
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
     * Add sentMessage
     *
     * @param \Yeskn\BlogBundle\Entity\Message $sentMessage
     *
     * @return User
     */
    public function addSentMessage(\Yeskn\BlogBundle\Entity\Message $sentMessage)
    {
        $this->sentMessages[] = $sentMessage;

        return $this;
    }

    /**
     * Remove sentMessage
     *
     * @param \Yeskn\BlogBundle\Entity\Message $sentMessage
     */
    public function removeSentMessage(\Yeskn\BlogBundle\Entity\Message $sentMessage)
    {
        $this->sentMessages->removeElement($sentMessage);
    }

    /**
     * Get sentMessages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSentMessages()
    {
        return $this->sentMessages;
    }

    /**
     * Add receivedMessage
     *
     * @param \Yeskn\BlogBundle\Entity\Message $receivedMessage
     *
     * @return User
     */
    public function addReceivedMessage(\Yeskn\BlogBundle\Entity\Message $receivedMessage)
    {
        $this->receivedMessages[] = $receivedMessage;

        return $this;
    }

    /**
     * Remove receivedMessage
     *
     * @param \Yeskn\BlogBundle\Entity\Message $receivedMessage
     */
    public function removeReceivedMessage(\Yeskn\BlogBundle\Entity\Message $receivedMessage)
    {
        $this->receivedMessages->removeElement($receivedMessage);
    }

    /**
     * Get receivedMessages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReceivedMessages()
    {
        return $this->receivedMessages;
    }

    /**
     * Add active
     *
     * @param \Yeskn\BlogBundle\Entity\Active $active
     *
     * @return User
     */
    public function addActive(\Yeskn\BlogBundle\Entity\Active $active)
    {
        $this->actives[] = $active;

        return $this;
    }

    /**
     * Remove active
     *
     * @param \Yeskn\BlogBundle\Entity\Active $active
     */
    public function removeActive(\Yeskn\BlogBundle\Entity\Active $active)
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
}
