<?php

namespace Drupal\crawl_shopee\Form;
    
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class AdminSettingsForm.
 *
 * @package Drupal\crawl_shopee\Form
 */
class ConfigShopeeAffiliateForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'config_shopee_affiliate_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'crawl_shopee.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('crawl_shopee.settings');

    $form['shopee_affiliate'] = [
      '#type' => 'textarea',
      '#default_value' => $config->get('shopee_affiliate'),
      '#title' => $this->t('Shopee affiliate'),
    ];

    $form['accesstrade_lazada'] = [
      '#type' => 'textfield',
      '#default_value' => $config->get('accesstrade_lazada'),
      '#title' => $this->t('accesstrade lazada'),
    ];
    
    $form['accesstrade_tiki'] = [
      '#type' => 'textfield',
      '#default_value' => $config->get('accesstrade_tiki'),
      '#title' => $this->t('accesstrade tiki'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('crawl_shopee.settings');
    $config->set('shopee_affiliate', $form_state->getValue('shopee_affiliate'));
    $config->set('accesstrade_tiki', $form_state->getValue('accesstrade_tiki'));
    $config->set('accesstrade_lazada', $form_state->getValue('accesstrade_lazada'));
    $config->save();
    parent::submitForm($form, $form_state);
  }

}