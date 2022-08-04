<?php

namespace Drupal\crawl_shopee\Services;

use Drupal\node\Entity\Node;
use Drupal\Component\Serialization\Json;

/**
 * Class CustomService.
 */
class InsertProductService {

  /**
   * Constructs a new CustomService object.
   */
  public function __construct() {

  }

  public function insertProductData() {
    $vid = 'category';
    $terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
    foreach ($terms as $term) {
      $term_data[] = array(
        'id' => $term->tid,
        'name' => rawurlencode($term->name),
      );
    }

    $context = stream_context_create(
      array(
          "http" => array(
              "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
          )
      )
    );
    
    $limit = 10;

    $result = [];

    foreach ($term_data as &$category) {

      // $response = \Drupal::service('crawl_shopee.crawl_shopee_client')->getListItem($category['name']);
      try {
        $response = Json::decode(file_get_contents('https://shopee.vn/api/v4/search/search_items?by=relevancy&keyword=' . $category['name'] . '&limit=60&newest=0&order=desc&page_type=search&scenario=PAGE_GLOBAL_SEARCH&version=2', false, $context));
        $ShopeeArr = [];
      if (!empty($response['items'])) {
        foreach ($response['items'] as &$value) {
          $item_id = $value['itemid'];
          $shop_id = $value['shopid'];
          $id = $value['shopid'] . '.' . $value['itemid'];
          $avatar = $value['item_basic']['image'];
          $name = \Drupal\crawl_shopee\InsertProduct::delete_all_between('[', ']', $value['item_basic']['name']);
          $discount = $value['item_basic']['raw_discount'];
          $historical_sold = $value['item_basic']['historical_sold'];
          $price = $value['item_basic']['price'];
          $price_before_discount = $value['item_basic']['price_before_discount'];
          $liked_count = $value['item_basic']['liked_count'];
          $rating_star = $value['item_basic']['item_rating']['rating_star'];
          $shop_location = $value['item_basic']['shop_location'];
          $brand = $value['item_basic']['brand'];
          $gallery = $value['item_basic']['images'];
          $category_id = $category['id'];
          $category_value = $category['name'];
    
          $ShopeeArr[] = [
            'shop_id' => $shop_id,
            'item_id' => $item_id,
            'id' => $id,
            'avatar' => $avatar,
            'name' => $name,
            'historical_sold' => $historical_sold,
            'discount' => $discount,
            'price' => $price,
            'price_before_discount' => $price_before_discount,
            'liked_count' => $liked_count,
            'rating_star' => $rating_star,
            'shop_location' => $shop_location,
            'brand' => $brand,
            'gallery' => $gallery,
            'category' => $category_id,
            'category_name' => $category_value
          ];
        }
        $getResult = \Drupal\crawl_shopee\InsertProduct::InsertProduct($ShopeeArr);
      }
      } catch (Exception $e) {
        \Drupal::logger('crawl_shopee')->error('SEARCH ITEMS ERROR: //////' . $e->getMessage());
      }
    }
  }
}