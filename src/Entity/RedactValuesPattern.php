<?php
namespace RedactValues\Entity;

use DateTime;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Omeka\Entity\AbstractEntity;
use Omeka\Entity\User;

/**
 * @Entity
 * @HasLifecycleCallbacks
 */
class RedactValuesPattern extends AbstractEntity
{
    /**
     * @Id
     * @Column(
     *     type="integer",
     *     options={
     *         "unsigned"=true
     *     }
     * )
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @ManyToOne(
     *     targetEntity="Omeka\Entity\User"
     * )
     * @JoinColumn(
     *     nullable=true,
     *     onDelete="SET NULL"
     * )
     */
    protected $owner;

    public function setOwner(?User $owner = null) : void
    {
        $this->owner = $owner;
    }

    public function getOwner() : ?User
    {
        return $this->owner;
    }

    /**
     * @Column(
     *     type="string",
     *     nullable=false
     * )
     */
    protected $label;

    public function setLabel(string $label) : void
    {
        $this->label = $label;
    }

    public function getLabel() : string
    {
        return $this->label;
    }

    /**
     * @Column(
     *     type="text",
     *     nullable=false
     * )
     */
    protected $pattern;

    public function setPattern(string $pattern) : void
    {
        $this->pattern = $pattern;
    }

    public function getPattern() : ?string
    {
        return $this->pattern;
    }

    /**
     * @Column(
     *     type="datetime",
     *     nullable=false
     * )
     */
    protected $created;

    public function setCreated(DateTime $created) : void
    {
        $this->created = $created;
    }

    public function getCreated() : DateTime
    {
        return $this->created;
    }

    /**
     * @Column(
     *     type="datetime",
     *     nullable=true
     * )
     */
    protected $modified;

    public function setModified(?DateTime $modified) : void
    {
        $this->modified = $modified;
    }

    public function getModified() : ?DateTime
    {
        return $this->modified;
    }

    /**
     * @PrePersist
     */
    public function prePersist(LifecycleEventArgs $eventArgs) : void
    {
        $this->setCreated(new DateTime('now'));
    }
}
