<?php

namespace Btn\PageBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Btn\BaseBundle\Provider\EntityProviderInterface;
use Btn\PageBundle\Model\PageInterface;

class PageToIdTransformer implements DataTransformerInterface
{
    /** @var \Btn\BaseBundle\Provider\EntityProviderInterface $entityProvider  */
    protected $entityProvider;

    /**
     *
     */
    public function __construct(EntityProviderInterface $entityProvider)
    {
        $this->entityProvider = $entityProvider;
    }

    /**
     *
     */
    public function transform($page)
    {
        if (null === $page) {
            return "";
        }

        if ($page instanceof PageInterface) {
            return $page->getId();
        }

        return $page;
    }

    /**
     *
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return;
        }

        if ($id instanceof PageInterface) {
            return $id;
        }

        $page = $this->entityProvider->getRepository()->find($id);

        if (null === $page) {
            throw new TransformationFailedException(sprintf('An page with id "%s" does not exist!', $id));
        }

        return $page;
    }
}
