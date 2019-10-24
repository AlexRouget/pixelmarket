<?php


namespace App\Entity;


interface ModelInterface
{

    /**
     * Get the model's unique identifier
     *
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * Get the model's creation date
     *
     * This date should be set only once and can't be updated.
     *
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface;

    /**
     * Get the model's last update date
     *
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt(): ?\DateTimeInterface;

    /**
     * Defines the the model's last update date
     *
     * @param \DateTimeInterface $dateTime
     *
     * @return the model instance ($this)
     */
    public function setUpdatedAt(\DateTimeInterface $dateTime);
}