<?php

namespace Drupal\crawl_shopee\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class InsertNodeForm.
 *
 * @package Drupal\crawl_shopee\Form
 */
class InsertProductForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'insert_product_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['Limit'] = array(
      '#type' => 'number',
      '#title' => t('Limit'),
      '#required' => TRUE,
    );

    $form['Category'] = array (
      '#type' => 'select',
      '#title' => ('Chuyên mục'),
      '#options' => array(
        '527255' => t('Sách Ngoại Ngữ'),
        '508473' => t('Truyện Tranh'),
        '527254' => t('Sách Đời Sống'),
        '527256' => t('Sách Kinh Tế'),
        '527258' => t('Sách Văn Học')
      ),
    );

    $form['insert_product'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Insert product'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $vid = 'category';
    $terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
    foreach ($terms as $term) {
      $term_data[] = array(
        'id' => $term->tid,
        'name' => $term->name,
      );
    }

    foreach ($term_data as &$category) {
      dump($category['id']);
      dump($category['name']);
    }

    // dump($term_data);
    die();
    $limit = $form_state->getValue('Limit');
    $category = $form_state->getValue('Category');

    $response = \Drupal::service('crawl_shopee.crawl_shopee_client')->getListItem($limit, $category);

    $ShopeeArr = [];
    foreach ($response['items'] as &$value) {
      $item_id = $value['itemid'];
      $shop_id = $value['shopid'];
      $id = $value['shopid'] . '.' . $value['itemid'];
      $avatar = $value['item_basic']['image'];
      $name = $value['item_basic']['name'];
      $discount = $value['item_basic']['raw_discount'];
      $historical_sold = $value['item_basic']['historical_sold'];
      $price = $value['item_basic']['price'];
      $price_before_discount = $value['item_basic']['price_before_discount'];
      $liked_count = $value['item_basic']['liked_count'];
      $rating_star = $value['item_basic']['item_rating']['rating_star'];
      $shop_location = $value['item_basic']['shop_location'];
      $brand = $value['item_basic']['brand'];
      $gallery = $value['item_basic']['images'];
      $category_id = $category;

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
        'category' => $category_id
      ];
    }
    
    $batch = array(
      'title' => t('Insert product...'),
      'operations' => array(
        array(
          '\Drupal\crawl_shopee\InsertProduct::InsertProduct',
          array($ShopeeArr)
        ),
      ),
      'finished' => '\Drupal\crawl_shopee\InsertProduct::InsertProductFinishedCallback',
    );

    batch_set($batch);
  }

}