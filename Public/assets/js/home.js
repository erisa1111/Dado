document.addEventListener('DOMContentLoaded', function() {
    initializeModal();
    initializeModalJob();
});

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
document.getElementById('jobpost-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData();

    // Get values
    const title = document.getElementById('job-title').value.trim();
    const description = document.getElementById('job-description').value.trim();
    const location = document.getElementById('job-location').value;
    const salary = document.getElementById('salary').value.trim();
    const jobType = document.querySelector('input[name="job-type"]:checked').value;
    const numKids = document.getElementById('job-num-kids').value.trim();
    const startHour = document.getElementById('start-hour').value.trim();
    const endHour = document.getElementById('end-hour').value.trim();
    const dateRange = document.getElementById('date-range').value.trim();

    // Simple validation
    if (!title || !description || !location) {
        alert('Please fill in the required fields.');
        return;
    }

    // If you are using a combined date range field like "2025-05-16 to 2025-05-20"
    let dateFrom = '';
    let dateTo = '';
    if (dateRange.includes('to')) {
        [dateFrom, dateTo] = dateRange.split('to').map(item => item.trim());
    } else {
        alert('Please select a valid date range.');
        return;
    }

    // Append data
    formData.append('title', title);
    formData.append('description', description);
    formData.append('location', location);
    formData.append('salary', salary);
    formData.append('job_type', jobType);
    formData.append('num_kids', numKids);
    formData.append('start_hour', startHour);
    formData.append('end_hour', endHour);
    formData.append('date_from', dateFrom);
    formData.append('date_to', dateTo);

    try {
        const response = await fetch('http://localhost:4000/views/create_jobpost.php', {
            method: 'POST',
            body: formData,
        });

        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`Server error: ${response.status} - ${errorText}`);
        }

        const data = await response.json();

        if (!data.success) {
            throw new Error(data.message || "Job post creation failed.");
        }

        alert('Job post created successfully!');
        toggleModalVisibility(false);  // Assuming same modal function
        location.reload();

    } catch (error) {
        console.error("Job Post Error:", error);
        alert(`Error: ${error.message}`);
    }
});







function toggleModalVisibility(show) {
    const modal = document.getElementById('post-modal');
    if (show) {
        modal.style.display = 'block';
    } else {
        modal.style.display = 'none';
    }
}
function toggleModalVisibilityJob(show) {
    const modal = document.getElementById('jobpost-modal');
    if (show) {
        modal.style.display = 'block';
    } else {
        modal.style.display = 'none';
    }
}
document.getElementById('post-images').addEventListener('change', function (event) {
    const previewContainer = document.getElementById('image-preview');
    previewContainer.innerHTML = ''; // Clear previous previews

    const files = event.target.files;

    if (files.length === 0) {
        previewContainer.innerHTML = '<p>No images selected</p>';
        return;
    }

    Array.from(files).forEach(file => {
        if (!file.type.startsWith('image/')) return; // Only images

        const reader = new FileReader();
        reader.onload = function (e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            previewContainer.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});


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
function initializeModalJob() {
    const addButton = document.getElementById('add_job');
    const closeButton = document.getElementById('close-jobpost-modal');
    const modal = document.getElementById('jobpost-modal');

    if (addButton) { // Safe check
        addButton.addEventListener('click', () => {
            toggleModalVisibilityJob(true);
        });
    }

    if (closeButton) {
        closeButton.addEventListener('click', () => {
            toggleModalVisibilityJob(false);
        });
    }

    if (modal) {
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                toggleModalVisibilityJob(false);
            }
        });
    }
}







// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    
    setupPostHandlers();
});


function setupPostHandlers() {
    // Event delegation for post actions
    document.addEventListener('click', async function(e) {
        // Post menu toggle
        if (e.target.classList.contains('post-menu-toggle')) {
            handlePostMenuToggle(e);
            return;
        }

        // Edit post
        const editBtn = e.target.closest('.edit-post');
        if (editBtn) {
            e.preventDefault();
            e.stopPropagation();
            await handleEditPost(e);
            return;
        }

        // Delete post
        const deleteBtn = e.target.closest('.delete-post');
        if (deleteBtn) {
            e.preventDefault();
            e.stopPropagation();
            await handleDeletePost(e);
            return;
        }

        // Close menus when clicking outside
        if (!e.target.closest('.post-menu-wrapper')) {
            closeAllMenus();
        }
    });
}

