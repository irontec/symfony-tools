<?php

namespace SymfonyTools\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks()
 */
trait UpdateTrait
{

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updated;

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated($updated): self
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersistUpdated()
    {
        $this->setUpdated(new \DateTime('now', new \DateTimeZone('UTC')));
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdateUpdated()
    {
        $this->setUpdated(new \DateTime('now', new \DateTimeZone('UTC')));
    }

}
