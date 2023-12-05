<?php
/**
 * The sidebar containing the main widget area
 *
 * @package WordPress
 * @subpackage Besa
 * @since Besa 1.2.9
 */


$sidebar = tbay_get_sidebar_dokan();

if(!isset($sidebar['id']) || empty($sidebar['id'])) return;

?> <div class="tbay-sidebar-vendor sidebar"><?php dynamic_sidebar( $sidebar['id'] ); ?></div>

