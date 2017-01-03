<?php

namespace Veta\HomeworkBundle\Admin;

use RedCode\TreeBundle\Admin\AbstractTreeAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Veta\HomeworkBundle\Entity\Category;

class CategoryAdmin extends AbstractTreeAdmin
{
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $subjectId = $this->getRoot()->getSubject()->getId();

        $formMapper
            ->with('Content')
            ->add('title')
            ->add('status')
            ->add('parent', 'entity', [
                'class' => 'Veta\HomeworkBundle\Entity\Category',
                'label' => 'Parent',
                'required'=>true,
                'query_builder' => function ($er) use ($subjectId) {
                    $qb = $er->createQueryBuilder('c');

                    $qb
                        ->orderBy('c.root, c.lft', 'ASC');
                    return $qb;
                }
            ])
            ->end()
        ;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('status')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->addIdentifier('title')
            ->add('status')
            ->add('parent ')
            ->add('_action', 'actions', [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ]
            ])
        ;
    }

    /**
     * @param mixed $object
     * @return string
     */
    public function toString($object)
    {
        return $object instanceof Category
            ? $object->getTitle()
            : 'Category'; // shown in the breadcrumb on the create views
    }
}
