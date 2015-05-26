<?php

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * An article
 *
 * @ORM\Entity
 * @ORM\Table(name="events")
 * 
 * To generate methods:
 * php index.php orm:generate-entities --update-entities="Application\Entity\Event" module\Application\src
 */
class Event {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    protected $title;

}
