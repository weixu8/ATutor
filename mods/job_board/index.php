<?php
/***********************************************************************/
/* ATutor															   */
/***********************************************************************/
/* Copyright (c) 2002-2009											   */
/* Adaptive Technology Resource Centre / Inclusive Design Institute	   */
/* http://atutor.ca													   */
/*																	   */
/* This program is free software. You can redistribute it and/or	   */
/* modify it under the terms of the GNU General Public License		   */
/* as published by the Free Software Foundation.					   */
/***********************************************************************/
// $Id$

define(AT_INCLUDE_PATH, '../../include/');
include(AT_INCLUDE_PATH.'vitals.inc.php');
include(AT_JB_INCLUDE.'classes/Job.class.php');
include(AT_JB_INCLUDE.'classes/Employer.class.php');
$_custom_css = $_base_path . AT_JB_BASENAME . 'module.css'; // use a custom stylesheet

$job = new Job();
$page = intval($_GET['p']);
$page = ($page==0)?1:$page;
$all_job_posts = $job->getAllJobs();

//handle search
if (isset($_GET['jb_submit'])){
	$search_input['general'] = trim($_GET['jb_search_general']);
	$search_input['title'] = trim($_GET['jb_search_title']);
//	$search_input['email'] = $_GET['jb_search_email'];
	$search_input['description'] = trim($_GET['jb_search_description']);
	$search_input['categories'] = $_GET['jb_search_categories'];
	$all_job_posts = $job->search($search_input);
}

if ($page > 0){
	$offset = ($page - 1) * AT_JB_ROWS_PER_PAGE;
} else {
	$offset = 0;
}
$current_job_posts = array_slice($all_job_posts, $offset, AT_JB_ROWS_PER_PAGE);

include(AT_INCLUDE_PATH.'header.inc.php');?>
<form action="" method="get">
	<div class="jb_head">
		<div class="jb_search">					
				<label for="jb_search_general"><?php echo _AT('jb_search'); ?></label>
				<input type="text" id="jb_search_general" name="jb_search_general" value="" />
				<input type="submit" name="jb_submit" value="<?php echo _AT('search'); ?>" />
			<a onclick="toggleAdvanceSearch()"><?php echo _AT('jb_search_filter'); ?></a>
			<div class="jb_advance_search" style="display: none;">
			<?php 
				$savant->assign('job_obj', $job);
				$savant->display('jb_advance_search_table.tmpl.php');
			?>
			</div>
		</div>
		<div class="jb_add_posting">
			<?php if(isset($_SESSION['jb_employer_id']) && $_SESSION['jb_employer_id'] > 0): ?>
			<a href="<?php echo AT_JB_BASENAME;?>employer/home.php"><?php echo _AT('jb_home');?></a>
			<a href="<?php echo AT_JB_BASENAME;?>employer/logout.php"><?php echo _AT('jb_logout');?></a>
			<?php else: ?>
			<a href="<?php echo AT_JB_BASENAME;?>employer/login.php"><?php echo _AT('jb_login');?></a>
			<a href="<?php echo AT_JB_BASENAME;?>employer/registration.php"><?php echo _AT('jb_not_a_member');?></a>
			<?php endif; ?>
		</div>		
	</div>
</form>
<div style="clear:both;"></div>
<div>
<?php
print_paginator($page, sizeof($all_job_posts), $_SERVER['QUERY_STRING'], AT_JB_ROWS_PER_PAGE);
$savant->assign('job_posts', $current_job_posts);
$savant->assign('job_obj', $job);
$savant->display('jb_index.tmpl.php');
print_paginator($page, sizeof($all_job_posts), $_SERVER['QUERY_STRING'], AT_JB_ROWS_PER_PAGE);
?>
</div>

<script type="text/javascript" >
	function toggleAdvanceSearch(){
		var box_state = jQuery('.jb_advance_search').css('display');
		if (box_state == 'none'){
			jQuery('.jb_advance_search').css('display', 'block');
		} else {
			jQuery('.jb_advance_search').css('display', 'none');
		}
	}
</script>

<?php include(AT_INCLUDE_PATH.'footer.inc.php'); ?>