// Handle Post Menu Toggle
function handlePostMenuToggle(e) {
    e.stopPropagation();
    e.preventDefault();

    const postDiv = e.target.closest('.post');
    const actionsMenu = postDiv.querySelector('.post-act');

    // Close other menus
    document.querySelectorAll('.post-act').forEach(menu => {
        if (menu !== actionsMenu) {
            menu.style.display = 'none';
        }
    });

    // Toggle current menu
    actionsMenu.style.display = actionsMenu.style.display === 'block' ? 'none' : 'block';
}

// Close all menus
function closeAllMenus() {
    document.querySelectorAll('.post-act').forEach(menu => {
        menu.style.display = 'none';
    });
}

// Edit Post Handler
let currentEditPostId = null;
let currentDeletePostId = null;

async function handleEditPost(e) {
  const postDiv = e.target.closest('.post');
  currentEditPostId = e.target.closest('.edit-post').dataset.postId;
  const postContent = postDiv.querySelector('.post-content').textContent.trim();

  document.getElementById('editContent').value = postContent;
  document.getElementById('editModal').style.display = 'flex';
}

async function handleDeletePost(e) {
  currentDeletePostId = e.target.closest('.delete-post').dataset.postId;
  document.getElementById('deleteModal').style.display = 'flex';
}

document.getElementById('saveEdit').addEventListener('click', async () => {
  const newContent = document.getElementById('editContent').value;
  const imageFile = document.getElementById('editImage').files[0];

  const formData = new FormData();
  formData.append('post_id', currentEditPostId);
  formData.append('content', newContent);
  if (imageFile) {
    formData.append('image', imageFile);
  }

  try {
    const response = await fetch('http://localhost:4000/views/handle_post.php?action=editPost', {
      method: 'POST',
      body: formData
    });

    const data = await response.json();
    if (data.success) {
      location.reload();
    } else {
      alert(data.message);
    }
  } catch (err) {
    alert('Error updating post');
  }
});


// Confirm Delete
document.getElementById('confirmDelete').addEventListener('click', async () => {
  const response = await fetch('http://localhost:4000/views/handle_post.php?action=deletePost', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ post_id: currentDeletePostId })
  });

  const data = await response.json();
  if (data.success) {
    location.reload();
  } else {
    alert(data.message);
  }
});

// Cancel or close modals
document.getElementById('editClose').onclick = () => document.getElementById('editModal').style.display = 'none';
document.getElementById('deleteClose').onclick = () => document.getElementById('deleteModal').style.display = 'none';
document.getElementById('cancelDelete').onclick = () => document.getElementById('deleteModal').style.display = 'none';
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('post-menu-toggle')) {
        const menu = e.target.nextElementSibling;
        menu.classList.toggle('visible');
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Like button functionality
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            toggleLike(postId);
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





document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.post').forEach(post => {
        const postId = post.id.split('-')[1];
        initCommentFunctionality(postId);
    });
});

