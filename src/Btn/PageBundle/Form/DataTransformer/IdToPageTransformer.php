<?php

namespace Btn\PageBundle\Form\DataTransformer;

class IdToPageTransformer extends PageToIdTransformer
{
    /**
     *
     */
    public function transform($id)
    {
        return parent::reverseTransform($id);
    }

    /**
     *
     */
    public function reverseTransform($page)
    {
        return parent::transform($page);
    }
}
