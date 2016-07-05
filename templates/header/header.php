<?php 
/**
 * This software is governed by the CeCILL-B license. If a copy of this license
 * is not distributed with this file, you can obtain one at
 * http://www.cecill.info/licences/Licence_CeCILL-B_V1-en.txt
 *
 * Author of Accounter: Bertrand THIERRY (bertrand.thierry1@gmail.com)
 *
 */
 
 /*
Contains logo and subtitle of the page
 */
 ?>
 <div class="col-lg-12">
<h1 id="main_title"><a href="<?php echo BASEURL?>"><img src="<?php echo BASEURL.'/img/logo.png'?>" alt="Accounter"></a></h1>

<?php if(isset($my_account) && isset($my_account['title']) && !empty($my_account['title']))
{
?>
<h2 id="main_subtitle">Welcome to the account: <?php echo htmlspecialchars($my_account['title'])?></h2>
<?php
}else
{
?>
<h2 id="main_subtitle">Manage the accounts with your friends</h2>
<?php	
}
?>
<hr class="separator-header">
</div>