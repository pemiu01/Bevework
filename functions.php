<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
* 
* WordPress 免费博客主题 Bevework，请勿用于商业行为，谢谢！
* 本主题设计借鉴了http://www.anzhuo.cn/，特此感谢！
* 二次开发建议：建议个人添加代码从custom 文件夹下custom.php 添加。若有bug 或更好的想法欢迎提出！
* @since 1.0.0
* @copyright Jeff  http://DeveWork.com
*/

/*__________________________________________定义全局变量________________________________________*/



/*__________________________________________基本WordPress theme架构代码________________________________________*/

/**
 * 导航菜单
 * @version 1.0.0
 * @author WordPress
 *
 */
register_nav_menus(array(
      'menu-primary' => 'Bevework顶部导航菜单',
    ));

/**
 * 侧边栏
 * @version 1.0.0
 * @author WordPress
 *
 */
if ( function_exists('register_sidebar') ) {
    register_sidebar(array(
        'name' => 'Bevework 侧边栏',
        'id' => 'dw_side_bar',
        'description' => 'Bevework 侧边栏',
        'before_widget' => '<ul class="widget-container"><li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li></ul>',
        'before_title' => '<h3 class="widgettitle"><span></span>',
        'after_title' => '</h3>'
    ));
    }

/*______________________________________________常见基本函数___________________________________________________*/

/**
 * 删除头部亢余代码
 *
 * @version 1.0.0
 * @author wp
 *
 */
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rel_canonical' );
remove_action( 'wp_head', 'index_rel_link' );
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'feed_links_extra', 3);// 额外的feed,例如category, tag页

/**
 * 删除多余的小工具，搜索，日历
 *
 * @version 1.0.0
 * @author wp
 *
 */
function dw_unregister_widget(){
unregister_widget('WP_Widget_Search');
unregister_widget('WP_Widget_Calendar');
}
add_action('widgets_init','dw_unregister_widget');

/**
 * title 自定义
 *
 * @version 1.0.0
 * @author Jeff ~ DeveWork.com
 * @internal 用于浏览器的标题
 *
 */
function get_page_number() {
    if ( get_query_var('paged') ) {print ' | ' . '第'. get_query_var('paged') . '页';}}
function dw_meta_title(){
        if ( is_single() ) { 
            single_post_title(); echo ' | '; bloginfo( 'name' );
        } elseif ( is_home() || is_front_page() ) {
            bloginfo( 'name' );
            if( get_bloginfo( 'description' ) ) {
              echo ' | ' ; bloginfo( 'description' ); get_page_number();
            }
        } elseif ( is_page() ) {
            single_post_title( '' ); echo ' | '; bloginfo( 'name' );
        } elseif ( is_search() ) {
            printf( __( '有关 %s 的搜索结果：', 'Geekwork' ), '"'.get_search_query().'"' ); get_page_number(); echo ' | '; bloginfo( 'name' );
        } elseif ( is_404() ) { 
            _e( '404 Not Found', 'Geekwork' ); echo ' | '; bloginfo( 'name' );
        } else { 
            wp_title( '' ); echo ' | '; bloginfo( 'name' ); get_page_number();
        }
    }

/**
 * 分页导航代码
 *
 * @version 1.0.0
 * @author wp
 *
 */
function dw_par_pagenavi($range = 9){
    global $paged, $wp_query;
    if ( !$max_page ) {$max_page = $wp_query->max_num_pages;}
    if($max_page > 1){if(!$paged){$paged = 1;}
    if($paged != 1){echo "<a href='" . get_pagenum_link(1) . "' class='extend' title='跳转到首页'> 返回首页 </a>";}
    previous_posts_link(' 上一页 ');
    if($max_page > $range){
        if($paged < $range){for($i = 1; $i <= ($range + 1); $i++){echo "<a href='" . get_pagenum_link($i) ."'";
        if($i==$paged)echo " class='current'";echo ">$i</a>";}}
    elseif($paged >= ($max_page - ceil(($range/2)))){
        for($i = $max_page - $range; $i <= $max_page; $i++){echo "<a href='" . get_pagenum_link($i) ."'";
        if($i==$paged)echo " class='current'";echo ">$i</a>";}}
    elseif($paged >= $range && $paged < ($max_page - ceil(($range/2)))){
        for($i = ($paged - ceil($range/2)); $i <= ($paged + ceil(($range/2))); $i++){echo "<a href='" . get_pagenum_link($i) ."'";if($i==$paged) echo " class='current'";echo ">$i</a>";}}}
    else{for($i = 1; $i <= $max_page; $i++){echo "<a href='" . get_pagenum_link($i) ."'";
    if($i==$paged)echo " class='current'";echo ">$i</a>";}}
    if ( $paged < $max_page - $p - 1 ) echo '... ';
    next_posts_link(' 下一页 ');
    if($paged != $max_page){echo "<a href='" . get_pagenum_link($max_page) . "' class='extend' title='跳转到最后一页'> 最后一页 </a>";}}
} 

/**
 * 浏览次数函数设置
 *
 * @version 1.0.0
 * @author wp
 * @internal 要使用在loop 中方可正确运行，数据通过post-meta保存在数据库中
 *
 */
function getPostViews($postID){   
     $count_key = 'post_views_count';   
     $count = get_post_meta($postID, $count_key, true);   
     if($count==''){   
         delete_post_meta($postID, $count_key);   
         add_post_meta($postID, $count_key, '0');   
         return "0";   
     }   
     return $count;   
 }   
 function setPostViews($postID) {   
     $count_key = 'post_views_count';   
     $count = get_post_meta($postID, $count_key, true);   
     if($count==''){   
         $count = 0;   
         delete_post_meta($postID, $count_key);   
         add_post_meta($postID, $count_key, '0');   
     }else{   
         $count++;   
         update_post_meta($postID, $count_key, $count);   
     }   
 } 



 ?>