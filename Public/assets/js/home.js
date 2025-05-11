initializeModal();




// Add this to your home.js
document.getElementById('post-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData();
    const content = document.getElementById('post-content').value;
    const images = document.getElementById('post-images').files;

    // Basic validation
    if (!content.trim() && images.length === 0) {
        alert('Please add content or an image');
        return;
    }

    formData.append('content', content);
    formData.append('title', 'New Post');

    // Append images
    for (let i = 0; i < images.length; i++) {
        formData.append('images[]', images[i]);
    }

    try {
        const response = await fetch('http://localhost:4000/views/create_post.php', {
            method: 'POST',
            body: formData,
        });
    
        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`Server error: ${response.status} - ${errorText}`);
        }
    
        const data = await response.json();
    
        if (!data.success) {
            throw new Error(data.message || "Post creation failed.");
        }
    
        toggleModalVisibility(false);
        location.reload();
    
    } catch (error) {
        console.error("Post Error:", error);
        alert(`Error: ${error.message}`);
    }
});  


// Add this function to your home.js
function toggleModalVisibility(show) {
    const modal = document.getElementById('post-modal');
    if (show) {
        modal.style.display = 'block';
    } else {
        modal.style.display = 'none';
    }
}

function initializeModal() {
    const addButton = document.getElementById('add');
    const closeButton = document.getElementById('close-modal');
    const modal = document.getElementById('post-modal');

    // Show modal when Add button is clicked
    addButton.addEventListener('click', () => {
        toggleModalVisibility(true);
    });

    // Hide modal when Close button is clicked
    closeButton.addEventListener('click', () => {
        toggleModalVisibility(false);
    });

    // Close modal when clicking outside
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            toggleModalVisibility(false);
        }
    });
}


document.addEventListener('DOMContentLoaded', function() {
    // Like button functionality
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            toggleLike(postId);
        });
    });

    
});
// Only load comments when explicitly requested (comment button clicked)
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.comment-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const postId = this.getAttribute('data-post-id');
            const commentsList = document.getElementById(`comments-list-${postId}`);
            
            // Toggle comments visibility
            if (commentsList.style.display === 'none' || !commentsList.style.display) {
                loadComments(postId);
                commentsList.style.display = 'block';
            } else {
                commentsList.style.display = 'none';
            }
        });
    });
});


