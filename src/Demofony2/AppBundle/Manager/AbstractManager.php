<?php
namespace Demofony2\AppBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Exception\ValidatorException;

abstract class AbstractManager
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    protected $validator;

    /**
     * @param ObjectManager      $em
     * @param ValidatorInterface $validator
     */
    public function __construct(ObjectManager $em, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->validator = $validator;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    abstract public function getRepository();

    /**
     * @return \Doctrine\ORM\Mapping\Entity
     */
    abstract public function create();

    /**
     * @param string $id
     *
     * @return \Doctrine\ORM\Mapping\Entity
     */
    public function load($id)
    {
        return $this->getRepository()->find($id);
    }

    /**
     * @param      $entity
     * @param null $groups
     *
     * @return \Symfony\Component\Validator\ConstraintViolationListInterface
     */
    public function validate($entity, $groups = null)
    {
        return $this->validator->validate($entity, null, $groups);
    }

    /**
     * @param \Doctrine\ORM\Mapping\Entity $entity
     * @param bool                         $flush
     *
     * @return \\AppBundle\Manager\AbstractManager
     * @throws \Symfony\Component\Validator\Exception\ValidatorException
     */
    public function persist($entity, $flush = true)
    {
        $violations = $this->validate($entity);
        if ($violations->count() > 0) {
            throw new ValidatorException('Entity is not valid: '.PHP_EOL.(string) $violations);
        }
        $this->em->persist($entity);
        if ($flush) {
            $this->flush();
        }

        return $this;
    }

    /**
     * @param \Doctrine\ORM\Mapping\Entity $entity
     * @param bool                         $flush
     *
     * @return \CliCons\CoreBundle\Manager\AbstractManager
     */
    public function remove($entity, $flush = true)
    {
        $this->em->remove($entity);

        if ($flush) {
            $this->flush();
        }

        return $this;
    }

    /**
     * @param mixed $entity
     *
     * @return $this
     */
    public function flush($entity = null)
    {
        $this->em->flush($entity);

        return $this;
    }
}
