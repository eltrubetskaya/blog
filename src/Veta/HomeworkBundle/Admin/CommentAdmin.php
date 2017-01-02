<?php

namespace Veta\HomeworkBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Veta\HomeworkBundle\Entity\Comment;

class CommentAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Comment', ['class'=>'col-lg-6'])
            ->add('text', 'sonata_simple_formatter_type', [
                'format' => 'richhtml',
                'ckeditor_context' => 'default',
                'label' => 'Message',
            ])

            ->end()
            ->with('General', ['class'=>'col-lg-6'])
            ->add('dateCreate', 'sonata_type_datetime_picker', [
                'dp_side_by_side' => true,
                'dp_use_current'  => false,
                'dp_use_seconds'  => false,
                'label' => 'Date',
            ])
            ->add('post', 'sonata_type_model_list', [
                'btn_add'       => 'Add',
                'btn_list'      => 'List',
                'btn_delete'    => false,
            ])
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('post');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('text')
            ->add('dateCreate')
            ->add('post')
        ;
    }

    public function toString($object)
    {
        return $object instanceof Comment
            ? $object->getText()
            : 'Comment'; // shown in the breadcrumb on the create views
    }
}
