<div class="alert alert-error">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <?php if (is_array($message)): ?>

        <?php foreach ($message as $field => $title): ?>

                <strong><?php echo $field ?></strong>
                <?php echo $title ?> <br>

        <?php endforeach ?>
    <?php else: ?>
        <?php echo $message ?>
    <?php endif; ?>
</div>