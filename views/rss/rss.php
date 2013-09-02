<table class="table table-bordered">
    <tr>
      <th> title:<a href="<?php echo $this->escape($entry['link']);?>" ><?php echo $this->escape($entry['title']);?></a>
      <p class="pull-right">投稿日時：<?php echo $this->escape($entry['created_at']);?></p>
      </th>
    </tr>
    <tr>
      <th>
        <a href="<?php echo $this->escape($entry['link']);?>" target="_blank">続きは...</a>
                <form action= "<?php echo $base_url;?>/rss/change" method = "post">
        <input type="hidden" name="entry_id" value="<?php echo $this->escape($entry['id']);?>" id="readflag">
        <input class="btn pull-right"type="submit" value="既読">
        </form>
      </th>
    </tr>
</table>