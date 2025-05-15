<?php
// Public/components/notifications_card/notifications_card.php
// This component expects a $notification array with keys:
// type, post_id, post_title, created_at, commenter_username/comment (for comments), liker_username (for likes)
?>
<div class="notification-card">
    <div class="notification-header">
        <img class="notification-image" 
             src="<?= ($notification['type'] === 'comment') 
                    ? '/assets/img/comment_icon.png' 
                    : '/assets/img/like_icon.png' ?>" 
             alt="<?= htmlspecialchars($notification['type']) ?> icon">
        <div class="notification-details">
            <p class="notification-username">
                <?= htmlspecialchars(
                    $notification['type'] === 'comment'
                        ? $notification['commenter_username']
                        : $notification['liker_username']
                ) ?>
            </p>
            <p class="notification-action">
                <?= $notification['type'] === 'comment'
                    ? 'commented on your post'
                    : 'liked your post'
                ?> "<strong><?= htmlspecialchars($notification['post_title']) ?></strong>"
            </p>
            <span class="notification-time"><?= htmlspecialchars($notification['created_at']) ?></span>
        </div>
    </div>

    <?php if ($notification['type'] === 'comment'): ?>
    <div class="notification-preview">
        <p class="notification-preview-text">
            <?= htmlspecialchars(mb_strimwidth($notification['comment'], 0, 100, '…')) ?>
        </p>
    </div>
    <?php endif; ?>
</div>