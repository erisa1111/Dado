// const notifications = [
//     {
//         profileImg: "https://media.istockphoto.com/id/1437816897/photo/business-woman-manager-or-human-resources-portrait-for-career-success-company-we-are-hiring.jpg?s=612x612&w=0&k=20&c=tyLvtzutRh22j9GqSGI33Z4HpIwv9vL_MZw_xOE19NQ=",
//         username: "Parent1",
//         action: "liked your post",
//         time: "5m ago",
//         preview: "Great post about child safety!",
//         unread: true
//     },
//     {
//         profileImg: "https://media.istockphoto.com/id/1386479313/photo/happy-millennial-afro-american-business-woman-posing-isolated-on-white.jpg?s=612x612&w=0&k=20&c=8ssXDNTp1XAPan8Bg6mJRwG7EXHshFO5o0v9SIj96nY=",
//         username: "Parent2",
//         action: "commented on your post",
//         time: "1h ago",
//         preview: "Thank you for the tips!",
//         unread: false
//     },
//     {
//         profileImg: "https://a.storyblok.com/f/191576/1200x800/a3640fdc4c/profile_picture_maker_before.webp",
//         username: "Parent3",
//         action: "shared your post",
//         time: "2d ago",
//         preview: "Useful information for everyone!",
//         unread: true
//     },
//     {
//         profileImg: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRBwgu1A5zgPSvfE83nurkuzNEoXs9DMNr8Ww&s",
//         username: "NannyA",
//         action: "liked your post",
//         time: "5m ago",
//         preview: "Great post about child safety!",
//         unread: true
//     },
//     {
//         profileImg: "https://easy-peasy.ai/cdn-cgi/image/quality=80,format=auto,width=700/https://fdczvxmwwjwpwbeeqcth.supabase.co/storage/v1/object/public/images/50dab922-5d48-4c6b-8725-7fd0755d9334/3a3f2d35-8167-4708-9ef0-bdaa980989f9.png",
//         username: "Parent4",
//         action: "liked your post",
//         time: "5m ago",
//         preview: "Great post about child safety!",
//         unread: true
//     },
//     {
//         profileImg: "https://marketplace.canva.com/EAFqNrAJpQs/1/0/1600w/canva-neutral-pink-modern-circle-shape-linkedin-profile-picture-WAhofEY5L1U.jpg",
//         username: "NannyB",
//         action: "liked your post",
//         time: "5m ago",
//         preview: "Great post about child safety!",
//         unread: true
//     },
//     {
//         profileImg: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQYXz402I39yGoxw90IrFr9w0vuQnuVSkgPCg&s",
//         username: "NannyC",
//         action: "liked your post",
//         time: "5m ago",
//         preview: "Great post about child safety!",
//         unread: true
//     },
//     {
//         profileImg: "https://shotkit.com/wp-content/uploads/2021/06/cool-profile-pic-matheus-ferrero.jpeg",
//         username: "Parent5",
//         action: "liked your post",
//         time: "5m ago",
//         preview: "Great post about child safety!",
//         unread: true
//     },
//     {
//         profileImg: "https://www.perfocal.com/blog/content/images/2021/01/Perfocal_17-11-2019_TYWFAQ_100_standard-3.jpg",
//         username: "NannyD",
//         action: "liked your post",
//         time: "5m ago",
//         preview: "Great post about child safety!",
//         unread: true
//     },

// ];

// const populateNotification = (notification, templateNotification) => {

//     const notificationImage = templateNotification.querySelector(".notification-image");
//     if (notificationImage && notification.profileImg) {
//         notificationImage.src = notification.profileImg;
//     }

//     const notificationUsername = templateNotification.querySelector(".notification-username");
//     if (notificationUsername) {
//         notificationUsername.textContent = notification.username;
//     }

//     const notificationAction = templateNotification.querySelector(".notification-action");
//     if (notificationAction) {
//         notificationAction.textContent = notification.action;
//     }

//     const notificationTime = templateNotification.querySelector(".notification-time");
//     if (notificationTime) {
//         notificationTime.textContent = notification.time;
//     }

//     if (notification.unread) {
//         templateNotification.classList.add("unread");
//     }
// };
// const createNotifications = (notificationsData) => {
//     const notificationContainer = document.getElementById("notification-container");
//     const templateNotification = document.querySelector(".notification-card");
//     if (!notificationContainer || !templateNotification) {
//         console.error("Notification container or template not found!");
//         return;
//     }
//     notificationsData.forEach((notification) => {
//         const newNotification = templateNotification.cloneNode(true);
//         newNotification.style.display = "block";
//         populateNotification(notification, newNotification);
//         notificationContainer.appendChild(newNotification);
//     });
// };

// async function loadNotificationCard() {
//     const notificationsCardPlaceholder = document.getElementById("center");
//     if (!notificationsCardPlaceholder) {
//         console.error("Placeholder for notifications card not found!");
//         return;
//     }
//     const response = await fetch("/components/notifications_card/notifications_card.php");
//     const notificationsCardHtml = await response.text();
//     notificationsCardPlaceholder.innerHTML = notificationsCardHtml;
//     createNotifications(notifications);
// }

// document.addEventListener("DOMContentLoaded", loadNotificationCard);

// Public/components/notifications_card/notifications_card.js

const NOTIFICATIONS_API_URL = '/notifications-action.php?action=fetch';

// Helper to build a single notification HTML string
function buildNotificationHTML(note) {
    const iconSrc = note.type === 'comment'
        ? '/assets/img/comment_icon.png'
        : '/assets/img/like_icon.png';

    const username = note.type === 'comment'
        ? note.commenter_username
        : note.liker_username;

    const actionText = note.type === 'comment'
        ? `commented on your post "${note.post_title}"`
        : `liked your post "${note.post_title}"`;

    const preview = note.type === 'comment'
        ? `<div class="notification-preview"><p>${note.comment.substring(0, 100)}${note.comment.length > 100 ? '…' : ''}</p></div>`
        : '';

    return `
        <div class="notification-card">
            <div class="notification-header">
                <img class="notification-image" src="${iconSrc}" alt="${note.type} icon">
                <div class="notification-details">
                    <p class="notification-username">${username}</p>
                    <p class="notification-action">${actionText}</p>
                    <span class="notification-time">${note.created_at}</span>
                </div>
            </div>
            ${preview}
        </div>
    `;
}

// Load and render notifications
async function loadNotifications() {
    try {
        const response = await fetch(NOTIFICATIONS_API_URL, { credentials: 'include' });
        const data = await response.json();

        console.log("Raw response data:", data); // 🔍 DEBUG: See the full response

        if (!data.success) {
            console.error('Failed to load notifications:', data.message);
            return;
        }

        console.log("Notifications array:", data.notifications); // 🔍 DEBUG: See parsed notifications

        const container = document.getElementById('center');
        if (!container) return;

        container.innerHTML = '';

        data.notifications.forEach(note => {
            console.log("Rendering notification:", note); // 🔍 DEBUG: See each individual notification
            container.innerHTML += buildNotificationHTML(note);
        });

    } catch (error) {
        console.error('Error loading notifications:', error);
    }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    loadNotifications();
    // Optionally, poll every minute:
    // setInterval(loadNotifications, 60000);
});
