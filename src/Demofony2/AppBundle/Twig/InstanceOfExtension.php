<?php
/**
 * Demofony2 for Symfony2
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author: Marc Morales Valldepérez <marcmorales83@gmail.com>
 *
 * Date: 19/03/15
 * Time: 12:35
 */
namespace Demofony2\AppBundle\Twig;

use Demofony2\AppBundle\Entity\Proposal;
use Demofony2\AppBundle\Entity\ProcessParticipation;

class InstanceOfExtension extends \Twig_Extension
{
    public function getTests ()
    {
        return array(
            new \Twig_SimpleTest('processParticipation', function ($event) { return $event instanceof ProcessParticipation; }),
            new \Twig_SimpleTest('proposal', function ($event) {
                          return $event instanceof Proposal; }),
        );
    }


    public function getName()
    {
        return 'app_instanceof_extension';
    }
}
