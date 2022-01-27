<?php
namespace RedactValues\Entity;

use DateTime;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Omeka\Entity\AbstractEntity;
use Omeka\Entity\Property;
use Omeka\Entity\User;

/**
 * @Entity
 * @HasLifecycleCallbacks
 */
class RedactValuesRedaction extends AbstractEntity
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
     *     type="string",
     *     nullable=false
     * )
     */
    protected $resourceType;

    public function setResourceType(string $resourceType) : void
    {
        $this->resourceType = $resourceType;
    }

    public function getResourceType() : string
    {
        return $this->resourceType;
    }

    /**
     * @Column(
     *     type="text",
     *     nullable=true
     * )
     */
    protected $query;

    public function setQuery(?string $query) : void
    {
        if ('' === $query) {
            // Set an empty query to null.
            $query = null;
        }
        $this->query = $query;
    }

    public function getQuery() : ?string
    {
        return $this->query;
    }

    /**
     * @ManyToOne(
     *     targetEntity="Omeka\Entity\Property"
     * )
     * @JoinColumn(
     *     nullable=false,
     *     onDelete="CASCADE"
     * )
     */
    protected $property;

    public function setProperty(Property $property) : void
    {
        $this->property = $property;
    }

    public function getProperty() : Property
    {
        return $this->property;
    }

    /**
     * @ManyToOne(
     *     targetEntity="RedactValues\Entity\RedactValuesPattern"
     * )
     * @JoinColumn(
     *     nullable=true,
     *     onDelete="SET NULL"
     * )
     */
    protected $pattern;

    public function setPattern(?RedactValuesPattern $pattern) : void
    {
        $this->pattern = $pattern;
    }

    public function getPattern() : ?RedactValuesPattern
    {
        return $this->pattern;
    }

    /**
     * @Column(
     *     type="string",
     *     nullable=false
     * )
     */
    protected $replacement;

    public function setReplacement(string $replacement) : void
    {
        $this->replacement = $replacement;
    }

    public function getReplacement() : string
    {
        return $this->replacement;
    }

    /**
     * @Column(
     *     type="json",
     *     nullable=false
     * )
     */
    protected $allowRoles;

    public function setAllowRoles(array $allowRoles) : void
    {
        $this->allowRoles = $allowRoles;
    }

    public function getAllowRoles() : array
    {
        return $this->allowRoles;
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
