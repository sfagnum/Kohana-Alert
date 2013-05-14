<div class="alert alert-info">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <?php if (is_array($message)): ?>
        <?php foreach ($message as $title => $text): ?>
            <strong><?php echo $title ?></strong>
            <?php echo $text ?><br>
        <?php endforeach ?>
    <?php else: ?>
    <?php echo $message ?>
    <?php endif; ?>
</div>