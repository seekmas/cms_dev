<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_installer
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * Extension Manager Default View
 *
 * @package		Joomla.Administrator
 * @subpackage	com_installer
 * @since		1.5
 */

if(!class_exists('PpinstallerViewAdapt')) {
	if(interface_exists('JView')) {
		abstract class PpinstallerViewAdapt extends JViewLegacy {}
	} else {
		class PpinstallerViewAdapt extends JView {}
	}
}

class PpInstallerViewDefault extends PpinstallerViewAdapt
{
	/**
	 * @since	1.5
	 */
	function __construct($config = null)
	{
		//$app = JFactory::getApplication();
		$this->task = JRequest::getVar('task','display');
		$this->needToContinue = true;
		parent::__construct($config);
		
		//Set toolbar
		self::_SetToolbar(JText::_('COM_PPINSTALLER'));
	}
	
	private function _SetToolbar($title)
	{
		JToolBarHelper::title($title,'pp-installer.png');
		//JSubMenuHelper::addEntry("Home","index.php?option=com_ppinstaller");
	}

	/**
	 * @since	1.5
	 */
	function display($tpl=null)
	{		
		$this->preRequirements();	
		$this->assign('nextTask',PpinstallerController::nextTask($this->task,$this->needToContinue));
		$this->render($tpl);
	}
	
	function patch($tpl=null)
	{	
		$migrate_info = PpinstallerController::_patch($this);	
		$this->assign('nextTask',PpinstallerController::nextTask($this->task,$this->needToContinue));
		$this->render($tpl);
	}
	
	public function migrate($tpl=null)
	{
		$migrate_info = PpinstallerController::_migrate($this->needToContinue);
		
		// key => {msg,nextTask,migrateAction,limit}
		foreach($migrate_info as $key=>$value){
			$this->assign($key,$value);
		}
			
		$this->render($tpl);
	}
	
	public function preRequirements()
	{
		$preReq = get_class_methods('PpinstallerHelperPrecheck');
		
		foreach ($preReq as $req){
			$result = PpinstallerHelperPrecheck::$req();
			if(empty($result)){	continue;}
			// If you don't have all minimum requirements then you can't install payplans
			if(PPINSTALLER_ERROR_LEVEL <= $result['status']){
				$this->needToContinue = false;
			}
			$results[] = $result;
		}
		$this->assign('results',$results);
	}
	
	public  function install($tpl=null) 
	{
		//XiTODO:: Make sure .....Installation process success.
		
		$app = JFactory::getApplication();
		//set to null
		$app->input->set('_messageQueue',null);
		
		$this->assign('nextTask',PpinstallerController::nextTask($this->task,$this->needToContinue));
		$this->render($tpl);
	}
	
	public function render($tpl)
	{
		PpinstallerHelperInstall::addStyle();
		PpinstallerHelperInstall::addScript();
		
		?>
		<div class="payplans">
			<div class="grid_12 alpha omega">
				
				<div class="grid_2 alpha">
					<div class="clearfix pp-body">
						<fieldset class="pp-parameter">
	 						<legend><?php echo JText::_('COM_PPINSTALLER_STEPS');?></legend>
	 					</fieldset>
					</div>

					 <div class="clearfix pp-body">
	 					<div class="pp-stepbar">
							<?php echo PpinstallerHelperInstall::stepbar(); ?>
			 			</div>
		 			</div>
				</div>

				<div class="grid_10 omega pp-blueleft clearfix ">
					<div class="clearfix pp-body">
						<form action="index.php" method="post" name="adminForm" id="adminForm" class="pp-adminForm" >
							<?php echo parent::display($tpl);?>
						</form>
					</div>
					
					<div class="clearfix pp-footer">
						<div class="pp-desc">
							<?php echo PpinstallerHelperInstall::task_description($this->nextTask); ?>
			 			</div>
						<div class="pp-next">
							<?php if(!empty($this->nextTask)):?>
							<?php  $next_task = JText::_(JString::strtoupper('COM_PPINSTALLER_'.$this->nextTask.'_BUTTON')); ?>
							<button class=" btn btn-primary " 
                                                                title="<?php echo $next_task; ?>" onclick="ppInstaller.submitform();"
                                                                id ="ppInstaller_submit_button">
								<?php echo $next_task ;?>
							</button>
                             <?php endif;?>                     
                          <img  src="<?php echo PPINSTALLER_IMG.'loader.gif'; ?>" 
                                id="ppInstaller_spinner" style="display:none" 
                            />
						</div>
					</div>
					
					<div class="pp-float-right clearfix pp-powered-by">
			      			<?php  echo PpinstallerHelperInstall::powered_by(); ?>
					</div>
				</div>

				
			</div>
			
				
		</div>
		<?php 

	}
}
