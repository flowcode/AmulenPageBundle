<?php

namespace Flowcode\PageBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Flowcode\PageBundle\Entity\Page;
use Flowcode\PageBundle\Entity\MenuItem;

/**
 * MenuItem
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="page_menu_item")
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 */
class MenuItem
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ManyToOne(targetEntity="Menu", inversedBy="items")
     * @JoinColumn(name="menu_id", referencedColumnName="id")
     */
    private $menu;

    /**
     *@var Page
     *
     * @ManyToOne(targetEntity="Page")
     * @JoinColumn(name="page_id", referencedColumnName="id")
     */
    private $page;

    /**
     *@var string
     *
     * @ORM\Column(name="page_slug", type="string", length=255, nullable=true)
     */
    private $pageSlug;

    /**
     * @var integer
     *
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private $lft;

    /**
     * @var integer
     *
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt;

    /**
     * @var integer
     *
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    private $root;

    /**
     * @ORM\Column(name="is_root", type="boolean")
     */
    private $isRoot;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="MenuItem", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="MenuItem", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->isRoot = false;
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return MenuItem
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get isRoot
     *
     * @return boolean
     */
    public function getIsRoot()
    {
        return $this->isRoot;
    }

    /**
     * Set isRoot.
     *
     * @param boolean $isRoot
     * @return MenuItem
     */
    public function setIsRoot($isRoot)
    {
        $this->isRoot = $isRoot;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    public function __toString()
    {
        return $this->name;
    }

    /**
     * Set lft
     *
     * @param integer $lft
     * @return Category
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Get lft
     *
     * @return integer
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     * @return Category
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set lvl
     *
     * @param integer $lvl
     * @return Category
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * Get lvl
     *
     * @return integer
     */
    public function getLvl()
    {
        return $this->lvl;
    }



    /**
     * Set root
     *
     * @param integer $root
     * @return Category
     */
    public function setRoot($root)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Get root
     *
     * @return integer
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Set parent
     *
     * @param \Flowcode\PageBundle\Entity\MenuItem $parent
     * @return Category
     */
    public function setParent(\Flowcode\PageBundle\Entity\MenuItem $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Flowcode\PageBundle\Entity\MenuItem
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     *
     * @param \Flowcode\PageBundle\Entity\MenuItem $children
     * @return Category
     */
    public function addChild(\Flowcode\PageBundle\Entity\MenuItem $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Flowcode\PageBundle\Entity\MenuItem $children
     */
    public function removeChild(\Flowcode\PageBundle\Entity\MenuItem $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set page
     *
     * @param \Flowcode\PageBundle\Entity\Menu $menu
     * @return Block
     */
    public function setMenu(\Flowcode\PageBundle\Entity\Menu $menu = null) {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get page
     *
     * @return \Flowcode\PageBundle\Entity\Menu
     */
    public function getMenu() {
        return $this->menu;
    }

    /**
     * Set page
     *
     * @param \Flowcode\PageBundle\Entity\Page $page
     * @return MenuItem
     */
    public function setPage(\Flowcode\PageBundle\Entity\Page $page = null) {
        $this->page = $page;

        return $this;
    }

    /**
     * Set pageSlug
     *
     * @param string $pageSlug
     * @return string
     */
    public function setPageSlug($pageSlug)
    {
        $this->pageSlug = $pageSlug;

        return $this;
    }


    public function getPageSlug(){
        return $this->pageSlug;
    }

    /**
     * Get page
     *
     * @return \Flowcode\PageBundle\Entity\Page
     */
    public function getPage() {
        return $this->page;
    }

}
