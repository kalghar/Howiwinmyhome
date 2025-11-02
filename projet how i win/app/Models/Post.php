<?php

//$post = new Post();
//$post->setTitle('title');
//$post->setContent('content');
//$post->setCreatedAt(new DateTimeImmutable());

//ou pattern FLUENT pour cela les methodes doivent retourner $this référence au model Post
(new Post())
    ->setTitle('title')
    ->setContent('content')
    ->setCreatedAt(new DateTimeImmutable())
    ->create();

//
//$post->create();
//
//$p = $post->getById(12);

class Post extends AbstractModel implements ModelInterface
{
    protected string $table = 'posts';

    private string $title = '';
    private string $content = '';
    private DateTimeImmutable|null $createdAt = null;

    public function create(): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO " . $this->table . " (title, content, created_at) 
            VALUES (?, ?, NOW())
        ");

        $stmt->execute([$this->title, $this->content, $this->createdAt]);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}