<?php
/**
 * TRadioButton class file
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.pradosoft.com/
 * @copyright Copyright &copy; 2005 PradoSoft
 * @license http://www.pradosoft.com/license/
 * @version $Revision: $  $Date: $
 * @package System.Web.UI.WebControls
 */

/**
 * Using TCheckBox parent class
 */
Prado::using('System.Web.UI.WebControls.TCheckBox');
/**
 * Using TRadioButtonList class
 */
Prado::using('System.Web.UI.WebControls.TRadioButtonList');

/**
 * TRadioButton class
 *
 * TRadioButton displays a radio button on the page.
 * You can specify the caption to display beside the radio buttonby setting
 * the {@link setText Text} property.  The caption can appear either on the right
 * or left of the radio button, which is determined by the {@link setTextAlign TextAlign}
 * property.
 *
 * To determine whether the TRadioButton component is checked, test the {@link getChecked Checked}
 * property. The {@link onCheckedChanged OnCheckedChanged} event is raised when
 * the {@link getChecked Checked} state of the TRadioButton component changes
 * between posts to the server. You can provide an event handler for
 * the {@link onCheckedChanged OnCheckedChanged} event to  to programmatically
 * control the actions performed when the state of the TRadioButton component changes
 * between posts to the server.
 *
 * TRadioButton uses {@link setGroupName GroupName} to group together a set of radio buttons.
 * Once the {@link setGroupName GroupName} is set, you can use the {@link getRadioButtonsInGroup}
 * method to get an array of TRadioButtons having the same group name.
 *
 * If {@link setAutoPostBack AutoPostBack} is set true, changing the radio button state
 * will cause postback action. And if {@link setCausesValidation CausesValidation}
 * is true, validation will also be processed, which can be further restricted within
 * a {@link setValidationGroup ValidationGroup}.
 *
 * Note, {@link setText Text} is rendered as is. Make sure it does not contain unwanted characters
 * that may bring security vulnerabilities.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Revision: $  $Date: $
 * @package System.Web.UI.WebControls
 * @since 3.0
 */
class TRadioButton extends TCheckBox
{
	/**
	 * @param array list of radio buttons registered.
	 */
	private static $_radiobuttons=array();	
	/**
	 * @var string the name used to fetch radiobutton post data
	 */
	private $_uniqueGroupName=null;
	/**
	 * @var int number radio buttons created
	 */
	private static $_counter=0;
	/**
	 * @var int unique unmutable radio button id
	 */
	private $_radioUid;

	public function __construct()
	{
		parent::__construct();
		$this->_radioUid = self::$_counter++;
	}
	
	/**
	 * Registers the radio button groupings. If overriding onInit method, 
	 * ensure to call parent implemenation.
	 * @param TEventParameter event parameter to be passed to the event handlers
	 */
	public function onInit($param)
	{
		parent::onInit($param);
		$this->registerRadioButton($this);
	}
	
	/**
	 * Unregisters the radio button groupings. If overriding onInit method,
	 * ensure to call parent implemenation.
	 * @param TEventParameter event parameter to be passed to the event handlers
	 */
	public function onUnLoad($param)
	{
		parent::onUnLoad($param);
		$this->unregisterRadioButton($this);
	}
	
	/**
	 * Loads user input data.
	 * This method is primarly used by framework developers.
	 * @param string the key that can be used to retrieve data from the input data collection
	 * @param array the input data collection
	 * @return boolean whether the data of the control has been changed
	 */
	public function loadPostData($key,$values)
	{
		$uniqueGroupName=$this->getUniqueGroupName();
		$value=isset($values[$uniqueGroupName])?$values[$uniqueGroupName]:null;
		if($value!==null && $value===$this->getValueAttribute())
		{
			if(!$this->getChecked())
			{
				$this->setChecked(true);
				return true;
			}
			else
				return false;
		}
		else if($this->getChecked())
			$this->setChecked(false);
		return false;
	}

	/**
	 * @return string the name of the group that the radio button belongs to. Defaults to empty.
	 */
	public function getGroupName()
	{
		return $this->getViewState('GroupName','');
	}

