<table class="table table-bordered">
  <tr>
    <th> title:<a href="<?php echo $this->escape($entry['link']);?>" ><?php echo $this->escape($entry['title']);?></a></th>
  </tr>
  <tr>
    <th>
      投稿日時：<?php echo $this->escape($entry['created_at']);?>
        <form action= "<?php echo $base_url;?>/rss/change" method = "post">
        <input type="hidden" name="entry_id" value="<?php echo $this->escape($entry['id']);?>" id="readflag">
        <input type="submit" id="addbtn" value="既読"><br>
        <a href="<?php echo $this->escape($entry['link']);?>" target="_blank">続きは...</a>
        </form>
        <?php //echo $entry['content'];?>
    </th>
  </tr>
</table>



<!-- <div class="accordion" id="accordion2">
  <div class="accordion-group" id="contentfeed">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
        title:<?php echo $this->escape($entry['title']);?>
      </a>
    </div>
    <div id="collapseOne" class="accordion-body collapse in">
      <div class="accordion-inner ">
        投稿日時：<?php echo $this->escape($entry['created_at']);?>
        <form action= "<?php echo $base_url;?>/rss/change" method = "post">
        <input type="hidden" name="entry_id" value="<?php echo $this->escape($entry['id']);?>" id="readflag">
        <input type="submit" id="addbtn" value="既読">
        </form>
         <!-- <input class="pull-right　btn btn-mini　" type="button" value="既読にする"　id="readflag" data-id="<?php echo $this->escape($entry['id']);?>"> -->
         <?php //echo $entry['content'];?>
  <!--     </div>
    </div>
  </div>
</div> -->

