<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/components/connections_card/connections_card.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="connection-card" data-sender-id="<?= $connection['user_one_id'] ?>"
     data-recipient-id="<?= $connection['user_two_id'] ?>">
    <div class="connection_wrapper">
        <div class="connection-header">
            <img class="connection-image"
                 src="<?= htmlspecialchars($profile_image ?? 'https://w7.pngwing.com/pngs/584/113/png-transparent-pink-user-icon.png') ?>"
                 alt="<?= htmlspecialchars($sender_name . ' ' . $sender_surname) ?> profile">
            <div class="connection-details">
                <p class="connection-username"><?= htmlspecialchars($sender_name . ' ' . $sender_surname) ?></p>
                <p class="connection-action">
                    <?php if ($status === 'pending'): ?>
                        <?= htmlspecialchars($message ?? 'sent you a connection request') ?>
                    <?php else: ?>
                        You are now connected
                    <?php endif; ?>
                </p>
                <div>
                    <span class="connection-status" style="color: <?= $status === 'pending' ? 'orange' : 'green' ?>;">
                        <?= ucfirst(htmlspecialchars($status)) ?>
                    </span>
                    <span class="connection-time"><?= htmlspecialchars($created_at) ?></span>
                </div>
            </div>
        </div>
        <?php if ($status === 'pending'): ?>
        <div class="connection-buttons">
            <span class="accept-text" data-connection-id="<?= $connection_id ?>">Accept</span>
            <span class="decline-text" data-connection-id="<?= $connection_id ?>">Decline</span>
        </div>
        <?php else: ?>
        <div class="connection-actions">
            <div class="connection-menu">
                <i class="fas fa-ellipsis-v connection-menu-icon"></i>
                <div class="connection-menu-content">
                    <span class="remove-connection">Remove connection</span>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script src="/components/connections_card/connections_card.js"></script>
</body>
</html>