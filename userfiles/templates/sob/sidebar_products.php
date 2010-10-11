<?php dbg(__FILE__); ?>
<?php if(!$post): ?>
<?php $who = $this->core_model->getParamFromURL ( 'username' );  $who = $this->users_model->getIdByUsername($who); ?>
<?php $who = $this->users_model->getUserById($who); ?>
<?php else: ?>
<?php $who = $this->users_model->getUserById( $post ['created_by']); ?>
<?php endif; ?>
<?php if(empty($active_categories)){
		$active_categories =  $this->taxonomy_model->getMasterCategories(false);
		$active_categories = $this->core_model->dbExtractIdsFromArray($active_categories);
	 }

      $related = array();
	   if($use_master_categories_for_products  == false){
	  $related['selected_categories'] = $active_categories;
	   }
	  $related ['content_type'] = 'post';
	  $related ['content_subtype'] = 'products';
	 // $related ['is_special'] = 'y';
	 if(!empty($who)){
	  $related ['created_by'] = $who['id'];
	 }

	  $limit[0] = 0;
	  $limit[1] = 5;
	  $related = $this->content_model->getContentAndCache($related, false,$limit,  $count_only = false, $short = true, $only_fields = array ('id', 'content_type', 'content_url', 'content_title', 'content_description',  'content_body', 'created_by', 'is_special' ) );

	@shuffle($related);
	@array_slice($related , 0 , 5);
	  ?>
<?php if(!empty($related)): ?>
<br />
<?php if(!empty($who)): ?>
<h2 class="title" style="padding: 10px 0 0 0"><?php print $this->users_model->getPrintableName (  $who['id'], 'full' ); ?>'s Products</h2>
<?php else: ?>
<h2 class="title" style="padding: 10px 0 0 0">Products in The School</h2>
<?php endif; ?>

<ul class="profile-products profile-products-border" style="padding-top: 0">
  <?php foreach($related as $the_post): ?>

  <?php $more = false;
 $more = $this->core_model->getCustomFields('table_content', $the_post['id']);
	 if(!empty($more)){
		ksort($more);
	 }  ?>


  <?php $author = $this->users_model->getUserById($the_post['created_by']); ?>
  <?php $thumb = $this->content_model->contentGetThumbnailForContentId( $the_post['id'], 89, 89);  ?>
  <li>
  <a class="img" href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>">
    <img src="<?php print $thumb ?>" alt="" />
  </a>
    <div class="sidebar-list-side">
    <h3><a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>"><?php print (character_limiter($the_post['content_title'], 30, '...')); ?></a></h3>
    <p>
      <?php if($the_post['content_description'] != ''): ?>
      <?php print (character_limiter($the_post['content_description'], 50, '...')); ?>
      <?php else: ?>
      <?php print character_limiter($the_post['content_body_nohtml'], 50, '...'); ?>
      <?php endif; ?>
    </p>

        <?php if(($more['product_buy_link'])): ?>
    <a href="<?php print prep_url($more['product_buy_link']); ?>" target="_blank" class="order left">Order<?php if(($more['product_price'])): ?> for $<?php print floatval($more['product_price']); ?><?php endif; ?></a>
    <?php endif; ?>

    <a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>" class="product-more">Read more</a>

    </div>
    </li>
  <?php endforeach; ?>
</ul>
	<?php if(empty($who)): ?>
    <a href="<?php print site_url('browse/type:products'); ?>" class="btn right hmarg">See All Products</a>
    <?php else: ?>
    <a href="<?php print site_url('userbase/action:products/username:'.$who['username']); ?>" class="btn right hmarg"><?php print $this->users_model->getPrintableName (  $who['id'], 'first' ); ?>'s Products</a>
    <?php endif; ?>
<?php else: ?>
<?php if(!empty($who)): ?>
<!--<?php print $this->users_model->getPrintableName (  $who['id'], 'full' ); ?> doesn't have any products.-->
<?php endif; ?>
<?php endif;  ?>
<span class="c"></span> <br />
<?php dbg(__FILE__, 1); ?>
