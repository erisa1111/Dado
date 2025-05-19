
<div class="recommend">
                        <h2>Add to your feed</h2>

                        <?php foreach ($suggestedUsers as $user): ?>
                            <div class="recommendation">
                                <div class="logo">
                                    <img 
                                        src="/<?= htmlspecialchars($user['profile_picture'] ?? 'assets/img/default_profile.webp') ?>" 
                                        alt="<?= htmlspecialchars($user['name'] ?? $user['username']) ?>" 
                                        style="object-fit: cover; width: 48px;"
                                    />
                                </div>
                                <div class="rec">
                                    <div class="info">
                                        <h3><a href="profile.php?user_id=<?= $user['id'] ?>" style="color: black; text-decoration:none;">
                                            <?= htmlspecialchars($user['name'] . ' ' . $user['surname']) ?>
                                        </a></h3>
                                        <p>
                                            <?php
                                                if ($user['role_name'] === 'Nanny') {
                                                    echo "Experienced caregiver";
                                                } else {
                                                    echo "Looking for a caring nanny";
                                                }
                                            ?>
                                        </p>
                                    </div>
                                    <!-- <button class="follow-btn" data-user-id="<?= $user['id'] ?>">+ Follow</button> -->
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>