<?php
/**
 * @package 	Logout for Joomla! 3.X
 * @version 	0.0.1
 * @author 		Function90.com
 * @copyright 	C) 2013- Function90.com
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
**/
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<script type="text/javascript">
	(function($){
		$(document).ready(function(){
			$('.f90-logout-button').click(function(){
				$('#f90-logout-form').submit();
			});
		});
	})(jQuery);
</script> 
		
<form action="index.php" method="post" id="f90-logout-form" class="form-vertical">
	<div class="logout-button">
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.logout" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
<?php 