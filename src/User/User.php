<?php

declare(strict_types=1);

namespace App\User;

use App\Auth\Identity;
use App\Blog\Entity\Comment;
use App\Blog\Entity\Post;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\HasMany;
use Cycle\Annotated\Annotation\Relation\HasOne;
use Cycle\Annotated\Annotation\Table;
use Cycle\Annotated\Annotation\Table\Index;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Yiisoft\Security\PasswordHasher;

/**
 * @Entity(repository="App\User\UserRepository")
 * @Table(
 *     indexes={
 *         @Index(columns={"login"}, unique=true),
 *     }
 * )
 */
class User
{
    /**
     * @Column(type="primary")
     */
    private ?int $id = null;

    /**
     * @Column(type="string(48)")
     */
    private string $login;

    /**
     * @Column(type="string")
     */
    private string $passwordHash;

    /**
     * @Column(type="datetime")
     */
    private DateTimeImmutable $created_at;

    /**
     * @Column(type="datetime")
     */
    private DateTimeImmutable $updated_at;

    /**
     * @HasOne(target="App\Auth\Identity")
     *
     * @var \Cycle\ORM\Promise\Reference|Identity
     */
    private $identity;

    /**
     * @HasMany(target="App\Blog\Entity\Post")
     *
     * @var ArrayCollection|Post[]
     */
    private $posts;

    /**
     * @HasMany(target="App\Blog\Entity\Comment")
     *
     * @var ArrayCollection|Comment[]
     */
    private $comments;

    public function __construct(string $login, string $password)
    {
        $this->login = $login;
        $this->created_at = new DateTimeImmutable();
        $this->updated_at = new DateTimeImmutable();
        $this->setPassword($password);
        $this->identity = new Identity();
        $this->posts = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id === null ? null : (string)$this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function validatePassword(string $password): bool
    {
        return (new PasswordHasher())->validate($password, $this->passwordHash);
    }

    public function setPassword(string $password): void
    {
        $this->passwordHash = (new PasswordHasher())->hash($password);
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function getIdentity(): Identity
    {
        return $this->identity;
    }

    /**
     * @return Post[]
     */
    public function getPosts(): array
    {
        return $this->posts->toArray();
    }

    public function addPost(Post $post): void
    {
        $this->posts->add($post);
    }

    /**
     * @return Comment[]
     */
    public function getComments(): array
    {
        return $this->comments->toArray();
    }

    public function addComment(Comment $post): void
    {
        $this->comments->add($post);
    }
}
