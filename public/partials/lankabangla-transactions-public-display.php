<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://selise.ch/
 * @since      1.0.0
 *
 * @package    Lankabangla_Transactions
 * @subpackage Lankabangla_Transactions/public/partials
 */
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="transaction_wrapper">
    <div class="container_wrap">
        <div class="inner_row"> 
          <div class="mixitup_inner">
            <div class="controls">
            <div class="lnk_button_inner">
               <button type="button" class="control mixitup-control-active" data-filter="all">All</button> 
              <?php
              $cat_args=array( 
                  'taxonomy' => 'transactions_category',
                  'hierarchical' => 0, 
                  'orderby' => 'term_id',
                  'order'  => 'ASC'

              );
              $categories = get_categories($cat_args);
              foreach ($categories as $key=>$category) {
                ?>
                  <button type="button" class="control" data-filter=".<?php echo $category->slug; ?>"><?php echo $category->name; ?></button>
                <?php
              }
              ?> 
              </div>
            <div class="filter_panel">
                <div class="lnkb_mobile_services">Select Services</div>
                <div class="filter_text pull-right">Filter by: <strong  class="open_modal">Year/Sector</strong></div>
              
                
            </div> 
        
                <?php 
                //pagination total count
                    $args = array(      
                        'post_type' => 'transaction',
                        'post_status' => 'publish',  
                        'order' => 'ASC', 
                        'posts_per_page' => 300, 
                    ); 
                    $post = new WP_Query($args); 
                    $post_count = $post->post_count + 1;
                    //pagination 1 post hide
                       
                    global $wpdb;
                    $posts_per_page = 15; 
                    if ( is_front_page()) {
                        $posts_per_page = 9; 
                    } 
                    
                ?>
                <!--spinner open -->
                     <div class="align-items-center spinner">   
                        <div class="spinner-border ml-auto" role="status" aria-hidden="true"></div>
                      </div>
                    <!--spinner close -->
                <div class="containers lnkb_mix_seletor container" data-ref="containers"> 
                        <div class="data-container">  
                        <!--data-container is for data when loaded with pagination-->
                        </div>
                     
                </div>
                <div id="pagination-list" class="pagination"></div> 
                <!--pagination Elements close-->
            
            </div><!-- mixitup inner-->
    </div><!-- row-->
</div><!-- container--> 
 
</div>




<!-- Modal Html-->
<div class="modal" id="year_sectors_modal" >
  <div class="modal-dialog modal-lg">
    <form method="post" id="FormId">
      <div class="modal-content">
        <button type="button" class="close text-right modal_close_btn" data-dismiss="modal" aria-hidden="true">×</button> 

        <div class="search_list_inner fist"> 

          <div class="alert alert-danger select_alert" role="alert">
            Please select at least one years/sectors
          </div>
          <h3>Year</h3> 

          <div class="InputGroup years"> 
            <?php
            $trans_years_args =array( 
              'taxonomy' => 'transactions_years',
              'hierarchical' => 0, 
              'orderby' => 'term_id',
              'order'  => 'DESC'
            );
            $trans_years_query = get_categories($trans_years_args);
            foreach ($trans_years_query as $key=>$years) { ?>                 
              <input type="radio" name="years_selector" id="<?php echo $years->slug; ?>" value="<?php echo $years->slug; ?>">
              <label for="<?php echo $years->slug; ?>"><?php echo $years->name; ?></label> 
              <?php
            }
            ?>   
          </div>  
        </div>


        <div class="search_list_inner last">
          <h3>Sectors</h3>
          <div class="InputGroup sectors"> 
            <?php
            $trans_sectors_args =array( 
              'taxonomy' => 'transactions_sectors',
              'hierarchical' => 0, 
              'orderby' => 'term_id',
              'order'  => 'ASC'
            );
            $trans_sectors_query = get_categories($trans_sectors_args);
            foreach ($trans_sectors_query as $key=>$sectors) {?>                  
              <input type="radio" name="sectors_selector" id="<?php echo $sectors->slug; ?>" value="<?php echo $sectors->slug; ?>">
              <label for="<?php echo $sectors->slug; ?>"><?php echo $sectors->name; ?></label> 
            <?php } ?>
          </div>

        </div>

        <div class="btn_area">
          <div class="align-items-center spinner">             
            <div class="spinner-border ml-auto" role="status" aria-hidden="true"></div>
          </div>
          <input type="submit" name="submit_btn" class="submit_btn btn btn-secondary" value="Submit">
          <input type="Reset" class="btn btn-primary" name="Reset_btn" value="Reset">
        </div>

      </div>
    </form>
  </div>
