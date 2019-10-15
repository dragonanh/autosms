[?php

require_once(dirname(__FILE__).'/../lib/Base<?php echo ucfirst($this->moduleName) ?>GeneratorConfiguration.class.php');
require_once(dirname(__FILE__).'/../lib/Base<?php echo ucfirst($this->moduleName) ?>GeneratorHelper.class.php');

/**
 * <?php echo $this->getModuleName() ?> actions.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage <?php echo $this->getModuleName()."\n" ?>
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: actions.class.php 31002 2010-09-27 12:04:07Z Kris.Wallsmith $
 */
abstract class <?php echo $this->getGeneratedModuleName() ?>Actions extends <?php echo $this->getActionsBaseClass()."\n" ?>
{
  /**
   * Ham xu ly ajax cho cac truong dang boolean
   * @author NamDT5
   * @created on 04/01/2013
   * @param sfWebRequest $request
   * @return string
   */
  public function executeToggleBoolean(sfWebRequest $request)
  {
    try {
      $request->checkCSRFProtection();
    } catch (Exception $e) {
      return $this->renderText('csrf');
    }
    $table = Doctrine_Core::getTable('<?php echo $this->getModelClass(); ?>');

    if (
      $table->hasField($field = $request->getParameter('field'))
      && ($record = $table->find($request->getParameter('pk')))
    ) {
      $record->set($field, !$record->get($field));
      $record->save();

      return $this->renderText($record->$field ? '1' : '0');
    } else {
      return $this->renderText('404');
    }
  }

  public function preExecute()
  {
    $this->configuration = new <?php echo $this->getModuleName() ?>GeneratorConfiguration();

    if (!$this->getUser()->hasCredential($this->configuration->getCredentials($this->getActionName())))
    {
      $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
    }

    $this->dispatcher->notify(new sfEvent($this, 'admin.pre_execute', array('configuration' => $this->configuration)));

    $this->helper = new <?php echo $this->getModuleName() ?>GeneratorHelper();

    parent::preExecute();
  }

<?php include dirname(__FILE__).'/../../parts/ajaxAction.php' ?>

<?php include dirname(__FILE__).'/../../parts/indexAction.php' ?>

<?php if ($this->configuration->hasFilterForm()): ?>
<?php include dirname(__FILE__).'/../../parts/filterAction.php' ?>
<?php endif; ?>

<?php include dirname(__FILE__).'/../../parts/newAction.php' ?>

<?php include dirname(__FILE__).'/../../parts/createAction.php' ?>

<?php include dirname(__FILE__).'/../../parts/editAction.php' ?>

<?php // include dirname(__FILE__).'/../../parts/showAction.php' ?>

<?php include dirname(__FILE__).'/../../parts/revertAction.php' ?>

<?php include dirname(__FILE__).'/../../parts/updateAction.php' ?>

<?php include dirname(__FILE__).'/../../parts/deleteAction.php' ?>

<?php if ($this->configuration->getValue('list.batch_actions')): ?>
<?php include dirname(__FILE__).'/../../parts/batchAction.php' ?>
<?php endif; ?>

<?php include dirname(__FILE__).'/../../parts/processFormAction.php' ?>

<?php if ($this->configuration->hasFilterForm()): ?>
<?php include dirname(__FILE__).'/../../parts/filtersAction.php' ?>
<?php endif; ?>

<?php include dirname(__FILE__).'/../../parts/paginationAction.php' ?>

<?php include dirname(__FILE__).'/../../parts/sortingAction.php' ?>

<?php include dirname(__FILE__).'/../../parts/repositionAction.php' ?>
}
