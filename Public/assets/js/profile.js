document.addEventListener("DOMContentLoaded", () => {
    const content = {
        story: `
        <p>Hi, my name is <span contenteditable="false" id="user-name">[Your Name]</span>, and caring for children has always been a part of my life.</p>
        <p>Growing up in a large family, I was often the go-to babysitter for my younger siblings and cousins, which sparked my passion for working with kids.</p>
        <p>Over the past [X years], I've had the privilege of helping families by providing loving and reliable care to their little ones.</p>
        `,
        skills: `
        <p>First Aid & CPR Certified: Trained to handle emergencies and ensure children's safety.</p>
        <p>Organizational Skills: Skilled in planning daily routines, activities, and meals.</p>
        `,
        experience: `
        <p>Private Nanny for [X Years]</p>
        <p>Provided care for children aged [range] by creating safe, engaging environments and fostering developmental growth.</p>
        `,
        reviews: `
        <h3>Reviews</h3>
        <div class="review">
            <p><strong>Jane Doe</strong> - <span class="review-rating">★★★★★</span></p>
            <p>"Amazing nanny! My kids loved spending time with her and learned so much."</p>
        </div>
        <div class="review">
            <p><strong>John Smith</strong> - <span class="review-rating">★★★★☆</span></p>
            <p>"Very reliable and great with kids. Highly recommend."</p>
        </div>
        `
    };

    const titles = {
        story: "My Story",
        skills: "Skills",
        experience: "Experience",
        reviews: "Reviews"
    };
    

    const links = document.querySelectorAll(".summary ul li a");
    const contentDiv = document.getElementById("content");
    const title = document.getElementById("title");

    links.forEach(link => {
        link.addEventListener("click", (event) => {
            event.preventDefault();
            const section = event.target.getAttribute("data-section");

            if (content[section]) {
                contentDiv.innerHTML = content[section];
                title.textContent = titles[section];
            } else {
                console.error(`Section "${section}" does not exist in content object.`);
            }
        });
    });
});
async function loadUserPosts() {
    const postCardPlaceholder = document.getElementById("post-container");
    const response = await fetch("postcard/postcard.html");
    const postCardHtml = await response.text();
    postCardPlaceholder.innerHTML = postCardHtml;

    // Filter posts for the current user
    const currentUser = "CurrentUser"; // Replace with logic to fetch the logged-in user's username
    const userPosts = posts.filter(post => post.username === currentUser);

    createPosts(userPosts);
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

const populatePost = (post, templatePost) => {
    templatePost.querySelector("#profile-img").src = post.profileImg;
    templatePost.querySelector("#username").textContent = post.username;
    templatePost.querySelector("#location").textContent = post.location;

    const contentElement = templatePost.querySelector("#content");
    const imagesContainer = templatePost.querySelector("#images");
    imagesContainer.innerHTML = "";

    contentElement.textContent = post.content;

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

document.addEventListener("DOMContentLoaded", loadUserPosts);



