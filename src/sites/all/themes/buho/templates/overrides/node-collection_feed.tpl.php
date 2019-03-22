<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">
<?php $my_user_info=my_get_user_info($node);?>	
<?php if($page==0):?>
<div id="flagtitulo">
    <div class="f-titulo"><h2><?php print l(htmlkarakter($title),'node/'.$node->nid,array('query'=>drupal_get_destination()));?></h2></div>
</div>
<div style="clear:both;width:100%;">
  <div style="float:left;min-width:75px;">
  		<?php print $my_user_info['img'];?> 
  </div>
  
  <div id="i-contenedor">
	  <div><b><?php print date('d/m/Y H:i',$node->created); ?></b></div>
	  <div class="item-teaser-texto">
              <?php print social_learning_feeds_get_collection_feed_resumen($node); ?>
          </div>
          
          
          <div class="div_idea_list_personas">
                <label><b><?php print t('Resource Container id');?>:</b>&nbsp;</label>
                <div class="item-teaser-texto"><?php print social_learning_feeds_get_collection_feed_node_id($node);?></div>
          </div>
          
          <div class="div_idea_list_personas">
                <label><b><?php print t('Url');?>:</b>&nbsp;</label>
                <div class="item-teaser-texto"><?php print social_learning_feeds_get_collection_feed_url_html($node);?></div>
          </div>
          
          <div class="div_idea_list_personas">
                <label><b><?php print t('RSS');?>:</b>&nbsp;</label>
                <div class="item-teaser-texto"><?php print social_learning_feeds_get_collection_feed_rss_html($node);?></div>
          </div>
          
         

    
 </div> <!-- end i-contenedor -->         
    <div class="n-opciones-item">		      
      <div class="n-item-editar">
           <?php print social_learning_feeds_collection_feed_edit_link($node);?>
      </div>             
      <div class="n-item-borrar">
           <?php print social_learning_feeds_collection_feed_delete_link($node);?>                  	
      </div>  
      <div class="n-item-upload-rating">
          <?php print social_learning_feeds_collection_feed_upload_link($node);?>
      </div>        
    </div>  
</div>  	
<?php elseif($page==1):?>
<!--
<div id="flagtitulo">
	<div class="f-titulo"><h2><?php //print l(htmlkarakter($title),'node/'.$node->nid);?></h2></div>
</div>
-->

<?php //gemini?>
	
        <?php if(hontza_node_has_body($node)): ?>
	  <div class="item-full-texto">
              <?php print $node->content['body']['#value'] ?>
          </div>
	<?php endif; ?>
<div style="clear:both;width:100%;">
  <div class="contenedor_left">
              <div class="user_img_left">
                    <?php print $my_user_info['img'];?>
              </div>              
  </div>
  
  <div id="i-contenedor">
	<?php //if($node->body): ?>
	  <div><b><?php print date('d/m/Y H:i',$node->created); ?></b></div>
    <?php //endif; ?>
                  <div class="field field-type-text field-field-collection_item-resource-container-id" style="float:left;clear:both;">
			<div class="field-items">
				<div class="field-item odd">
					<div class="field-label-inline-first" style="float:left;">
					  <?php print t('Resource Container id');?>:&nbsp;
					</div>									
					<?php print social_learning_feeds_get_collection_feed_node_id($node);?>  
				</div>
			</div>
		  </div>
          
                  <div class="field field-type-text field-field-collection_item-resource-container-url" style="float:left;clear:both;">
			<div class="field-items">
				<div class="field-item odd">
					<div class="field-label-inline-first" style="float:left;">
					  <?php print t('Url');?>:&nbsp;
					</div>									
					<?php print social_learning_feeds_get_collection_feed_url_html($node);?>
				</div>
			</div>
		  </div>
                 
                  <div class="field field-type-text field-field-collection_item-resource-container-rss" style="float:left;clear:both;">
			<div class="field-items">
				<div class="field-item odd">
					<div class="field-label-inline-first" style="float:left;">
					  <?php print t('RSS');?>:&nbsp;
					</div>									
					<?php print social_learning_feeds_get_collection_feed_rss_html($node);?>
				</div>
			</div>
		  </div>
            
  </div>
  <?php if(hontza_is_con_botonera()):?>
    <div class="n-opciones-item">		      
      <div class="n-item-editar">
           <?php print social_learning_feeds_collection_feed_edit_link($node);?>
      </div>             
      <div class="n-item-borrar">
           <?php print social_learning_feeds_collection_feed_delete_link($node);?>                  	
      </div>  
      <div class="n-item-upload-rating">
          <?php print social_learning_feeds_collection_feed_upload_link($node);?>
      </div>        
    </div> 
  <?php endif;?>    
</div>  	
<?php endif;?>
</div>