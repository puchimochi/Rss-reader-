<div class="accordion" id="accordion2">
  <div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
        title:<?php echo $this->escape($entry['title']);?>
      </a>
    </div>
    <div id="collapseOne" class="accordion-body collapse in">
      <div class="accordion-inner">
        投稿日時：<?php echo $this->escape($entry['created_at']);?><br>
        <?php echo $entry['content'];?>
      </div>
    </div>
  </div>
</div>
