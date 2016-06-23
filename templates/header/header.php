<div class="col-lg-12">
<h1 id="main_title"><a href="<?php echo BASEURL?>"><img src="<?php echo BASEURL.'/img/logo.png'?>" alt="Accounter"></a></h1>

<?php if(isset($my_account['title']) && !empty($my_account['title']))
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
<hr>
</div>