function initCommentFunctionality(postId) {
    const commentBtn = document.querySelector(`.comment-btn[data-post-id="${postId}"]`);
    const commentsList = document.getElementById(`comments-list-${postId}`);
    if (!commentBtn || !commentsList) return;

    commentBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        
        commentsList.classList.toggle('visible');
        if (commentsList.classList.contains('visible')) {
            loadComments(postId);
        }
    });

    const commentInput = document.getElementById(`comment-${postId}`);
    commentInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            submitComment(postId);
        }
    });

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
        
        toggleBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            actionsMenu.style.display = actionsMenu.style.display === 'none' ? 'block' : 'none';
        });

        document.addEventListener('click', () => {
            actionsMenu.style.display = 'none';
        });

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
    
    const currentUserId = document.getElementById('current-user-id')?.dataset?.userId;
    if (!currentUserId) {
        alert('You must be logged in to delete comments');
        return;
    }

    try {
        const response = await fetch('http://localhost:4000/views/handle_comment.php?action=deleteComment', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                comment_id: commentId,
                user_id: currentUserId 
            })
        });

        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
        const data = await response.json();
        
        if (data.success) {
            document.getElementById(`comment-${commentId}`)?.remove();
            // Update comment count, etc.
        } else {
            alert(data.message || 'Failed to delete comment');
        }
    } catch (error) {
        console.error('Error deleting comment:', error);
        alert('Error deleting comment. Check console for details.');
    }
}
async function handleEditComment(commentId) {
    const commentElement = document.getElementById(`comment-${commentId}`);
    const commentBody = commentElement.querySelector('.comment-body');
    const currentText = commentBody.textContent;
    const currentUserId = document.getElementById('current-user-id')?.dataset?.userId;

    if (!currentUserId) {
        alert('You must be logged in to edit comments');
        return;
    }

    commentBody.innerHTML = `
        <textarea class="edit-comment-input">${currentText}</textarea>
        <div class="edit-comment-buttons">
            <button class="save-edit">Save</button>
            <button class="cancel-edit">Cancel</button>
        </div>
    `;

    const textarea = commentBody.querySelector('.edit-comment-input');
    textarea.focus();

    commentBody.querySelector('.save-edit').addEventListener('click', async () => {
        const newText = textarea.value.trim();
        if (!newText) return;

        try {
            const response = await fetch('http://localhost:4000/views/handle_comment.php?action=updateComment', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ 
                    comment_id: commentId,
                    new_comment: newText,
                    user_id: currentUserId
                })
            });

            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            const data = await response.json();

            if (data.success) {
                commentBody.innerHTML = newText;
            } else {
                alert(data.message || 'Failed to update comment');
                commentBody.innerHTML = currentText;
            }
        } catch (error) {
            console.error('Error updating comment:', error);
            alert('Error updating comment. Check console for details.');
            commentBody.innerHTML = currentText;
        }
    });

    commentBody.querySelector('.cancel-edit').addEventListener('click', () => {
        commentBody.innerHTML = currentText;
    });
}
async function loadComments(postId) {
    const commentsList = document.getElementById(`comments-list-${postId}`);
    if (!commentsList) return;

    commentsList.innerHTML = '<div class="loading-comments">Loading comments...</div>';

    try {
        const response = await fetch(`http://localhost:4000/views/get_comments.php?post_id=${postId}`);
        const data = await response.json();

        commentsList.innerHTML = '';

        if (data.success && data.comments?.length > 0) {
            data.comments.forEach(comment => {
                const commentElement = createCommentElement(comment);
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



document.addEventListener('DOMContentLoaded', function() {
    // Initialize functionality for both post types
   
    initJobPostFunctionality();
});
function initJobPostFunctionality() {
    // Like button functionality for job posts
    document.querySelectorAll('.job-like-btn').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            toggleJobLike(postId);
        });
    });

    // Comment functionality for job posts
    document.querySelectorAll('.job-post').forEach(post => {
        const postId = post.id.split('-')[2]; // job-post-ID format
        initJobCommentFunctionality(postId);
    });
}
async function toggleJobLike(postId) {
    try {
        console.log('Attempting to like job post:', postId); // Debug log
        
        const response = await fetch('like_job_post.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ job_post_id: postId })
        });

        // First check if response is OK
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Server responded with status:', response.status, 'Text:', errorText);
            throw new Error(`Server error: ${response.status}`);
        }

        // Then try to parse as JSON
        let data;
        try {
            data = await response.json();
            console.log('Response data:', data); // Debug log
        } catch (jsonError) {
            console.error('Failed to parse JSON:', jsonError);
            throw new Error('Invalid JSON response from server');
        }

        if (!data || typeof data.success === 'undefined') {
            console.error('Invalid data structure:', data);
            throw new Error('Invalid data structure from server');
        }
        
        if (!data.success) {
            throw new Error(data.message || 'Action failed');
        }

        // Update UI - make sure we're targeting the correct elements
        const postElement = document.getElementById(`job-post-${postId}`);
        if (!postElement) {
            console.error('Could not find job post element with ID:', `job-post-${postId}`);
            return;
        }

        const likesCountElement = postElement.querySelector('.job-likes');
        if (likesCountElement) {
            likesCountElement.textContent = `${data.job_like_count} likes`;
        } else {
            console.error('Could not find likes count element in post:', postId);
        }
        
        const likeButton = postElement.querySelector('.job-like-btn i');
        if (likeButton) {
            likeButton.classList.toggle('fa-regular', !data.is_liked);
            likeButton.classList.toggle('fa-solid', data.is_liked);
            likeButton.style.color = data.is_liked ? 'red' : '';
        } else {
            console.error('Could not find like button in post:', postId);
        }

    } catch (error) {
        console.error('Error in toggleJobLike:', error);
        // Show user-friendly error message
        alert('Failed to update like. Please try again. Error: ' + error.message);
    }
}// Add this to your DOMContentLoaded event listener
document.addEventListener('DOMContentLoaded', function() {
    // Existing code...
    
    // Add this event delegation for job post comments
    document.addEventListener('click', function(e) {
        // Handle job comment submission
        if (e.target.matches('#job-submit-comment, #job-submit-comment i')) {
            const postId = e.target.closest('[data-post-id]').getAttribute('data-post-id');
            submitJobComment(postId);
        }
    });
});

