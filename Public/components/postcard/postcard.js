async function loadPostCard() {
    const postCardPlaceholder = document.getElementById("center");
    const response = await fetch("postcard/postcard.html");
    const postCardHtml = await response.text();
    postCardPlaceholder.innerHTML = postCardHtml;

    createPosts(posts);
    initializeModal();
    initializePostForm();

}




const toggleCommentsDisplay = (postElement) => {
    const commentList = postElement.querySelector("#comments-list");
    const commentsToggleButton = postElement.querySelector(".comments-toggle");
    const comments = Array.from(commentList.children);

    if (commentsToggleButton.textContent === "Show all comments") {

        comments.forEach((comment) => (comment.style.display = "flex"));
        commentsToggleButton.textContent = "Show less";
    } else {

        comments.forEach((comment, index) => {
            comment.style.display = index < 2 ? "flex" : "none";
        });
        commentsToggleButton.textContent = "Show all comments";
    }
};




const handleComment = (post, postElement) => {
    const commentInput = postElement.querySelector("#comment-input");
    const commentList = postElement.querySelector("#comments-list");
    const commentsCount = postElement.querySelector("#comments");
    const commentsSection = postElement.querySelector(".comments-list");

    const newComment = commentInput.value.trim();

    if (newComment) {

        const commentElement = document.createElement("div");
        commentElement.classList.add("comment");

        const commentProfileImg = document.createElement("img");
        commentProfileImg.classList.add("comment-profile-img");
        commentProfileImg.src = "../img/profile.jpg";
        commentProfileImg.alt = "User Profile";

        const commentContent = document.createElement("div");
        commentContent.classList.add("comment-content");

        const commentUsername = document.createElement("span");
        commentUsername.classList.add("comment-username");
        commentUsername.textContent = post.username;

        const commentText = document.createElement("p");
        commentText.classList.add("comment-text");
        commentText.textContent = newComment;

        commentContent.appendChild(commentUsername);
        commentContent.appendChild(commentText);

        const deleteButton = document.createElement("button");
        deleteButton.classList.add("delete-comment");
        deleteButton.textContent = "Delete";
        deleteButton.addEventListener("click", () => {
            commentList.removeChild(commentElement);
            post.comments -= 1;
            commentsCount.textContent = `${post.comments} Comments`;
        });

        commentElement.appendChild(commentProfileImg);
        commentElement.appendChild(commentContent);
        commentElement.appendChild(deleteButton);

        commentList.appendChild(commentElement);

        commentInput.value = "";

        post.comments += 1;
        commentsCount.textContent = `${post.comments} Comments`;

        if (post.comments === 1) {
            commentsSection.classList.remove("hidden");
        }


        toggleCommentsDisplay(postElement);
    }
};

const initializeComments = (postElement) => {
    const commentList = postElement.querySelector("#comments-list");
    const commentsToggleButton = document.createElement("button");
    commentsToggleButton.classList.add("comments-toggle");
    commentsToggleButton.textContent = "Show all comments";

    commentList.after(commentsToggleButton);

    commentsToggleButton.addEventListener("click", () => {
        toggleCommentsDisplay(postElement);
    });


    const comments = Array.from(commentList.children);
    comments.forEach((comment, index) => {
        comment.style.display = index < 2 ? "flex" : "none";
    });
};




const posts = [
    {
        profileImg: "../img/dado_profile.webp",
        username: "BabySitter1",
        location: "1d · Prishtine, Kosove",
        content: "Hi! I’m [Your Name], an experienced and caring nanny with [X] years of experience in childcare. I’m passionate about providing a safe, nurturing, and fun environment for children. I enjoy engaging kids in creative activities, helping with homework, and ensuring they feel loved and supported. I’m available for [full-time/part-time] work and can provide references upon request. Looking forward to helping your family!",
        images: ["../img/Cute-Room.jpg", "../img/another_room1.png"],
        likes: 125,
        comments: 10,
        isLiked: false,
    },
    {
        profileImg: "../img/profile.jpg",
        username: "BabySitter2",
        location: "2d · Gjilan, Kosove",
        content: "Looking for experienced babysitters!",
        images: ["../img/recieve.png"],
        likes: 200,
        comments: 15,
        isLiked: false,
    },
];



const toggleLike = (post, likesElement, heartIcon) => {
    post.isLiked = !post.isLiked;
    if (post.isLiked) {
        post.likes += 1;
        heartIcon.classList.add("fa-solid");
        heartIcon.classList.remove("fa-regular");
        heartIcon.style.color = "#e2687e";


        heartIcon.addEventListener("mouseenter", () => {
            heartIcon.style.color = "#e2687e";
        });
        heartIcon.addEventListener("mouseleave", () => {
            heartIcon.style.color = "#e2687e";
        });
    } else {
        post.likes -= 1;
        heartIcon.classList.add("fa-regular");
        heartIcon.classList.remove("fa-solid");
        heartIcon.style.color = "black";


        heartIcon.addEventListener("mouseenter", () => {
            heartIcon.style.color = "#e2687e";
        });
        heartIcon.addEventListener("mouseleave", () => {
            heartIcon.style.color = "black";
        });
    }
    likesElement.textContent = `${post.likes} likes`;
};
const populatePost = (post, templatePost) => {
    templatePost.querySelector("#profile-img").src = post.profileImg;
    templatePost.querySelector("#username").textContent = post.username;
    templatePost.querySelector("#location").textContent = post.location;
    templatePost.querySelector("#content").textContent = post.content;

    const imagesContainer = templatePost.querySelector("#images");
    imagesContainer.innerHTML = "";
    post.images.forEach((imgSrc) => {
        const img = document.createElement("img");
        img.src = imgSrc;
        imagesContainer.appendChild(img);
    });

    const likesElement = templatePost.querySelector("#likes");
    likesElement.textContent = `${post.likes} likes`;
    templatePost.querySelector("#comments").textContent = `${post.comments} Comments`;

    const heartButton = templatePost.querySelector(".fa-heart");
    heartButton.addEventListener("click", () => {
        toggleLike(post, likesElement, heartButton);
    });

    const submitCommentButton = templatePost.querySelector("#submit-comment");
    submitCommentButton.addEventListener("click", () => {
        handleComment(post, templatePost);
    });


    initializeComments(templatePost);
};



