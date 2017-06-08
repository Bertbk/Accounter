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
  Template of the create an account page.
*/
?>
 
<!DOCTYPE html>

<html>
<head>
<title>Create an account</title>
<meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">

   <link href="<?php echo BASEURL.'/bootstrap/css/bootstrap.min.css'?>" rel="stylesheet">
   <link rel="stylesheet" type="text/css" href="<?php echo BASEURL.'/css/global.css'?>">
   </head>
   <body>

   <div id="content">
   <div class="container">
   <div class="row">
   <header>
   <?php include(__DIR__.'/header/header.php'); ?>
   </header>
   </div>
   <?php include(__DIR__.'/messages/messages.php');?>

   <h1>Create a new account</h1>

   <form method="post" action="<?php echo ACTIONPATH.'/accounts/new_account.php'?>">
   <fieldset>
   <legend class="sr-only">Create a new account</legend>
   <p><em>Fields with asterisk <span class="glyphicon glyphicon-asterisk red"></span> are required</em></p>
   <input type="hidden" name="p_token" value="<?php echo $token?>">
   <div class="row">
   <div class="col-xs-12 col-sm-6 col-lg-4">
   <div class="form-group">
   <label for="form_title_account">Title<span class="glyphicon glyphicon-asterisk red"></span></label>
   <div class="input-group">
   <input type="text" name="p_title_of_account" id="form_title_account" required
   class="form-control" placeholder="Title" title="Title">
   <span class="input-group-addon glyphicon glyphicon-bookmark"></span>
   </div>
   </div>
   </div>
   <div class="col-xs-12 col-sm-6 col-lg-4">
   <div class="form-group">
   <label for="form_author">Author<span class="glyphicon glyphicon-asterisk red"></span></label>
   <div class="input-group">
   <input type="text" name="p_author" id="form_author" required class="form-control"
   placeholder="Author" title="Author">
   <span class="input-group-addon glyphicon glyphicon-user"></span>
   </div>
   </div>
   </div>
   <div class="col-xs-12 col-sm-6 col-lg-4">
   <div class="form-group">
   <label for="form_email">Email address</label>
   <div class="input-group">
   <input type="email" name="p_contact_email" id="form_email" class="form-control"
   placeholder="Email address" title="Email address">
   <span class="input-group-addon glyphicon glyphicon-envelope"></span>
   </div>
   </div>
   </div>
   <div class="col-xs-12 col-sm-6">
   <div class="form-group">
   <label for="form_description">Description</label>
   <div class="input-group">
   <textarea name="p_description" id="form_description" class="form-control" 
   placeholder="Description" title="Description"></textarea>
   <span class="input-group-addon glyphicon glyphicon-tag"></span>
   </div>
   </div>
   </div>
   </div>
   <div class="row">
   <div class="col-xs-12">
   <div class="form-group">
   <label for="form_captcha">How makes two plus 12 minus 2?</label>
   <div class="input-group">
   <input type="number" name="p_captcha" value="0"
   class="form-control" title="Captcha" id="form_captcha">
   </div>
   </div>
   </div>
   </div>
   <div class="row">
   <div class="col-xs-12">
   <button type="submit" name="submit_new_account" value="Submit"
   class="btn btn-primary" title="Submit new account">
   Submit
   </button> 
   </div>
   </div>
   </fieldset>
   </form>
   </div>
   </div> <!-- content -->
   </body>
   </html>