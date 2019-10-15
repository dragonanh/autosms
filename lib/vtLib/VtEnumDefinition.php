<?php

// trang thai sim
abstract class CommonIsActive
{
    const INACTIVE = 0;
    const ACTIVE = 1;
    public static function getArr(){
        return array(self::INACTIVE => 'Không hiển thị', self::ACTIVE => 'Hiển thị');
    }
}
// trang thai sim
abstract class SimAttr
{
  const SIM_SALE = 1; #sim khuyen mai
  const SIM_PRIORITY = 2; #sim uu tien
  public static function getArr(){
    return array(self::SIM_PRIORITY => 'Sim ưu tiên',self::SIM_SALE => 'Sim khuyến mại');
  }
}
// trang thai sim
abstract class ProductStatus
{
  const INACTIVE = 0;
  const ACTIVE = 1;
  public static function getArr(){
    return array(self::INACTIVE => 'Không hiển thị', self::ACTIVE => 'Hiển thị');
  }
}
//trang thai don hang
abstract class CustomerOrderStatus
{
  const NEW_ORDER = 0; #dat hang
  const CUSTOMER_CONFIRM = 1; #khach hang xac nhan mua hang
  const COMPLETED = 2; #da mua thanh cong ngoai cua hang
  const CANCELED = 3; #da huy don hang
  const PROCESSING = 4; #dang xu ly
  public static function getArr(){
    return array(self::NEW_ORDER => 'Đơn hàng mới', self::CUSTOMER_CONFIRM => 'KH xác nhận mua', self::COMPLETED => 'Đã hoàn thành', self::CANCELED => 'Huỷ đơn hàng', self::PROCESSING => 'Đang xử lý');
  }

  public static function getStatusName($status){
    $statusArr = self::getArr();
    return isset($statusArr[$status]) ? $statusArr[$status] : '';
  }
}

// vi tri
abstract class ArticleGroupPosition
{
  const SIDEBAR_RIGHT = 1;
  const FOOTER = 2;
  const MENU_TOP = 4;
  const FOOTER_INFO = 5;
  const GENERAL = 6;
  public static function getArr(){
    return array(self::SIDEBAR_RIGHT => 'Sidebar right',self::FOOTER => 'Footer',
      self::MENU_TOP => 'Menu top', self::FOOTER_INFO => 'Footer info', self::GENERAL => 'Trang tin tức');
  }
}

abstract class CachePosition
{
  const ARTICLE_DETAIL = 1; #page article/indexSuccess
  const HEADER = 2; #component vtCommon/header
  const MAIN_MENU = 3; #component vtCommon/mainMenu
  const MORE_INFO = 4; #component vtCommon/moreInfo
  const FOOTER_INFO = 5; #component vtCommon/telcoFilter
  const ARTICLE_SIDEBAR_RIGHT = 6; #component vtCommon/listArticleSidebar
  const LIST_NEW_ORDER = 7; #component vtCommon/listNewOrder
  const LIST_SIM_SALE = 8; #component vtCommon/listSimSale
  const ARTICLE_ORDER = 9; #component vtOrder/articleOrder
  const ARTICLE_LIST = 10; #page article/listArticleSuccess
  const LIST_SIM = 11; #component vtCommon/_listSimResult
  const PHONG_THUY = 12; #page vtHomepage/phongThuy
  const BANNER = 13; #component vtCommon/banner
  const HOMEPAGE = 14; #trang chu
  const SIM_TYPE = 14; #trang the loai sim
  public static function getAllCacheArr(){
    return array(
      self::ARTICLE_DETAIL => array(
        'name' => 'Cache trang chi tiết bài viết',
        'cache_key' => "vtArticle/index"
      ),
      self::HEADER => array(
        'name' => 'Cache header',
        'cache_key' => "@sf_cache_partial?module=vtCommon&action=_header&sf_cache_key=*"
      ),
      self::MAIN_MENU => array(
        'name' => 'Cache menu chính',
        'cache_key' => "@sf_cache_partial?module=vtCommon&action=_mainMenu&sf_cache_key=*"
      ),
      self::MORE_INFO => array(
        'name' => 'Cache chính sách - hỗ trợ - thông tin',
        'cache_key' => "@sf_cache_partial?module=vtCommon&action=_moreInfo&sf_cache_key=*"
      ),
      self::FOOTER_INFO => array(
        'name' => 'Cache box bài viết chân trang',
        'cache_key' => "@sf_cache_partial?module=vtCommon&action=_footer&sf_cache_key=*"
      ),
      self::ARTICLE_SIDEBAR_RIGHT => array(
        'name' => 'Cache box danh sách bài viết ở sidebar phải',
        'cache_key' => "@sf_cache_partial?module=vtCommon&action=_listArticleSidebar&sf_cache_key=*"
      ),
      self::LIST_NEW_ORDER => array(
        'name' => 'Cache box danh sách đơn đặt hàng',
        'cache_key' => "@sf_cache_partial?module=vtCommon&action=_listNewOrder&sf_cache_key=*"
      ),
      self::LIST_SIM_SALE => array(
        'name' => 'Cache box danh sách sim khuyến mại',
        'cache_key' => "@sf_cache_partial?module=vtCommon&action=_listSimSale&sf_cache_key=*"
      ),
      self::ARTICLE_ORDER => array(
        'name' => 'Cache box bài viết trong trang đặt sim',
        'cache_key' => "@sf_cache_partial?module=vtCommon&action=_listSimSale&sf_cache_key=*"
      ),
      self::ARTICLE_LIST => array(
        'name' => 'Cache trang danh sách tin tức',
        'cache_key' => "vtArticle/listArticle"
      ),
      self::LIST_SIM => array(
        'name' => 'Cache box danh sach sim',
        'cache_key' => "@sf_cache_partial?module=vtCommon&action=_listSimResult&sf_cache_key=*"
      ),
      self::PHONG_THUY => array(
        'name' => 'Cache trang sim phong thuy',
        'cache_key' => "vtHomepage/phongThuy"
      ),
      self::BANNER => array(
        'name' => 'Cache banner',
        'cache_key' => "@sf_cache_partial?module=vtCommon&action=_banner&sf_cache_key=*"
      ),
      self::HOMEPAGE => array(
        'name' => 'Cache trang chủ',
        'cache_key' => "vtHomepage/index"
      ),
      self::SIM_TYPE => array(
        'name' => 'Cache trang thể loại sim',
        'cache_key' => "vtSimType/index"
      ),
    );
  }
}


