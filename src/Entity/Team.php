<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="teams")
 * @ORM\Entity()
 */
class Team
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=60)
     */
    private $name;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Tournament", inversedBy="teams")
     */
    private $tournament;

    /**
     * @var boolean
     */
    private $isDummy;

    public function __construct($isDummy = false) {
        $this->isDummy = $isDummy;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return Tournament
     */
    public function getTournament() {
        return $this->tournament;
    }

    /**
     * @param Tournament $tournament
     */
    public function setTournament($tournament) {
        $this->tournament = $tournament;
    }

    /**
     * @return bool
     */
    public function isDummy() {
        return $this->isDummy;
    }
}