	/**
	 * Sets the name of the group that the radio button belongs to
	 * @param string the group name
	 */
	public function setGroupName($value)
	{
		$this->setViewState('GroupName',$value,'');
	}
	
	/**
	 * Register radio button control grouping.
	 * @param TRadioButton control to add
	 */
	protected function registerRadioButton(TRadioButton $control)
	{
		if(!isset(self::$_radiobuttons[$control->_radioUid]))
			self::$_radiobuttons[$control->_radioUid] = $control;
	}
	
	/**
	 * Unregister radio button control for grouping
	 * @param TRadioButton control to unregister.
	 */
	protected function unregisterRadioButton(TRadioButton $control)
	{
		if(isset(self::$_radiobuttons[$control->_radioUid]))
			unset(self::$_radiobuttons[$control->_radioUid]);
	}
	
	/**  
	 * Gets an array of RadioButtons with same group name. This method will
	 * always return at least the current radio button in the array.
	 * @return array list of TRadioButton with the same group
	 */
	public function getRadioButtonsInGroup()
	{
		$group = $this->getUniqueGroupName();
		$buttons = array();
		foreach(self::$_radiobuttons as $control)
		{
			if($control->getUniqueGroupName() === $group)
				$buttons[] = $control;
		}
		return count($buttons) > 0 ? $buttons : array($this);
	}

	/**
	 * @return string the value attribute to be rendered
	 */
	protected function getValueAttribute()
	{
		if(($value=parent::getValueAttribute())==='')
			return $this->getUniqueID();
		else
			return $value;
	}

	/**
	 * @return string the name used to fetch radiobutton post data
	 */
	private function getUniqueGroupName()
	{
		if($this->_uniqueGroupName===null)
		{
			$groupName=$this->getGroupName();
			$uniqueID=$this->getUniqueID();
			if($uniqueID!=='')
			{
				if(($pos=strrpos($uniqueID,TControl::ID_SEPARATOR))!==false)
				{
					if($groupName!=='')
						$groupName=substr($uniqueID,0,$pos+1).$groupName;
					else if($this->getNamingContainer() instanceof TRadioButtonList)
						$groupName=substr($uniqueID,0,$pos);
				}
				if($groupName==='')
					$groupName=$uniqueID;
			}
			$this->_uniqueGroupName=$groupName;
		}
		return $this->_uniqueGroupName;
	}

	/**
	 * Renders a radiobutton input element.
	 * @param THtmlWriter the writer for the rendering purpose
	 * @param string checkbox id
	 * @param string onclick js
	 */
	protected function renderInputTag($writer,$clientID,$onclick)
	{
		if($clientID!=='')
			$writer->addAttribute('id',$clientID);
		$writer->addAttribute('type','radio');
		$writer->addAttribute('name',$this->getUniqueGroupName());
		$writer->addAttribute('value',$this->getValueAttribute());
		if($onclick!=='')
			$writer->addAttribute('onclick',$onclick);
		if($this->getChecked())
			$writer->addAttribute('checked','checked');
		if(!$this->getEnabled(true))
			$writer->addAttribute('disabled','disabled');

		$page=$this->getPage();
		if($this->getEnabled(true) && $this->getAutoPostBack() && $page->getClientSupportsJavaScript())
			$this->renderClientControlScript($writer);

		if(($accesskey=$this->getAccessKey())!=='')
			$writer->addAttribute('accesskey',$accesskey);
		if(($tabindex=$this->getTabIndex())>0)
			$writer->addAttribute('tabindex',"$tabindex");
		if($attributes=$this->getViewState('InputAttributes',null))
			$writer->addAttributes($attributes);
		$writer->renderBeginTag('input');
		$writer->renderEndTag();
	}

	/**
	 * Renders the client-script code.
	 */
	protected function renderClientControlScript($writer)
	{
		$cs = $this->getPage()->getClientScript();
		$cs->registerPostBackControl($this->getClientClassName(),$this->getPostBackOptions());
	}

	/**
	 * Gets the name of the javascript class responsible for performing postback for this control.
	 * This method overrides the parent implementation.
	 * @return string the javascript class name
	 */
	protected function getClientClassName()
	{
		return 'Prado.WebUI.TRadioButton';
	}
}

?>
