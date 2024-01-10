<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://selise.ch/
 * @since      1.0.0
 *
 * @package    Lankabangla_Transactions
 * @subpackage Lankabangla_Transactions/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Lankabangla_Transactions
 * @subpackage Lankabangla_Transactions/public
 * @author     Selise Team (ITSM) <rabiul.islam@selise.ch>
 */
class Lankabangla_Transactions_Public {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Lankabangla_Transactions_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Lankabangla_Transactions_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/lankabangla-transactions-public.css', array(), $this->version, 'all' ); 

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Lankabangla_Transactions_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Lankabangla_Transactions_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		//mixitup plugin
		//wp_register_script( 'mixitup_main_js', plugin_dir_url( __FILE__ ) . 'js/mixitup.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script('mixitup_main_js');
		
		wp_register_script('lankabangla-script', plugin_dir_url( __FILE__ ) . 'js/lankabangla-transactions-public.js', array( 'jquery' ), $this->version, false );
		
		wp_enqueue_script('lankabangla-script');
       	
       	wp_localize_script('lankabangla-script', 'search_ajax', array('ajax_url' => admin_url('admin-ajax.php')));
       	
       	//pagination
       	wp_register_script( 'pagination_js', plugin_dir_url( __FILE__ ) . 'js/pagination.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script('pagination_js');
		
	} 
	//ajax data
	public function selection_ajax_function_callback() {   
		//echo 'search query'; 
		$yearsSelector =  $_REQUEST['yearsSelector'];
		$sectorsSelector = $_REQUEST['sectorsSelector'];
		$relation ='OR';
		if((!empty($sectorsSelector)) AND (!empty($yearsSelector))){
		    $relation = 'AND';
		}

		$args = array(
			'post_type' => 'transaction',
			'posts_per_page'=> 300, 
			'tax_query' => array(
				'relation' => $relation,
				array(
					'taxonomy' => 'transactions_sectors',
					'field'    => 'slug',
					'terms'    => $sectorsSelector,
				),
				array(
					'taxonomy' => 'transactions_years',
					'field'    => 'slug',
					'terms'    => $yearsSelector,
				),
			),
		);

		$search_query = new WP_Query( $args); 

		if( $search_query->have_posts() ) {

			$ii=1;
			while( $search_query->have_posts() ) {
				$search_query->the_post();

				$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
                  $imgUrl = '';
                  if(isset($image) && !empty($image)){
                    $imgUrl = $image;
                  }
				
				$terms = wp_get_object_terms( get_the_ID(), 'transactions_category');

				foreach($terms as $cat) {
					$cat_slug_by_post_id = $cat->slug;
					$cat_name = $cat->name;
				} 
				 //after 3 post border will be shown here
                  if($ii % 3 === 1) {
                    echo '<div class="lnkb_transaction_border"></div>';
                  }
				?>
				<div id="post_inner" class="post_inner_filter col-md-4">
					<img src="<?php echo $image[0]; ?>" alt="<?php the_title();?>">
					<div class="post_details">  
					<a href="javascript:void(0);" title="<?php the_title();?>"><?php the_title(); ?></a>
					<p class="transaction_cat_name"><?php echo $cat_name; ?></p>
					<p><?php the_content();?></p>
					
					<p class="lnk-excerpt">
					<?php 
					 $price_numeric = lnkb_kses(get_the_excerpt()); 
					 if(has_excerpt()){ 
			         ?>
			         <span>Deal Size:</span> 
					 <?php
					   $price = str_replace(',', '', $price_numeric);
					   if(is_numeric($price) === true OR is_float($price) === true OR is_int($price) === true){
					         echo 'BDT ';  
					   }  
					      
				       echo $price_numeric; 
				       if(is_numeric($price) === true OR is_float($price) === true OR is_int($price) === true){
				         echo ' Mn';  
				       } 
					   }//has_excerpt check
					    ?>  
					</p>
				 
					<?php if($yearsSelector =='ongoing') { ?><p class="transaction_ongoing"><?php //echo $yearsSelector; ?></p><?php }?>
					</div>
				</div> 
				<?php
		$ii++;	}
		
	}else{
   echo 'No data found..';
} 
	 
		die(); 
	}
	public function transactions_shortcode_method_init() {
		add_shortcode('transactions_shortcode', 'transactions_shortcode_func');
		function transactions_shortcode_func() { 
			ob_start();
				require plugin_dir_path( __FILE__ ) . 'partials/lankabangla-transactions-public-display.php';
			return ob_get_clean();
		}
	}
	


//pagination ajax function
//ajax data
public function post_count_ajax_function_callback(){   
 
   $activeCategory =  $_REQUEST['activeCategory']; 
   $page = $_REQUEST['page'];  

 //for pagination
  if($activeCategory == 'all'){
   $total_post_args = array(
      'post_type' => 'transaction', 
      'posts_per_page' => 300
   );
  }else{ 
   $total_post_args = array(
      'post_type' => 'transaction',
      'posts_per_page' => 300,
      'tax_query' => array(
         'relation' => 'OR',
         array(
            'taxonomy' => 'transactions_category',
            'field'    => 'slug',
            'terms'    => $activeCategory,
         )
      ),
   );
  } 

$total_post_args_query = new WP_Query( $total_post_args);
   $post_count =  $total_post_args_query->post_count;  
   //$per_page =  ceil( $post_count / 3); 
   echo $post_count; 
   die();
}


//onload data for transaction ajax data

public function pagination_ajax_function_callback(){   
    global $wpdb;
   //$posts_per_page = 15;  
   $posts_per_page =  $_REQUEST['posts_per_page']; 
   $activeCategory =  $_REQUEST['activeCategory']; 
   $page = $_REQUEST['page'];   

  //for data
   if($activeCategory == 'all'){
   $args = array(
      'post_type' => 'transaction',
      'posts_per_page' => $posts_per_page,
      'paged' => $page, 
   );
  }else{ 
   $args = array(
      'post_type' => 'transaction',
       'posts_per_page' => $posts_per_page,
      'paged' => $page, 
      'tax_query' => array(
         'relation' => 'OR',
         array(
            'taxonomy' => 'transactions_category',
            'field'    => 'slug',
            'terms'    => $activeCategory,
         )
      ),
   );
   } 

$pagination_query = new WP_Query( $args);?>
<div class="row" id="results">
 <?php
		
if( $pagination_query->have_posts() ) {
   $i = 1;
   while( $pagination_query->have_posts() ) {
    
      $pagination_query->the_post();

     $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
                  $imgUrl = '';
      if(isset($image) && !empty($image)){
        $imgUrl = $image;
      }
      // echo $image[0];
      $terms = wp_get_object_terms( get_the_ID(), 'transactions_category');

      foreach($terms as $cat){ //print_r($cat);
         $cat_slug_by_post_id = $cat->slug;
         $cat_name = $cat->name;
      } 
      
      $transactions_years = wp_get_object_terms( get_the_ID(), 'transactions_years'); 
      foreach($transactions_years as $ongoing){ 
           $ongoing  = $ongoing->name;
      } 
      
      
      
      
      //after 3 post border will be shown here
      if($i % 3 === 1) {
        echo '<div class="lnkb_transaction_border"></div>';
      }
      ?>
      <div id="post_inner" class="post_inner_pagination col-md-4 animated zoomIn" data-settings="{"_animation":"zoomIn","_animation_delay":9}">
         <img src="<?php if(!empty($image[0])){ echo $image[0]; }else{ echo 'http://stage-lankabangla.selise.biz/wp-content/uploads/2022/09/noimage.png'; } ?>" alt="<?php echo $cat_name; ?>">
         <div class="post_details"> 
            
            <a href="javascript:void(0)" title="<?php the_title();?>"><?php the_title(); ?></a>
            <?php if($activeCategory =='all'){?> <p class="transaction_cat_name"><?php echo $cat_name; ?></p><?php } ?>
            <p><?php the_content(); ?></p>
            <p class="lnk-excerpt">
            <?php 
		    $price_numeric = lnkb_kses(get_the_excerpt()); 
		    if(has_excerpt()){ 
		     ?>
    		<span>Deal Size:</span> 
                <?php 
                $price_numeric = lnkb_kses(get_the_excerpt()); 
                $price = str_replace(',', '', $price_numeric);
                if(is_numeric($price) === true OR is_float($price) === true OR is_int($price) === true){
                    echo 'BDT ';  
                }  
                echo $price_numeric; 
                if(is_numeric($price) === true OR is_float($price) === true OR is_int($price) === true){
                echo ' Mn';  
                }
		     }//has_excerpt check
                ?>
            </p>
          <p class="transaction_ongoing"><?php if($ongoing == 'Ongoing') {  echo $ongoing; }  ?></p> 
         </div>
      </div> 

      <?php
      
      $i++;
    }
}else{
   echo 'No data found..';
} 
?>
</div>
<?php
die(); 
}


}