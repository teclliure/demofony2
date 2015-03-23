<?php
/**
 * Demofony2 app
 *
 * @author: Marc Morales Valldepérez <marcmorales83@gmail.com>
 *
 * Date: 23/03/14
 * Time: 12:00
 */
namespace Demofony2\AppBundle\Filter;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;

class PublishedFilter extends  SQLFilter
{
    protected $enabled = array();

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        $class = $targetEntity->getName();
        if (array_key_exists($class, $this->enabled) && $this->enabled[$class] === true) {

            $query = sprintf('%s.%s = %s', $targetTableAlias, 'published', true);

            return $query;
        }

        return '';
    }

    public function disableForEntity($class)
    {
        $this->enabled[$class] = false;
    }

    public function enableForEntity($class)
    {
        $this->enabled[$class] = true;
    }
}