async function toggleLike(postId) {
    try {
        const response = await fetch('like_post.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ post_id: postId })
        });

        // First check if response is OK
        if (!response.ok) {
            const errorData = await response.text();
            console.error('Server responded with:', errorData);
            throw new Error(`Server error: ${response.status}`);
        }

        // Then try to parse as JSON
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.message || 'Action failed');
        }

        // Update UI
        const postElement = document.getElementById(`post-${postId}`);
        if (postElement) {
            const likesCountElement = postElement.querySelector('.likes');
            if (likesCountElement) {
                likesCountElement.textContent = `${data.like_count} likes`;
            }
            
            const likeButton = postElement.querySelector('.like-btn i');
            if (likeButton) {
                likeButton.classList.toggle('fa-regular', !data.is_liked);
                likeButton.classList.toggle('fa-solid', data.is_liked);
                likeButton.style.color = data.is_liked ? 'red' : '';
            }
        }

    } catch (error) {
        console.error('Error in toggleLike:', error);
        // Show user-friendly error message
        alert('Failed to update like. Please try again.');
    }
}
/*
async function addComment(postId, commentText) {
    if (!commentText.trim()) return;
    
    const input = document.getElementById(`comment-${postId}`);
    input.disabled = true;
    
    try {
        const response = await fetch('/controllers/PostsController.php?action=addComment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
                post_id: postId,
                comment: commentText 
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Update comment count
            const countElement = document.querySelector(`#post-${postId} .comments`);
            if (countElement) {
                countElement.textContent = `${data.comment_count} comments`;
            }
            
            // Add new comment to list if visible
            const commentsList = document.getElementById(`comments-list-${postId}`);
            if (commentsList && commentsList.style.display !== 'none') {
                if (commentsList.querySelector('.no-comments')) {
                    commentsList.innerHTML = '';
                }
                commentsList.appendChild(createCommentElement(data.comment));
            }
            
            input.value = '';
        }
    } catch (error) {
        console.error('Error adding comment:', error);
    } finally {
        input.disabled = false;
    }
}

function createCommentElement(comment) {
    const commentDiv = document.createElement('div');
    commentDiv.className = 'comment';
    commentDiv.id = `comment-${comment.id}`;
    
    commentDiv.innerHTML = `
        <div class="comment-header">
            <img src="${comment.profile_picture || '/assets/img/default-profile.png'}" 
                 alt="${comment.username}" class="comment-profile-pic">
            <strong>${comment.username}</strong>
            <span class="comment-time">${formatCommentTime(comment.created_at)}</span>
            <div class="comment-actions">
                <button class="edit-comment" data-comment-id="${comment.id}">Edit</button>
                <button class="delete-comment" data-comment-id="${comment.id}">Delete</button>
            </div>
        </div>
        <div class="comment-body">${comment.body}</div>
    `;
    
    return commentDiv;
}

function formatCommentTime(timestamp) {
    const date = new Date(timestamp);
    return date.toLocaleString();
}

document.addEventListener('DOMContentLoaded', function() {
    // Only load comments when a post is clicked or expanded
    document.querySelectorAll('.post').forEach(post => {
        post.addEventListener('click', function(e) {
            // Check if click is on the post content (not a button/link)
            if (e.target.tagName === 'DIV' || e.target.classList.contains('post-content')) {
                const postId = this.id.split('-')[1];
                loadComments(postId);
            }
        });
    });
});

// Simplified comment loading
async function loadComments(postId) {
    const commentsList = document.getElementById(`comments-list-${postId}`);
    if (!commentsList) return;
    
    // Show loading state
    commentsList.innerHTML = '<div class="loading-comments">Loading comments...</div>';
    
    try {
        const response = await fetch(`/controllers/PostsController.php?action=getCommentsForPost&post_id=${postId}`);
        const data = await response.json();
        
        if (data.comments && data.comments.length > 0) {
            commentsList.innerHTML = '';
            data.comments.forEach(comment => {
                commentsList.appendChild(createCommentElement(comment));
            });
        } else {
            commentsList.innerHTML = '<div class="no-comments">No comments yet</div>';
        }
    } catch (error) {
        console.error(`Error loading comments:`, error);
        commentsList.innerHTML = '<div class="comments-error">Could not load comments</div>';
    }
}

async function handleDeleteComment(event) {
    const commentId = event.target.getAttribute('data-comment-id');
    if (!confirm('Are you sure you want to delete this comment?')) return;
    
    try {
        const response = await fetch('/controllers/PostsController.php?action=deleteComment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ comment_id: commentId })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Remove the comment from the UI
            document.getElementById(`comment-${commentId}`).remove();
            
            // Update comment count
            const commentElement = document.getElementById(`comment-${commentId}`);
            const postId = commentElement.closest('.post').id.split('-')[1];
            updateCommentCount(postId);
        } else {
            alert('Failed to delete comment: ' + data.message);
        }
    } catch (error) {
        console.error('Error deleting comment:', error);
        alert('An error occurred while deleting the comment');
    }
}

async function handleEditComment(event) {
    const commentId = event.target.getAttribute('data-comment-id');
    const commentElement = document.getElementById(`comment-${commentId}`);
    const commentBody = commentElement.querySelector('.comment-body');
    const currentText = commentBody.textContent;
    
    // Create an input field with the current text
    const input = document.createElement('textarea');
    input.value = currentText;
    commentBody.innerHTML = '';
    commentBody.appendChild(input);
    input.focus();
    
    // Add save and cancel buttons
    const saveButton = document.createElement('button');
    saveButton.textContent = 'Save';
    saveButton.className = 'save-edit';
    
    const cancelButton = document.createElement('button');
    cancelButton.textContent = 'Cancel';
    cancelButton.className = 'cancel-edit';
    
    const buttonContainer = document.createElement('div');
    buttonContainer.className = 'edit-buttons';
    buttonContainer.appendChild(saveButton);
    buttonContainer.appendChild(cancelButton);
    
    commentBody.appendChild(buttonContainer);
    
    // Handle save
    saveButton.addEventListener('click', async () => {
        const newText = input.value.trim();
        if (!newText) {
            alert('Comment cannot be empty');
            return;
        }
        
        try {
            const response = await fetch('/controllers/PostsController.php?action=updateComment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    comment_id: commentId,
                    new_comment: newText
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Update the comment in the UI
                commentBody.innerHTML = newText;
            } else {
                alert('Failed to update comment: ' + data.message);
                commentBody.innerHTML = currentText;
            }
        } catch (error) {
            console.error('Error updating comment:', error);
            alert('An error occurred while updating the comment');
            commentBody.innerHTML = currentText;
        }
    });
    
    // Handle cancel
    cancelButton.addEventListener('click', () => {
        commentBody.innerHTML = currentText;
    });
}

async function updateCommentCount(postId) {
    try {
        const response = await fetch(`/controllers/PostsController.php?action=getCommentCount&post_id=${postId}`);
        const data = await response.json();
        
        if (data.success) {
            const postElement = document.getElementById(`post-${postId}`);
            const commentsCountElement = postElement.querySelector('.comments');
            commentsCountElement.textContent = `${data.count} comments`;
        }
    } catch (error) {
        console.error('Error updating comment count:', error);
    }
}*/


