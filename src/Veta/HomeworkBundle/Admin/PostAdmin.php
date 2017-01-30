<?php

namespace Veta\HomeworkBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\TranslationBundle\Filter\TranslationFieldFilter;
use Veta\HomeworkBundle\Entity\Post;

class PostAdmin extends AbstractAdmin
{
    protected $searchResultActions = ['edit', 'show'];

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab('Post')
            ->with('Content', ['class'=>'col-lg-8'])
                ->add('title', 'text', [
                    'help' => 'Set the title'
                ])
                ->add('description', 'text', [
                    'help' => 'Set the short text'
                ])
                ->add('text', 'sonata_simple_formatter_type', [
                    'format' => 'richhtml',
                    'ckeditor_context' => 'default',
                ])
            ->end()
            ->with('Status', ['class'=>'col-lg-4'])
                ->add('status', null, ['label' => 'Enabled'])
                ->add('dateCreate', 'sonata_type_datetime_picker', [
                    'dp_side_by_side' => true,
                    'dp_use_current'  => false,
                    'dp_use_seconds'  => false,
                ])
            ->end()
            ->with('Classification', ['class'=>'col-lg-4'])
                    ->add('tags', 'sonata_type_model', [
                        'class' => 'Veta\HomeworkBundle\Entity\Tag',
                        'property' => 'title',
                        'multiple' => true,
                     ])
                    ->add('category', 'sonata_type_model_list', [
                        'btn_add'       => 'Add Category',
                        'btn_list'      => 'List',
                        'btn_delete'    => false,
                    ])
            ->end()
            ->end()
            ->tab('Comments')
                ->with('')
                    ->add('comments', 'sonata_type_collection', [
                        'by_reference' => false,
                        'type_options' => ['delete' => true]
                    ], [
                        'edit' => 'inline',
                        'inline' => 'table',
                        'sortable' => 'position',
                    ])
                ->end()
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
            ->add('tags')
            ->add('category')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title', TranslationFieldFilter::class)
            ->add('dateCreate')
            ->add('category')
            ->add('status')
            ->add('_action', 'actions', [
            'actions' => [
                'show' => [],
                'edit' => [],
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
            ->add('text')
            ->add('dateCreate')
            ->add('tags')
            ->add('category')

        ;
    }

    public function toString($object)
    {
        return $object instanceof Post
            ? $object->getTitle()
            : 'Post'; // shown in the breadcrumb on the create views
    }
}
