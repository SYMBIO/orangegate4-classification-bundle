<?php

namespace Symbio\OrangeGate\ClassificationBundle\Form\Type;

use Sonata\ClassificationBundle\Form\Type\CategorySelectorType as BaseCategorySelectorType;
use Sonata\ClassificationBundle\Model\CategoryInterface;
use Symfony\Component\OptionsResolver\Options;

class CategorySelectorType extends BaseCategorySelectorType
{
    /**
     * {@inheritdoc}
     */
    public function getChoices(Options $options)
    {
        if (!$options['category'] instanceof CategoryInterface) {
            return array();
        }

        if ($options['context'] === null) {
            $categories = $this->manager->getRootCategories();
        } else {
            $categories = array($this->manager->getRootCategory($options['context']));
        }

        $choices = array();

        foreach ($categories as $category) {
            $choices[$category->getId()] = sprintf("%s (%s)", $category->getName(), $category->getContext()->getId());

            $this->childWalker($category, $options, $choices);
        }

        return $choices;
    }

    /**
     * @param CategoryInterface $category
     * @param Options           $options
     * @param array             $choices
     * @param int               $level
     */
    private function childWalker(CategoryInterface $category, Options $options, array &$choices, $level = 2)
    {
        if ($category->getChildren() === null) {
            return;
        }

        foreach ($category->getChildren() as $child) {
            if ($options['category'] && $options['category']->getId() == $child->getId()) {
                continue;
            }

            $choices[$child->getId()] = sprintf("%s %s", str_repeat('-' , 1 * $level), $child);

            $this->childWalker($child, $options, $choices, $level + 1);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'orangegate_category_selector';
    }

}
