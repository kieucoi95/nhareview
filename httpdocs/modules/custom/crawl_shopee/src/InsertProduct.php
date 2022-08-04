<?php

namespace Drupal\crawl_shopee;


use Drupal\node\Entity\Node;
use Drupal\Component\Serialization\Json;

class InsertProduct {

  public static function InsertProduct($nids){
    // $message = 'Insert Product...';
    $results = array();
    
    foreach ($nids as $nid) {
      $query = \Drupal::entityQuery('node')
        ->condition('type', 'product')
        ->condition('field_id', $nid['id'], '=');

      $nids = $query->execute();
      
      $terms = \Drupal::entityQuery('taxonomy_term')
      ->condition('vid','category')
      ->condition('tid', $nid['category'], '=')
      ->execute();
      $term = array_values($terms)[0];

      // $tiki = Json::decode(file_get_contents('https://tiki.vn/api/v2/products?limit=1&include=advertisement&aggregations=2&trackity_id=18af4b37-f4b3-f3a5-ed33-d1fe8a500b78&q=' . rawurlencode($nid['name']) . '&ref=hot-keyword'));
      $tiki_link = '';
      $tiki_price = '';
      $accesstrade_tiki = '';
      $tiki_name = '';

      // if (!empty($tiki['data'])) {
      //   $tiki_link = 'https://tiki.vn/' . $tiki['data'][0]['url_key'] . '.html';
      //   $tiki_price = $tiki['data'][0]['price'];
      //   $accesstrade_tiki = \Drupal::config('crawl_shopee.settings')->get('accesstrade_tiki') . '?url=' . rawurlencode($tiki_link);
      //   $tiki_name = $tiki['data'][0]['name'];
      // }
      

      if (!$nids) {
        // $item_detail = \Drupal::service('crawl_shopee.crawl_shopee_client')->getItemDetail($nid['item_id'], $nid['shop_id']);
        try {
          $context = stream_context_create(
            array(
                "http" => array(
                    "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                )
            )
          );
          $item_detail = Json::decode(file_get_contents('https://shopee.vn/api/v4/item/get?itemid=' . $nid['item_id'] . '&shopid=' . $nid['shop_id'], false, $context));
          $description = '';
          if (!empty($item_detail['data'])) {
            $description = $item_detail['data']['description'];
          }

          $node = Node::create([
            'type'  => 'product',
            'title' => $nid['name'],
            'field_discount' => $nid['discount'],
            'field_historical_sold' => $nid['historical_sold'],
            'field_id' => $nid['id'],
            'field_liked_count' => $nid['liked_count'],
            'field_price' => $nid['price'],
            'field_price_before_discount' => $nid['price_before_discount'],
            'field_rating_star' => $nid['rating_star'],
            'field_shop_location' => $nid['shop_location'],
            'field_gallery' => $nid['gallery'],
            'field_brand' => $nid['brand'],
            'field_avatar' => $nid['avatar'],
            'field_price_tiki' => $tiki_price,
            'field_link_tiki' => $accesstrade_tiki,
            'body' => $description . $tiki_name,
            'field_category'  => [
              ['target_id' => $term]
            ]
          ]);
          $results['create'][] = $node->save();
          \Drupal::logger('crawl_shopee')->notice('Create node ' . $nid['name']);
        } catch (Exception $e) {
          \Drupal::logger('crawl_shopee')->error('SEARCH ITEMS ERROR: //////' . $e->getMessage());
        }
        
        
      }
      
    }

    
  }

  function InsertProductFinishedCallback($success, $results, $operations) {
    // The 'success' parameter means no fatal PHP errors were detected. All
    // other error management should be handled using 'results'.
    if ($success) {
      $message = count($results['create']) . ' posts create. ' . count($results['update']) . ' posts update.';
    }
    else {
      $message = t('Finished with an error.');
    }
    drupal_set_message($message);
  }

  public static function delete_all_between($beginning, $end, $string) {
    $beginningPos = strpos($string, $beginning);
    $endPos = strpos($string, $end);
    if ($beginningPos === false || $endPos === false) {
      return $string;
    }

    $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

    return \Drupal\crawl_shopee\InsertProduct::delete_all_between($beginning, $end, str_replace($textToDelete, '', $string));
  }

}