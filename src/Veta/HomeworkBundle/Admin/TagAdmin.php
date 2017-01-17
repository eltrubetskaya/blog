<?php

namespace Veta\HomeworkBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\TranslationBundle\Filter\TranslationFieldFilter;
use Veta\HomeworkBundle\Entity\Tag;

class TagAdmin extends AbstractAdmin
{
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Content')
            ->add('title', 'text')
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title', TranslationFieldFilter::class)
        ;
    }

    public function toString($object)
    {
        return $object instanceof Tag
            ? $object->getTitle()
            : 'Tag'; // shown in the breadcrumb on the create views
    }
}
