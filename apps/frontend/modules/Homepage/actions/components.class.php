<?php

/**
 * vtManageAvatar actions.
 *
 * @package    mobile_marketing
 * @subpackage vtManageAvatar
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php_bak.bak 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class HomepageComponents extends sfComponents
{
  public function executeListProductCategory(sfWebRequest $request){
    $listCategory = CategoryTable::getListActiveCategory();
    $results = array();
    if(count($listCategory)){
      foreach ($listCategory as $category){
        $listProduct = ProductTable::getListProductByCategory($category['id'], 20);
        $results[] = array(
          'id' => $category['id'],
          'name' => $category['name'],
          'slug' => $category['slug'],
          'list_product' => $listProduct
        );
      }
    }
    $this->results = $results;
  }

  public function executeRecentlyViewed(){

  }
  
}