const createPosts = (postsData) => {
    const postContainer = document.getElementById("post-container");
    const templatePost = document.querySelector(".post");


    if (!postContainer || !templatePost) {
        console.error("Post container or template not found!");
        return;
    }

    postsData.forEach((post) => {
        const newPost = templatePost.cloneNode(true);
        newPost.style.display = "block";
        populatePost(post, newPost);
        postContainer.appendChild(newPost);
    });
};

function toggleModalVisibility(show) {
    const modal = document.getElementById("post-modal");
    if (show) {
        modal.style.display = "block"; // Show the modal
    } else {
        modal.style.display = "none"; // Hide the modal
    }
}

function initializeModal() {
    const addButton = document.getElementById("add");
    const closeButton = document.getElementById("close-modal");

    // Show the modal when the Add button is clicked
    addButton.addEventListener("click", () => {
        toggleModalVisibility(true);
    });

    // Hide the modal when the Close button is clicked
    closeButton.addEventListener("click", () => {
        toggleModalVisibility(false);
    });

    // Optional: Close the modal if the user clicks outside the modal content
    const modal = document.getElementById("post-modal");
    modal.addEventListener("click", (event) => {
        if (event.target === modal) {
            toggleModalVisibility(false);
        }
    });
}

function initializePostForm() {
    // Select necessary elements
    const postForm = document.getElementById('post-form');
    const postImagesInput = document.getElementById('post-images');
    const imagePreview = document.getElementById('image-preview');
    const postContainer = document.getElementById("post-container");
    const templatePost = document.querySelector(".post");

    if (!postContainer || !templatePost) {
        console.error("Post container or template not found!");
        return;
    }

    // Function to handle file selection and display previews
    const handleFileSelection = (event) => {
        const files = event.target.files;
        imagePreview.innerHTML = ''; // Clear previous previews

        Array.from(files).forEach((file) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = document.createElement('img');
                img.src = e.target.result; // Display the image preview
                imagePreview.appendChild(img);
            };
            reader.readAsDataURL(file); // Read the file as a data URL
        });
    };

    // Function to handle form submission
    const handleFormSubmission = (event) => {
        event.preventDefault();
        const postContent = document.getElementById('post-content').value.trim();
        const files = postImagesInput.files;

        if (!postContent && files.length === 0) {
            alert('Post content or at least one image is required.');
            return;
        }

        // Collect image sources for display
        const images = [];
        Array.from(files).forEach((file) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                images.push(e.target.result);

                // Once all images are processed, create and add the new post
                if (images.length === files.length) {
                    addNewPost(postContent, images);
                }
            };
            reader.readAsDataURL(file);
        });

        // If there are no images, create the post immediately
        if (files.length === 0) {
            addNewPost(postContent, []);
        }
    };

    // Function to create a new post
    const addNewPost = (content, images) => {
        const newPost = {
            profileImg: "../img/profile.jpg", // Placeholder for the user's profile image
            username: "CurrentUser", // Replace with the actual logged-in user's username
            location: "Just now · Your Location", // Placeholder for location
            content,
            images,
            likes: 0,
            comments: 0,
            isLiked: false,
        };

        // Add to posts array
        posts.unshift(newPost);

        // Create the new post element and add it to the DOM
        const newPostElement = templatePost.cloneNode(true);
        newPostElement.style.display = "block"; // Make it visible
        populatePost(newPost, newPostElement);
        postContainer.prepend(newPostElement); // Add it at the top

        // Clear form and preview
        postForm.reset();
        imagePreview.innerHTML = '';
        toggleModalVisibility(false); // Hide the modal
    };

    // Attach event listeners
    postImagesInput.addEventListener('change', handleFileSelection);
    postForm.addEventListener('submit', handleFormSubmission);
}






document.addEventListener('DOMContentLoaded', loadPostCard);
/*// Data for the post
const postData = {
    profileImg: "../img/dado_profile.webp",
    username: "BabySitter",
    location: "1d · Prishtine, Kosove",
    content: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Saepe voluptatum officia...",
    images: ["../img/Cute-Room.jpg", "../img/another_room1.png"],
    likes: 925,
    comments: 23
  };

  // Function to populate post content dynamically
  const populatePost = (data) => {
    // Update the profile image
    const profileImg = document.getElementById("profile-img");
    profileImg.src = data.profileImg;

    // Update the username
    const username = document.getElementById("username");
    username.textContent = data.username;

    // Update the location
    const location = document.getElementById("location");
    location.textContent = data.location;

    // Update the content text
    const content = document.getElementById("content");
    content.textContent = data.content;

    // Update images
    const imagesContainer = document.getElementById("images");
    imagesContainer.innerHTML = ""; // Clear any existing images
    data.images.forEach((imgSrc) => {
      const img = document.createElement("img");
      img.src = imgSrc;
      img.alt = "Post Image";
      imagesContainer.appendChild(img);
    });

    // Update likes and comments
    const likes = document.getElementById("likes");
    likes.textContent = `${data.likes} likes`;

    const comments = document.getElementById("comments");
    comments.textContent = `${data.comments} Comments`;
  };

  // Populate the post with the data*/

// Create and populate multiple posts
// Data for multiple posts