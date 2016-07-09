<?php

namespace Flowcode\PageBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * MenuItem
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
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @ManyToOne(targetEntity="\Amulen\PageBundle\Entity\Menu", inversedBy="items")
     * @JoinColumn(name="menu_id", referencedColumnName="id")
     */
    protected $menu;

    /**
     * @var \Amulen\PageBundle\Entity\Page
     *
     * @ManyToOne(targetEntity="\Amulen\PageBundle\Entity\Page")
     * @JoinColumn(name="page_id", referencedColumnName="id")
     */
    protected $page;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=255, nullable=true)
     */
    protected $link;

    /**
     *@var string
     *
     * @ORM\Column(name="page_slug", type="string", length=255, nullable=true)
     */
    protected $pageSlug;

    /**
     * @OneToMany(targetEntity="Amulen\PageBundle\Entity\MenuItemLabel", mappedBy="menuItem")
     * */
    protected $labels;

    /**
     * @var integer
     *
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    protected $lft;

    /**
     * @var integer
     *
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    protected $rgt;

    /**
     * @var integer
     *
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    protected $lvl;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    protected $root;

    /**
     * @ORM\Column(name="is_root", type="boolean")
     */
    protected $isRoot;

    /**
     * @var integer
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     */
    protected $position;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="MenuItem", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="MenuItem", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    protected $children;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->isRoot = false;
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->labels = new \Doctrine\Common\Collections\ArrayCollection();
        $this->position = 0;
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
     * @return \Amulen\PageBundle\Entity\MenuItem
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
     * @return \Amulen\PageBundle\Entity\MenuItem
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
     * @return \Amulen\PageBundle\Entity\MenuItem
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
     * @return \Amulen\PageBundle\Entity\MenuItem
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
     * @param \Amulen\PageBundle\Entity\MenuItem $parent
     * @return Category
     */
    public function setParent(\Amulen\PageBundle\Entity\MenuItem $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Amulen\PageBundle\Entity\MenuItem
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     *
     * @param \Amulen\PageBundle\Entity\MenuItem $children
     * @return Category
     */
    public function addChild(\Amulen\PageBundle\Entity\MenuItem $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Amulen\PageBundle\Entity\MenuItem $children
     */
    public function removeChild(\Amulen\PageBundle\Entity\MenuItem $children)
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
     * @param \Amulen\PageBundle\Entity\Menu $menu
     * @return Block
     */
    public function setMenu(\Amulen\PageBundle\Entity\Menu $menu = null) {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get page
     *
     * @return \Amulen\PageBundle\Entity\Menu
     */
    public function getMenu() {
        return $this->menu;
    }

    /**
     * Set page
     *
     * @param \Amulen\PageBundle\Entity\Page $page
     * @return MenuItem
     */
    public function setPage(\Amulen\PageBundle\Entity\Page $page = null) {
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
     * @return \Amulen\PageBundle\Entity\Page
     */
    public function getPage() {
        return $this->page;
    }

    /**
     * Add blocks
     *
     * @param \Amulen\PageBundle\Entity\MenuItemLabel $blocks
     * @return \Amulen\PageBundle\Entity\MenuItem
     */
    public function addMenuItemLabel(\Amulen\PageBundle\Entity\MenuItemLabel $blocks) {
        $this->blocks[] = $blocks;

        return $this;
    }

    /**
     * Remove blocks
     *
     * @param \Amulen\PageBundle\Entity\MenuItemLabel $blocks
     */
    public function removeMenuItemLabel(\Amulen\PageBundle\Entity\MenuItemLabel $blocks) {
        $this->labels->removeElement($blocks);
    }

    /**
     * Get blocks
     *
     * @return Collection
     */
    public function getMenuItemLabels() {
        return $this->labels;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     * @return MenuItem
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     * @return MenuItem
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }



}