/*
function loadComments(postId) {
    const commentsList = document.getElementById(`comments-list-${postId}`);
    if (!commentsList) return;
    
    // Show loading state
    commentsList.innerHTML = '<div class="loading-comments">Loading comments...</div>';
    
    fetch(`http://localhost:4000/views/get_comments.php?post_id=${postId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                commentsList.innerHTML = '';  // Clear existing comments

                if (data.comments && data.comments.length > 0) {
                    data.comments.forEach(comment => {
                        const div = document.createElement('div');
                        div.classList.add('comment');
                        div.innerHTML = `
                            <strong>${comment.name} ${comment.surname}</strong> (${comment.username}):<br>
                            ${comment.body} <br>
                            <small>Posted on ${comment.created_at}</small>
                        `;
                        commentsList.appendChild(div);
                    });
                } else {
                    commentsList.innerHTML = '<div class="no-comments">No comments yet</div>';
                }
            } else {
                console.error('Failed to load comments:', data.message);
                commentsList.innerHTML = '<div class="comments-error">Could not load comments</div>';
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            commentsList.innerHTML = '<div class="comments-error">Error loading comments</div>';
        });
}*/

document.addEventListener('DOMContentLoaded', function() {
    // Initialize comment functionality for all posts
    document.querySelectorAll('.post').forEach(post => {
        const postId = post.id.split('-')[1];
        initCommentFunctionality(postId);
    });
});

function initCommentFunctionality(postId) {
    // Comment button to toggle comments visibility
    const commentBtn = document.querySelector(`.comment-btn[data-post-id="${postId}"]`);
    const commentsList = document.getElementById(`comments-list-${postId}`);
    
   commentBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    console.log('Comment button clicked for post', postId);
    console.log('Current display state:', commentsList.style.display);
    
    if (commentsList.style.display === 'none' || !commentsList.style.display) {
        console.log('Loading comments...');
        loadComments(postId);
        commentsList.style.display = 'block';
        console.log('Comments should be visible now');
    } else {
        commentsList.style.display = 'none';
        console.log('Comments hidden');
    }
});

    // Comment submission - Enter key
    const commentInput = document.getElementById(`comment-${postId}`);
    commentInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            submitComment(postId);
        }
    });

    // Comment submission - Button click
    const submitBtn = document.querySelector(`#submit-comment[data-post-id="${postId}"]`);
    submitBtn.addEventListener('click', function() {
        submitComment(postId);
    });
}