function initJobCommentFunctionality(postId) {
    const commentBtn = document.querySelector(`.job-comment-btn[data-post-id="${postId}"]`);
    const commentsList = document.getElementById(`job-comments-list-${postId}`);
    
    if (!commentBtn || !commentsList) return;

    commentBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        
        if (commentsList.style.display === 'none' || commentsList.style.display === '') {
            commentsList.style.display = 'block';
            loadJobComments(postId);
        } else {
            commentsList.style.display = 'none';
        }
    });

    // Keep the input keypress listener (input element isn't recreated)
    const commentInput = document.getElementById(`job-comment-${postId}`);
    commentInput?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') submitJobComment(postId);
    });
    
    // Remove the button click listener here since we're using delegation
}
async function submitJobComment(postId) {
    console.log('Attempting to submit job comment for post:', postId); // Debug log
    
    const commentInput = document.getElementById(`job-comment-${postId}`);
    if (!commentInput) {
        console.error('Job comment input not found for post', postId);
        return;
    }
    
    const commentText = commentInput.value.trim();
    if (!commentText) return;
    
    commentInput.disabled = true;
    
    try {
        console.log('Submitting job comment:', commentText); // Debug log
        const response = await fetch('/views/comment_job_post.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
                job_post_id: postId,
                comment: commentText 
            })
        });
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Job comment submission failed:', errorText);
            throw new Error('Failed to submit comment');
        }
        
        const data = await response.json();
        console.log('Job comment response:', data); // Debug log
        
        if (data.success) {
            // Update comment count
            const commentsCount = document.querySelector(`#job-post-${postId} .job-comments`);
            if (commentsCount) {
                commentsCount.textContent = `${data.comment_count} comments`;
            }
            
            // Add new comment to list if visible
            const commentsList = document.getElementById(`job-comments-list-${postId}`);
            if (commentsList && commentsList.style.display !== 'none') {
                if (commentsList.querySelector('.job-no-comments')) {
                    commentsList.innerHTML = '';
                }
                
                const commentElement = createJobCommentElement(data.comment);
                commentsList.appendChild(commentElement);
            }
            
            commentInput.value = '';
        } else {
            console.error('Job comment submission failed:', data.message);
            alert(data.message || 'Failed to submit comment');
        }
    } catch (error) {
        console.error('Error adding job comment:', error);
        alert('Error submitting comment. Please try again.');
    } finally {
        commentInput.disabled = false;
    }
}

async function loadJobComments(postId) {
    const commentsList = document.getElementById(`job-comments-list-${postId}`);
    if (!commentsList) return;

    commentsList.classList.add('visible');
    commentsList.innerHTML = '<div class="loading-comments">Loading comments...</div>';

    try {
        const response = await fetch(`http://localhost:4000/views/get_job_post_comments.php?job_post_id=${postId}`);
        const data = await response.json();

        commentsList.innerHTML = '';

        if (data.success && data.comments?.length > 0) {
            data.comments.forEach(comment => {
                const commentElement = createJobCommentElement(comment);
                commentsList.appendChild(commentElement);
            });
        } else {
            commentsList.innerHTML = '<div class="job-no-comments">No comments yet</div>';
        }
    } catch (error) {
        console.error('Error loading job comments:', error);
        commentsList.innerHTML = '<div class="comments-error">Error loading comments</div>';
    }
}

