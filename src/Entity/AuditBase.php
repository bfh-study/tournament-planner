<?php
/**
 * Created by IntelliJ IDEA.
 * User: samuel
 * Date: 30.12.17
 * Time: 17:10
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class AuditBase
{

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="creator", referencedColumnName="email")
     */
    protected $creator;

}