async function submitComment(postId) {
    const commentInput = document.getElementById(`comment-${postId}`);
    const commentText = commentInput.value.trim();
    
    if (!commentText) return;
    
    // Disable input during submission
    commentInput.disabled = true;
    
    try {
        const response = await fetch('/views/comment_post.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
                post_id: postId,
                comment: commentText 
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Update comment count
            const commentsCount = document.querySelector(`#post-${postId} .comments`);
            if (commentsCount) {
                commentsCount.textContent = `${data.comment_count} comments`;
            }
            
            // Add new comment to list if visible
            const commentsList = document.getElementById(`comments-list-${postId}`);
            if (commentsList && commentsList.style.display !== 'none') {
                if (commentsList.querySelector('.no-comments')) {
                    commentsList.innerHTML = '';
                }
                
                // Create and append the new comment
                const div = document.createElement('div');
                div.classList.add('comment');
                div.innerHTML = `
                    <strong>${data.comment.name} ${data.comment.surname}</strong> (${data.comment.username}):<br>
                    ${data.comment.body} <br>
                    <small>Posted on ${data.comment.created_at}</small>
                `;
                commentsList.appendChild(div);
            }
            
            // Clear input
            commentInput.value = '';
        }
    } catch (error) {
        console.error('Error adding comment:', error);
    } finally {
        commentInput.disabled = false;
    }
}

function createCommentElement(comment) {
    const commentDiv = document.createElement('div');
    commentDiv.className = 'comment';
    commentDiv.id = `comment-${comment.id}`;

    const currentUserId = document.getElementById('current-user-id')?.dataset?.userId || null;

    commentDiv.innerHTML = `
        <div class="comment-header">
            <div id="userDetail">
            <img src="${comment.profile_picture || '/assets/img/profile.jpg'}" 
                 alt="${comment.username}" class="comment-profile-pic">
            <strong>${comment.name} ${comment.surname}</strong>
            </div>
           <div id="time-act"> 
            <span class="comment-time">${formatCommentTime(comment.created_at)}</span>
            
            ${comment.user_id == currentUserId ? `
            <div class="comment-menu-wrapper">
                <button class="comment-menu-toggle">⋮</button>
                <div class="comment-actions">
                    <button class="edit-comment" data-comment-id="${comment.id}">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="delete-comment" data-comment-id="${comment.id}">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
            ` : ''}
            </div>
            
            
        </div>
        <div class="comment-body">${comment.body}</div>
    `;

    if (comment.user_id == currentUserId) {
        const toggleBtn = commentDiv.querySelector('.comment-menu-toggle');
        const actionsMenu = commentDiv.querySelector('.comment-actions');
        
        // Toggle menu visibility
        toggleBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            actionsMenu.style.display = actionsMenu.style.display === 'none' ? 'block' : 'none';
        });

        // Close menu when clicking elsewhere
        document.addEventListener('click', () => {
            actionsMenu.style.display = 'none';
        });

        // Prevent menu from closing when clicking inside it
        actionsMenu?.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        // Add edit/delete handlers
        commentDiv.querySelector('.edit-comment')?.addEventListener('click', () => handleEditComment(comment.id));
        commentDiv.querySelector('.delete-comment')?.addEventListener('click', () => handleDeleteComment(comment.id, comment.post_id));
    }
console.log('Comment data:', comment);
// Should show: {id: 1, user_id: 123, ...}
    return commentDiv;
}



function formatCommentTime(timestamp) {
  const date = new Date(timestamp);
  return date.toLocaleString();
}

