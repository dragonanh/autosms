<?php

/**
 * Created by JetBrains PhpStorm.
 * User: ngoctv1
 * Date: 5/6/14
 * Time: 6:08 PM
 * To change this template use File | Settings | File Templates.
 */
class CommonComponents extends sfComponents
{
    public function executeHeader(){
//      $this->listCategory = CategoryTable::getListActiveCategory(7);
    }

    public function executeSidebarRight(){
      $this->listFeaturedProduct = ProductTable::getListFeaturedProduct(5);
      $this->listArticle = ArticleTable::getListArticleByPosition(ArticleGroupPosition::SIDEBAR_RIGHT, 7);
    }
}