abstract class SearchSimPrice
{
  public static function getSearchPrice(){
    return array(
      -2 => array('name' => 'Giá tăng dần','p_order' => 'asc'),
      -1 => array('name' => 'Giá giảm dần','p_order' => 'desc'),
      1 => array('name' => 'Dưới 300.000đ','from_money' => '', 'to_money' => 300000),
      2 => array('name' => 'Từ 3 trăm - 6 trăm','from_money' => 300000, 'to_money' => 600000),
      3 => array('name' => 'Từ 6 trăm - 1 triệu','from_money' => 600000, 'to_money' => 1000000),
      4 => array('name' => 'Từ 1 triệu - 3 triệu','from_money' => 1000000, 'to_money' => 3000000),
      5 => array('name' => 'Từ 3 triệu - 5 triệu','from_money' => 3000000, 'to_money' => 5000000),
      6 => array('name' => 'Từ 5 triệu - 10 triệu','from_money' => 5000000, 'to_money' => 10000000),
      7 => array('name' => 'Từ 10 triệu - 20 triệu','from_money' => 10000000, 'to_money' => 20000000),
      8 => array('name' => 'Từ 20 triệu - 30 triệu','from_money' => 20000000, 'to_money' => 30000000),
      9 => array('name' => 'Từ 30 triệu - 50 triệu','from_money' => 30000000, 'to_money' => 50000000),
      10 => array('name' => 'Trên 50 triệu','from_money' => 50000000, 'to_money' => ''),
    );
  }

  public static function getSearchPriceByValue($value){
    $priceArr = self::getSearchPrice();
    return isset($priceArr[$value]) ? $priceArr[$value] : array();
  }
}
abstract class AgencyChangeTypeEnum
{
  const UP_PRICE = 1; #tang gia
  const DOWN_PRICE = 2; # giam gia

  public static function getAgencyChangeTypeArr(){
    return array(self::UP_PRICE => '+%',self::DOWN_PRICE => '-%');
  }

  public static function getAgencyChangeTypeByValue($value){
    $changeTypeArr = self::getAgencyChangeTypeArr();
    return isset($changeTypeArr[$value]) ? $changeTypeArr[$value] : '';
  }
}
abstract class AddMultiSimPriceFormatEnum
{
  const PRICE_DEFAULT = 1; #1.000.000= 1trieu
  const LESS_1K = 2; #1.000 = 1trieu
  const LESS_1M = 3; #1 = 1trieu

  public static function getArr(){
    return array(self::PRICE_DEFAULT => '1.000.000=1tr',self::LESS_1K => '1.000=1tr',self::LESS_1M => '1=1tr');
  }
}
abstract class TelcoEnum
{
  const VIETTEL = 1;
  const VINAPHONE = 2;
  const MOBIFONE = 3;
  const VIETNAMOBILE = 4;
  const GMOBILE = 5;
  const SFONE = 6;
  public static function getArrSyntaxSeo(){
    return array(self::VIETTEL => 'seo_sim_viettel', self::VINAPHONE => 'seo_sim_vinaphone', self::MOBIFONE => 'seo_sim_mobifone',
      self::VIETNAMOBILE => 'seo_sim_vietnamobile', self::GMOBILE => 'seo_sim_gmobile', self::SFONE => 'seo_sim_sfone'
    );
  }

  public static function getArr(){
    return array(self::VIETTEL => 'Viettel', self::VINAPHONE => 'Vinaphone', self::MOBIFONE => 'Mobifone',
      self::VIETNAMOBILE => 'Việt Nam mobile', self::GMOBILE => 'GMobile', self::SFONE => 'SFone'
    );
  }

  public static function getTelcoName($id){
    $nameArr = self::getArr();
    return $nameArr[$id];
  }

}




