<?php

namespace Btn\PageBundle\Form\DataTransformer;

use Symfony\Component\Form\Exception\TransformationFailedException;

class IdToPageQuietTransformer extends IdToPageTransformer
{
    /**
     *
     */
    public function transform($id)
    {
        try {
            return parent::transform($id);
        } catch(TransformationFailedException $exception) {
            return null;
        }
    }

    /**
     *
     */
    public function reverseTransform($page)
    {
        try {
            return parent::reverseTransform($page);
        } catch(TransformationFailedException $exception) {
            return null;
        }
    }
}
