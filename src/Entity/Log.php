<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LogRepository")
 */
class Log implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=190)
     */
    private $body;

    /**
     * @ORM\Column(type="smallint", options={"default" : 0})
     */
    private $status = 0;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $api_used;

    /**
     * @ORM\Column(type="smallint", options={"default" : 0})
     */
    private $attempts = 0;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getApiUsed(): ?int
    {
        return $this->api_used;
    }

    public function setApiUsed(?int $api_used): self
    {
        $this->api_used = $api_used;

        return $this;
    }

    public function getAttempts(): ?int
    {
        return $this->attempts;
    }

    public function setAttempts(int $attempts): self
    {
        $this->attempts = $attempts;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            "id"=> $this->getId(),
            "number" => $this->getNumber(),
            "body" => $this->getbody(),
            "status" => $this->getStatus(),
            "api_used" => $this->getApiUsed(),
            "attempts" => $this->getAttempts(),
            "created_at" => $this->getCreatedAt()->format("Y-m-d H:i:s"),
        ];
    }
}
