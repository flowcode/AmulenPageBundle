<?php

namespace Flowcode\PageBundle\Controller;

use Amulen\PageBundle\Entity\MenuItem;
use Amulen\PageBundle\Entity\Menu;
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
 */
class MenuController extends Controller
{
    /**
     * Show menu
     *
     * @Route("/{id}/items", name="menu_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Menu $menu)
    {
        $em = $this->getDoctrine()->getManager();

        $itemRoot = $em->getRepository('AmulenPageBundle:MenuItem')->findOneBy(array('menu' => $menu, 'isRoot'=> true));
        $menuItems = $em->getRepository('AmulenPageBundle:MenuItem')->childrenHierarchy($itemRoot);

        $this->updateLinks($menuItems);

        return array(
            'extra_class' => 'nav navbar-nav navbar-right',
            'menuitems' => $menuItems,
        );
    }

    private function updateLinks(&$items){
        $em = $this->getDoctrine()->getManager();
        foreach ($items as &$itemArr) {
            $menuItem = $em->getRepository('AmulenPageBundle:MenuItem')->find($itemArr['id']);
            if($menuItem->getPage()){
                $page = $menuItem->getPage();
                $itemArr['pageSlug'] = $page->getSlug();
            }
            $this->updateLinks($itemArr['__children']);
        }
    }
}
