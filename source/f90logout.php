<?php
/**
 * @package 	Logout for Joomla! 3.X
 * @version 	0.0.1
 * @author 		Function90.com
 * @copyright 	C) 2013- Function90.com
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
**/
defined( '_JEXEC' ) or die( 'Restricted access' );

class plgSystemF90logout extends JPlugin
{	
	public function onAfterRender()
	{
		$app = JFactory::getApplication();
		if($app->isAdmin()){
			return true;
		}
		if(!JFactory::getUser()->id){
			return true;
		}
		
		$return	= $this->getReturnURL();
		
		ob_start();
		require_once dirname(__FILE__).'/tmpl/logout.php';
		$contents = ob_get_contents();
		ob_end_clean();
		
		$body = JResponse::getBody();
		$body = str_ireplace('</body>', $contents.'</body>', $body);
      	JResponse::setBody($body);
		return true;
	}
	
	public function getReturnURL()
	{
		$app	= JFactory::getApplication();
		$router = $app->getRouter();
		$url = null;
		if ($itemid = $this->params->get('logout_return'))
		{
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true)
				->select($db->quoteName('link'))
				->from($db->quoteName('#__menu'))
				->where($db->quoteName('published') . '=1')
				->where($db->quoteName('id') . '=' . $db->quote($itemid));

			$db->setQuery($query);
			if ($link = $db->loadResult())
			{
				if ($router->getMode() == JROUTER_MODE_SEF)
				{
					$url = 'index.php?Itemid='.$itemid;
				}
				else {
					$url = $link.'&Itemid='.$itemid;
				}
			}
		}
		if (!$url)
		{
			// Stay on the same page
			$uri = clone JURI::getInstance();
			$vars = $router->parse($uri);
			unset($vars['lang']);
			if ($router->getMode() == JROUTER_MODE_SEF)
			{
				if (isset($vars['Itemid']))
				{
					$itemid = $vars['Itemid'];
					$menu = $app->getMenu();
					$item = $menu->getItem($itemid);
					unset($vars['Itemid']);
					if (isset($item) && $vars == $item->query)
					{
						$url = 'index.php?Itemid='.$itemid;
					}
					else {
						$url = 'index.php?'.JURI::buildQuery($vars).'&Itemid='.$itemid;
					}
				}
				else
				{
					$url = 'index.php?'.JURI::buildQuery($vars);
				}
			}
			else
			{
				$url = 'index.php?'.JURI::buildQuery($vars);
			}
		}

		return base64_encode($url);
	}
	
}
