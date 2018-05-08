<?php

namespace SymfonyTools\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks()
 */
trait CreateTrait
{

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $created;

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created): self
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersistCreated()
    {
        $this->setCreated(new \DateTime('now', new \DateTimeZone('UTC')));
    }

}