</div>

<?php
 //$query = $_SERVER['QUERY_STRING']; 
?>
 
<!--script-->
<script type="text/javascript">
(function( $ ) {
  'use strict';
     
     //onload data
 categoryWisePaginationFunc(<?php echo $post_count; ?>);
 //pagination library hard code
 function categoryWisePaginationFunc(post_count){
    
    var container = jQuery('#pagination-list');
        var sources = function () {
        var result = [];  
        for (var i = 1; i < post_count ; i++) {
        result.push(i);
        } 
        return result;
    }();

    var options = { 
        pageSize: '<?php echo $posts_per_page; ?>', 
        prevText: 'Previous', // add image for prev button
        nextText: 'Next', // add image next button
        dataSource: sources,
            callback: function (response, pagination) { 
            
            var page = jQuery('.active').attr('data-num'); 
            var active_cat = jQuery('.mixitup-control-active').attr('data-filter');  
           
            
            var cat = active_cat.split(".");  
            var active_category = cat[1]; 
            //console.log(post_count); 
            
            
            if(active_cat == 'all'){
                var active_category = 'all';       
            }else{
                var active_category = cat[1];      
            }
            
           /* var active_category = '<?php echo $query; ?>'; 
            if(active_category == ''){
                var active_category = 'all';      
            } */
           

            jQuery.ajax({ 
                url: search_ajax.ajax_url,   
                type: "POST", 
                data: {
                    action:"pagination_ajax_action",
                    activeCategory: active_category,
                    page: page, 
                    posts_per_page: '<?php echo $posts_per_page; ?>'
                },
                beforeSend: function(){ 
                  //jQuery(".spinner").show();  
                },
                success: function(response){ 
                     jQuery(".spinner").hide(); 

                    //pagination data onload 
                    container.prev().html(response); 
                },error: function(errorThrown){
                    //console.log(errorThrown);
                } 
            });

        }
    };

    //jQuery.pagination(container, options); 

    container.addHook('beforeInit', function () {
     
      //window.console && console.log('beforeInit...');
    });
    container.pagination(options);

    container.addHook('beforePageOnClick', function () {
      
      //window.console && console.log('beforePageOnClick...');
      
      //return false
    });
 
}

 
//when clicking button search others data hide
jQuery('.controls button').on('click',function(){   
    
    jQuery(".transaction_wrapper").css("min-height", "inherit");//for team click other pages
    
    jQuery('html, body').animate({
    scrollTop: jQuery(".transaction_wrapper").offset().top - 105
    }, 1000); 
    
    jQuery("#pagination-list").show(); //when filter its hide so now show
    
    jQuery('.controls button').removeClass('mixitup-control-active');
    jQuery(this).addClass('mixitup-control-active');
    
    //ajax start
    var page = jQuery('.active').attr('data-num');  
    var active_cat = jQuery('.mixitup-control-active').attr('data-filter'); 

    var cat = active_cat.split(".");  
    var active_category = cat[1];         

    if(active_cat == 'all'){
        var active_category = 'all';       
    }else{
        var active_category = cat[1];      
    }
    var active_name = jQuery('.mixitup-control-active').text(); 
    jQuery('.lnkb_mobile_services').text(active_name); 
    jQuery("strong.open_modal").text('Year/Sector'); 


    jQuery.ajax({ 
        url: search_ajax.ajax_url,   
        type: "POST", 
        data: {
            action:"post_count_ajax_action",
            activeCategory: active_category,
            page: page, 
        }, 
        success: function(response){   
             // console.log(response); 
             //for pagination  
             var response = parseInt(response) + parseInt(1);
          
             categoryWisePaginationFunc(response);//paginate data func
        } 
    });//ajax close 
     
}); //function close


//from sector hyper link
 jQuery(".lnkb-post-sectors-list").on('click',function(){   
    
    jQuery(".transaction_wrapper").css("min-height", "inherit");//for team click other pages
     
    jQuery('html, body').animate({
    scrollTop: jQuery(".transaction_wrapper").offset().top - 205
    }, 2000); 
    
    jQuery('.controls button').removeClass('mixitup-control-active');
    jQuery('.lnk_button_inner button').first().addClass('mixitup-control-active');
    
    var sectors_selector = jQuery(this).attr('data-attr');
    var years_selector = '';
    //  alert(sectors_selector);
     
    jQuery.ajax({ 
        url: search_ajax.ajax_url, //did not same function wordpress such as ajax_url
        type: "GET", 
        data: {
          action:"selection_ajax_function_callback",
          yearsSelector: years_selector,
          sectorsSelector: sectors_selector
        },
        beforeSend: function(){ 
          //jQuery(".spinner").show(); 
        },success: function(response){
          //  alert(response);
          // console.log(response);
          jQuery(".spinner").hide(); 
          jQuery("#pagination-list").hide(); 
          //jQuery(".mix").hide();
          //jQuery(".post_inner_search").hide();
          jQuery("#year_sectors_modal .close").click();
          jQuery("#results").html(response); 
        }
      });
     
 });
 
 
 
 //form
 jQuery("#FormId").submit(function(event){
        event.preventDefault(); 
        jQuery(".transaction_wrapper").css("min-height", "inherit");//for team click other pages
        var years_selector = jQuery(".InputGroup input[name='years_selector']:checked").val();
       // alert(years_selector);
        var sectors_selector = jQuery(".InputGroup input[name='sectors_selector']:checked").val();
        
       //push year/sectors in level 
        var slash = '';
        if((typeof years_selector != "undefined") && (typeof sectors_selector != "undefined")) {
            var slash = ' / ';
        } 
        var split_name = years_selector+slash+sectors_selector;
        var selector_name_part = split_name.split("undefined"); 
        if( typeof years_selector === "undefined") {
          var selector_name_part = selector_name_part[1];
        }else{
           var selector_name_part = selector_name_part[0];  
        } 
        jQuery("strong.open_modal").text(selector_name_part.replace('-',' ')); 
          
          
          
        jQuery("strong.open_modal").removeClass('fmcg');
        jQuery("strong.open_modal").removeClass('nttn');
        if(sectors_selector == 'nttn' || sectors_selector == 'fmcg'){
           
            jQuery("strong.open_modal").addClass(sectors_selector);
        }
        if(sectors_selector == 'rmg-textile'){
             jQuery("strong.open_modal").text('RMG & textile');
        }
        
       //push year/sectors in level close
        
        if((typeof years_selector === "undefined") && ( typeof sectors_selector === "undefined")) {
            jQuery(".select_alert").show();
            jQuery("strong.open_modal").text('Year/Sector'); 
            
        }else{
          jQuery.ajax({ 
            url: search_ajax.ajax_url, //did not same function wordpress such as ajax_url
            type: "POST", 
            data: {
              action:"selection_ajax_function_callback",
              yearsSelector: years_selector,
              sectorsSelector: sectors_selector
            },
            beforeSend: function(){ 
              jQuery(".spinner").show(); 
            },success: function(response){
                jQuery('.controls button').removeClass('mixitup-control-active');
                jQuery('.lnk_button_inner button').first().addClass('mixitup-control-active');
    
              jQuery(".select_alert").show().fadeIn(300).fadeOut(300);
              //console.log(response);
              jQuery(".spinner").hide(); 
              jQuery("#pagination-list").hide(); 
              //jQuery(".mix").hide();
              //jQuery(".post_inner_search").hide();
              jQuery("#year_sectors_modal .close").click();
              jQuery("#results").html(response); 
            }
          });
        }
    });  
  //custom open modal
  jQuery('.open_modal').click(function(){  
      jQuery("#year_sectors_modal").modal('show');
  });
   

})( jQuery );
</script>