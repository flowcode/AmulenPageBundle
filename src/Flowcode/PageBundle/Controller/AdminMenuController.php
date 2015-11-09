<?php

namespace Flowcode\PageBundle\Controller;

use Flowcode\PageBundle\Entity\MenuItem;
use Flowcode\PageBundle\Entity\Menu;
use Flowcode\PageBundle\Form\MenuItemType;
use Flowcode\PageBundle\Form\MenuType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * Menu controller.
 *
 * @Route("/admin/menu")
 */
class AdminMenuController extends Controller
{
    /**
     * Lists all Menu entities.
     *
     * @Route("/", name="admin_menu")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $page = $request->get("page", 1);
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT p FROM FlowcodePageBundle:Menu p";
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $this->get('request')->query->get('page', $page));

        return array(
            'pagination' => $pagination,
        );
    }

    /**
     * Creates a new Menu entity.
     *
     * @Route("/", name="admin_menu_create")
     * @Method("POST")
     * @Template("FlowcodePageBundle:Menu:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Menu();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            /* set root item */
            $rootItem = new MenuItem();
            $rootItem->setName($entity->getName()." Root Item");
            $rootItem->setIsRoot(true);
            $rootItem->setMenu($entity);
            $em->persist($rootItem);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_menu_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Menu entity.
     *
     * @param Menu $entity The entity
     *
     * @return Form The form
     */
    private function createCreateForm(Menu $entity)
    {
        $form = $this->createForm(new MenuType(), $entity, array(
            'action' => $this->generateUrl('admin_menu_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Menu entity.
     *
     * @Route("/new", name="admin_menu_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Menu();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Menu entity.
     *
     * @Route("/{id}", name="admin_menu_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FlowcodePageBundle:Menu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Menu entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Menu entity.
     *
     * @Route("/{id}/edit", name="admin_menu_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FlowcodePageBundle:Menu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Menu entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Menu entity.
     *
     * @param Menu $entity The entity
     *
     * @return Form The form
     */
    private function createEditForm(Menu $entity)
    {
        $form = $this->createForm(new MenuType(), $entity, array(
            'action' => $this->generateUrl('admin_menu_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Menu entity.
     *
     * @Route("/{id}", name="admin_menu_update")
     * @Method("PUT")
     * @Template("FlowcodePageBundle:Menu:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FlowcodePageBundle:Menu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Menu entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_menu_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Menu entity.
     *
     * @Route("/{id}", name="admin_menu_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FlowcodePageBundle:Menu')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Menu entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_menu'));
    }

    /**
     * Creates a form to delete a Menu entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('admin_menu_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

    /**
     * Finds and displays menu items..
     *
     * @Route("/{id}/items", name="admin_menu_items")
     * @Method("GET")
     * @Template()
     */
    public function itemsAction(Menu $menu)
    {
        $em = $this->getDoctrine()->getManager();
        //$entities = $em->getRepository('FlowcodePageBundle:MenuItem')->findBy(array("menu" => $menu->getId()));
        $entities = $em->getRepository('FlowcodePageBundle:MenuItem')->childrenHierarchy(null);

        return array(
            'menu' => $menu,
            'entities' => $entities,
        );
    }

    /**
     * Creates a new MenuItem entity.
     *
     * @Route("/{id}/item", name="admin_menu_item_create")
     * @Method("POST")
     * @Template("FlowcodePageBundle:AdminMenu:new_item.html.twig")
     */
    public function createMenuItemAction(Request $request, Menu $menu)
    {
        $entity = new MenuItem();
        $entity->setMenu($menu);
        $form = $this->createMenuItemCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if($entity->getPage()){
                $entity->setPageSlug($entity->getPage());
            }

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_menu_item_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a MenuItem entity.
     *
     * @param MenuItem $entity The entity
     *
     * @return Form The form
     */
    private function createMenuItemCreateForm(MenuItem $entity)
    {
        $form = $this->createForm(new MenuItemType(), $entity, array(
            'action' => $this->generateUrl('admin_menu_item_create', array('id' => $entity->getMenu()->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new MenuItem entity.
     *
     * @Route("/{id}/item/new", name="admin_menu_item_new")
     * @Method("GET")
     * @Template("FlowcodePageBundle:AdminMenu:new_item.html.twig")
     */
    public function newMenuItemAction(Menu $menu)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = new MenuItem();
        $entity->setMenu($menu);
        $form = $this->createMenuItemCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a MenuItem entity.
     *
     * @Route("/item/{id}", name="admin_menu_item_show")
     * @Method("GET")
     * @Template("FlowcodePageBundle:AdminMenu:show_item.html.twig")
     */
    public function showMenuItemAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FlowcodePageBundle:MenuItem')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MenuItem entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing MenuItem entity.
     *
     * @Route("/item/{id}/edit", name="admin_menu_item_edit")
     * @Method("GET")
     * @Template("FlowcodePageBundle:AdminMenu:edit_item.html.twig")
     */
    public function editMenuItemAction(MenuItem $menuItem)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$menuItem) {
            throw $this->createNotFoundException('Unable to find MenuItem entity.');
        }

        $editForm = $this->createMenuItemEditForm($menuItem);
        $deleteForm = $this->createMenuItemDeleteForm($menuItem);

        return array(
            'entity' => $menuItem,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a MenuItem entity.
     *
     * @param MenuItem $entity The entity
     *
     * @return Form The form
     */
    private function createMenuItemEditForm(MenuItem $entity)
    {
        $form = $this->createForm(new MenuItemType(), $entity, array(
            'action' => $this->generateUrl('admin_menu_item_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing MenuItem entity.
     *
     * @Route("/item/{id}", name="admin_menu_item_update")
     * @Method("PUT")
     * @Template("FlowcodePageBundle:MenuItem:edit.html.twig")
     */
    public function updateMenuItemAction(Request $request, MenuItem $menuItem)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$menuItem) {
            throw $this->createNotFoundException('Unable to find MenuItem entity.');
        }

        $editForm = $this->createMenuItemEditForm($menuItem);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            $em->flush();

            return $this->redirect($this->generateUrl('admin_menu_items', array('id' => $menuItem->getMenu()->getId())));
        }

        return array(
            'entity' => $menuItem,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a MenuItem entity.
     *
     * @Route("/item/{id}", name="admin_menu_item_delete")
     * @Method("DELETE")
     */
    public function deleteMenuItemAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FlowcodePageBundle:MenuItem')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find MenuItem entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('item'));
    }

    /**
     * Creates a form to delete a MenuItem entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Form The form
     */
    private function createMenuItemDeleteForm($id)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('admin_menu_item_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }
}
