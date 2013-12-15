<?php
/**
* @copyright	Copyright (C) 2009 - 2011 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @package		PayPlans
* @subpackage	Frontend
* @contact 		shyam@readybytes.in
* website		http://www.jpayplans.com
* Technical Support : Forum -	http://www.jpayplans.com/support/support-forum.html
*/
if(defined('_JEXEC')===false) die();

class PayplansadminControllerConfig extends XiController
{
	protected 	$_defaultTask = 'edit';

	public function _save(array $data, $itemId=null, $type=null)
	{
		//fields with blank value does not get posted so value does not get updated in the configuration
		$modelform  = PayplansFactory::getInstance('config', 'Modelform' , 'Payplans');
		$form		= $modelform->getForm();
		$form->loadFile(PAYPLANS_PATH_XML.DS.'config.xml',false, '//config');
		$fieldsets  = $form->getFieldsets();
		
		//save logo image
		if(!empty($_FILES['Payplans_form']['tmp_name']['companyLogo'])){
			$companylogo_imgpath = $_FILES['Payplans_form']['tmp_name']['companyLogo'];
			$companylogo_imgname = $_FILES['Payplans_form']['name']['companyLogo'];
			$supported_imageExt	= array("jpg","jpeg","png","gif");
			$logo_image=PayplansHelperUtils::saveUploadedFile("images/payplans",$companylogo_imgpath,$companylogo_imgname,$supported_imageExt,"companylogo");
			$data['companyLogo'] = $logo_image;
		}
		
		foreach ($fieldsets as $name => $fieldSet){
			foreach ($form->getFieldset($name) as $field){
				$configParams[] = $field->fieldname;
			}
		}
		
		$model 	= $this->getModel();
		$model->save($data);
		return true;
	}
	
	public function removecompanylogo()
	{
		$image = XiFactory::getConfig()->companyLogo;
    	$model = $this->getModel();
    	$model->save(array('companyLogo'=>''));
    
    	XiHelperPatch::removeFile(JPATH_ROOT.DS.$image);
		$this->setRedirect(XiRoute::_('index.php?option=com_payplans&view=config&task=edit'));

		return false;
	}
	
	
	public function migration()
	{
		$this->setTemplate('migration');
		return true;
	}
	
	public function doLogMigration()
	{
		$object1        = new stdClass();
		$object1->id    = "button-migrate-now";
		$object1->click = 'payplans.admin.config.migrateLogs.start()';
		$object1->text 	= XiText::_('COM_PAYPLANS_LOG_MIGRATION_BUTTON');
		$object1->classes = "btn-large btn btn-primary";
		
		$object2 		= new stdClass();
		$object2->id    = "button-migrate-cancel";
		$object2->click = 'xi.ui.dialog.close()';
		$object2->text 	= XiText::_('COM_PAYPLANS_LOG_MIGRATION_CANCEL');
		$object2->classes = "btn-large btn";
		
		$html		 = XiText::_("COM_PAYPLANS_MESSAGE_BEFORE_LOG_MIGRATION");	
        $title 		 = XiText::_('COM_PAYPLANS_TITLE_BEFORE_LOG_MIGRATION');
		$domObject   = 'xiWindowBody';
		$domProperty = 'innerHTML';

 		$response	 = XiFactory::getAjaxResponse();
 		$response->addAssign( $domObject , $domProperty , $html );
		$response->addScriptCall('xi.ui.dialog.title',$title);
 		$response->addScriptCall('xi.ui.dialog.button',array($object1,$object2));
        $response->sendResponse();
	}
	
}