function createJobCommentElement(comment) {
    const commentDiv = document.createElement('div');
    commentDiv.className = 'comment';
    commentDiv.id = `job-comment-${comment.id}`;

    const currentUserId = document.getElementById('current-user-id-job')?.dataset?.userId || null;

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
        
        toggleBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            actionsMenu.style.display = actionsMenu.style.display === 'none' ? 'block' : 'none';
        });

        document.addEventListener('click', () => {
            actionsMenu.style.display = 'none';
        });

        actionsMenu?.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        commentDiv.querySelector('.edit-comment')?.addEventListener('click', () => handleEditJobComment(comment.id));
        commentDiv.querySelector('.delete-comment')?.addEventListener('click', () => handleDeleteJobComment(comment.id, comment.job_post_id));
    }

    return commentDiv;
}

async function handleDeleteJobComment(commentId, postId) {
    if (!confirm('Are you sure you want to delete this comment?')) return;
    
    const currentUserId = document.getElementById('current-user-id-job')?.dataset?.userId;
    if (!currentUserId) {
        alert('You must be logged in to delete comments');
        return;
    }

    try {
        const response = await fetch('http://localhost:4000/views/handle_job_post_comment.php?action=deleteComment', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                comment_id: commentId,
                user_id: currentUserId 
            })
        });

        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
        const data = await response.json();
        
        if (data.success) {
            document.getElementById(`job-comment-${commentId}`)?.remove();
            // Update comment count
            const commentsCount = document.querySelector(`#job-post-${postId} .job-comments`);
            if (commentsCount) {
                commentsCount.textContent = `${data.comment_count} comments`;
            }
        } else {
            alert(data.message || 'Failed to delete comment');
        }
    } catch (error) {
        console.error('Error deleting job comment:', error);
        alert('Error deleting comment. Check console for details.');
    }
}

async function handleEditJobComment(commentId) {
    const commentElement = document.getElementById(`job-comment-${commentId}`);
    const commentBody = commentElement.querySelector('.comment-body');
    const currentText = commentBody.textContent;
    const currentUserId = document.getElementById('current-user-id-job')?.dataset?.userId;

    if (!currentUserId) {
        alert('You must be logged in to edit comments');
        return;
    }

    commentBody.innerHTML = `
        <textarea class="edit-comment-input">${currentText}</textarea>
        <div class="edit-comment-buttons">
            <button class="save-edit">Save</button>
            <button class="cancel-edit">Cancel</button>
        </div>
    `;

    const textarea = commentBody.querySelector('.edit-comment-input');
    textarea.focus();

    commentBody.querySelector('.save-edit').addEventListener('click', async () => {
        const newText = textarea.value.trim();
        if (!newText) return;

        try {
            const response = await fetch('http://localhost:4000/views/handle_job_post_comment.php?action=updateComment', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ 
                    comment_id: commentId,
                    new_comment: newText,
                    user_id: currentUserId
                })
            });

            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            const data = await response.json();

            if (data.success) {
                commentBody.innerHTML = newText;
            } else {
                alert(data.message || 'Failed to update comment');
                commentBody.innerHTML = currentText;
            }
        } catch (error) {
            console.error('Error updating job comment:', error);
            alert('Error updating comment. Check console for details.');
            commentBody.innerHTML = currentText;
        }
    });

    commentBody.querySelector('.cancel-edit').addEventListener('click', () => {
        commentBody.innerHTML = currentText;
    });
}

document.querySelectorAll('.apply-btn').forEach(button => {
    button.addEventListener('click', async (e) => {
        e.preventDefault();

        const form = e.target.closest('form');
        const jobId = form.querySelector('input[name="job_id"]').value;

        try {
            const response = await fetch('/views/jobpost_controller.php?action=applyToJobPost', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ job_post_id: jobId })
            });

            const data = await response.json();

            if (data.success) {
                alert(data.message); // or update UI accordingly
            } else {
                alert('Failed to apply: ' + data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while applying.');
        }
    });
});
