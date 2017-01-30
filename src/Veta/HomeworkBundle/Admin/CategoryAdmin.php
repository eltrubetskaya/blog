<?php

namespace Veta\HomeworkBundle\Admin;

use RedCode\TreeBundle\Admin\AbstractTreeAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\TranslationBundle\Filter\TranslationFieldFilter;
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
            ->add('title', 'text')
            ->add('status')
            ->add('parent', 'entity', [
                'class' => 'Veta\HomeworkBundle\Entity\Category',
                'label' => 'Parent',
                'required' => true,
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
            ->add('title', null, ['_sort_by' => 'title'], null)
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
            ->addIdentifier('title', TranslationFieldFilter::class)
            ->add('status')
            ->add('parent', 'entity', [
                'class' => 'Veta\HomeworkBundle\Entity\Category',
                'label' => 'Parent',
            ])
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
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('title')
            ->add('status')
            ->add('parent')

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
