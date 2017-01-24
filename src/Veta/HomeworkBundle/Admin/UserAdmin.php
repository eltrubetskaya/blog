<?php

namespace Veta\HomeworkBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Veta\HomeworkBundle\Entity\User;

class UserAdmin extends AbstractAdmin
{
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $container = $this->getConfigurationPool()->getContainer();
        $roles = $container->getParameter('security.role_hierarchy.roles');

        $rolesChoices = self::flattenRoles($roles);
        $formMapper
            ->with('User')
            ->add('username', null, ['label' => 'form.username', 'translation_domain' => 'FOSUserBundle'])
            ->add('plainPassword', 'repeated', [
                'type' => 'password',
                'options' => ['translation_domain' => 'FOSUserBundle'],
                'first_options' => ['label' => 'form.password'],
                'second_options' => ['label' => 'form.password_confirmation'],
                'invalid_message' => 'fos_user.password.mismatch',
            ])
            ->add('email', 'email', ['label' => 'form.email', 'translation_domain' => 'FOSUserBundle'])
            ->add('enabled')
            ->add('roles', 'choice', [
                    'choices'  => $rolesChoices,
                    'multiple' => true
                ]
            )
            ->end()
        ;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('username', null, ['_sort_by' => 'username'], null);
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('username')
            ->add('email')
            ->add('enabled')
            ->add('roles')
        ;
    }

    /**
     * @param mixed $object
     * @return string
     */
    public function toString($object)
    {
        return $object instanceof User
            ? $object->getUsername()
            : 'User'; // shown in the breadcrumb on the create views
    }

    /**
     * Turns the role's array keys into string <ROLES_NAME> keys.
     *
     * @param $rolesHierarchy
     * @return array
     */
    protected static function flattenRoles($rolesHierarchy)
    {
        $flatRoles = array();
        foreach ($rolesHierarchy as $roles) {
            if (empty($roles)) {
                continue;
            }

            foreach ($roles as $role) {
                if (!isset($flatRoles[$role])) {
                    $flatRoles[$role] = $role;
                }
            }
        }

        return $flatRoles;
    }
}