async function handleDeleteComment(commentId, postId) {
  if (!confirm('Are you sure you want to delete this comment?')) return;
  
  try {
    const response = await fetch('/../../App/Controllers/CommentsController.php?action=deleteComment', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ comment_id: commentId })
    });
    
    const data = await response.json();
    
    if (data.success) {
      // Remove comment from UI
      document.getElementById(`comment-${commentId}`)?.remove();
      
      // Update comment count
      const commentsCount = document.querySelector(`#post-${postId} .comments`);
      if (commentsCount) {
        const newCount = parseInt(commentsCount.textContent) - 1;
        commentsCount.textContent = `${newCount} comments`;
      }
      
      // If no comments left, show "no comments" message
      const commentsList = document.getElementById(`comments-list-${postId}`);
      if (commentsList && commentsList.children.length === 0) {
        commentsList.innerHTML = '<div class="no-comments">No comments yet</div>';
      }
    }
  } catch (error) {
    console.error('Error deleting comment:', error);
  }
}

async function handleEditComment(commentId) {
    const commentElement = document.getElementById(`comment-${commentId}`);
    const commentBody = commentElement.querySelector('.comment-body');
    const currentText = commentBody.textContent;
    
    // Create edit interface
    commentBody.innerHTML = `
        <textarea class="edit-comment-input">${currentText}</textarea>
        <div class="edit-comment-buttons">
            <button class="save-edit">Save</button>
            <button class="cancel-edit">Cancel</button>
        </div>
    `;
    
    const textarea = commentBody.querySelector('.edit-comment-input');
    textarea.focus();
    
    // Handle save
    commentBody.querySelector('.save-edit').addEventListener('click', async () => {
        const newText = textarea.value.trim();
        if (!newText) return;
        
        try {
            const response = await fetch('/../../App/Controllers/CommentsController.php?action=updateComment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    comment_id: commentId,
                    new_comment: newText
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Update comment in UI
                commentBody.innerHTML = newText;
            }
        } catch (error) {
            console.error('Error updating comment:', error);
            commentBody.innerHTML = currentText;
        }
    });
    
    // Handle cancel
    commentBody.querySelector('.cancel-edit').addEventListener('click', () => {
        commentBody.innerHTML = currentText;
    });
}
async function loadComments(postId) {
    const commentsList = document.getElementById(`comments-list-${postId}`);
    if (!commentsList) return;

    commentsList.classList.add('visible');
    commentsList.innerHTML = '<div class="loading-comments">Loading comments...</div>';

    try {
        const response = await fetch(`http://localhost:4000/views/get_comments.php?post_id=${postId}`);
        const data = await response.json();

        commentsList.innerHTML = ''; // Clear previous content

        if (data.success && data.comments?.length > 0) {
            data.comments.forEach(comment => {
                const commentElement = createCommentElement(comment); // ⬅ use the new version
                commentsList.appendChild(commentElement);
            });
        } else {
            commentsList.innerHTML = '<div class="no-comments">No comments yet</div>';
        }
    } catch (error) {
        console.error('Error loading comments:', error);
        commentsList.innerHTML = '<div class="comments-error">Error loading comments</div>';
    }
}

function initCommentFunctionality(postId) {
    const commentBtn = document.querySelector(`.comment-btn[data-post-id="${postId}"]`);
    const commentsList = document.getElementById(`comments-list-${postId}`);
    
    if (!commentBtn || !commentsList) return;

    commentBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        
        // Toggle comments visibility
        if (commentsList.style.display === 'none' || commentsList.style.display === '') {
            // Show comments
            commentsList.style.display = 'block';
            loadComments(postId);
        } else {
            // Hide comments
            commentsList.style.display = 'none';
        }
    });

    // Comment submission
    const commentInput = document.getElementById(`comment-${postId}`);
    const submitBtn = document.querySelector(`#submit-comment[data-post-id="${postId}"]`);
    
    commentInput?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') submitComment(postId);
    });
    
    submitBtn?.addEventListener('click', function() {
        submitComment(postId);
    });
}
