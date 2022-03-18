<?php

namespace Drupal\crawl_shopee;


use Drupal\node\Entity\Node;

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

      if ($nids) {
        $node = Node::load(array_values($nids)[0]);
        //set value for field
        $node->title->value = $nid['name'];
        $node->field_discount->value = $nid['discount'];
        $node->field_historical_sold->value = $nid['historical_sold'];
        $node->field_id->value = $nid['id'];
        $node->field_liked_count->value = $nid['liked_count'];
        $node->field_price->value = $nid['price'];
        $node->field_price_before_discount->value = $nid['price_before_discount'];
        $node->field_rating_star->value = $nid['rating_star'];
        $node->field_shop_location->value = $nid['shop_location'];
        $node->field_avatar->value = $nid['avatar'];
        $node->field_brand->value = $nid['brand'];
        $node->field_gallery = $nid['gallery'];
        $node->field_category->target_id = $term;

        $results['update'][] = $node->save();
        \Drupal::logger('crawl_shopee')->notice('Update node ' . $nid['name']);
      } else {

        $item_detail = \Drupal::service('crawl_shopee.crawl_shopee_client')->getItemDetail($nid['item_id'], $nid['shop_id']);
        $description = $item_detail['data']['description'];

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
          'body' => $description,
          'field_category'  => [
            ['target_id' => $term]
          ]
        ]);
        $results['create'][] = $node->save();
        \Drupal::logger('crawl_shopee')->notice('Create node ' . $nid['name']);

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