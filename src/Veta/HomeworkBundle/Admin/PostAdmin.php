<?php

namespace Veta\HomeworkBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\TranslationBundle\Filter\TranslationFieldFilter;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Veta\HomeworkBundle\Entity\Post;
use Veta\HomeworkBundle\VetaHomeworkBundle;

class PostAdmin extends AbstractAdmin
{
    protected $searchResultActions = ['edit', 'show'];

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        // get the current Image instance
        $image = $this->getSubject();
        $_FILES['getImage'] = $image->getImage();
        // use $fileFieldOptions so we can add other options to the field
        $fileFieldOptions = [
            'required' => false,
            'data_class' => null,
        ];
        if ($image && ($webPath = Post::SERVER_PATH_TO_IMAGE_FOLDER)) {
            // get the container so the full path to the image can be set
            $container = $this->getConfigurationPool()->getContainer();
            $fullPath = $container->get('request_stack')->getCurrentRequest()->getBasePath().'/'.$webPath;

            // add a 'help' option containing the preview's img tag
            $fileFieldOptions['help'] = '<img src="'.$fullPath.'/'.$image->getImage().'" style="width: 200px" class="admin-preview" />';
        }

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
                ->add('image', FileType::class, $fileFieldOptions)
            ->end()
            ->with('Status', ['class'=>'col-lg-4'])
                ->add('enabled', null, ['label' => 'Enabled'])
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
            ->add('enabled')
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
            ->add('enabled')
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
