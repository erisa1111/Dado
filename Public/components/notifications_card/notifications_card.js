const notifications = [
    {
        profileImg: "https://media.istockphoto.com/id/1437816897/photo/business-woman-manager-or-human-resources-portrait-for-career-success-company-we-are-hiring.jpg?s=612x612&w=0&k=20&c=tyLvtzutRh22j9GqSGI33Z4HpIwv9vL_MZw_xOE19NQ=",
        username: "Parent1",
        action: "liked your post",
        time: "5m ago",
        preview: "Great post about child safety!",
        unread: true
    },
    {
        profileImg: "https://media.istockphoto.com/id/1386479313/photo/happy-millennial-afro-american-business-woman-posing-isolated-on-white.jpg?s=612x612&w=0&k=20&c=8ssXDNTp1XAPan8Bg6mJRwG7EXHshFO5o0v9SIj96nY=",
        username: "Parent2",
        action: "commented on your post",
        time: "1h ago",
        preview: "Thank you for the tips!",
        unread: false
    },
    {
        profileImg: "https://a.storyblok.com/f/191576/1200x800/a3640fdc4c/profile_picture_maker_before.webp",
        username: "Parent3",
        action: "shared your post",
        time: "2d ago",
        preview: "Useful information for everyone!",
        unread: true
    },
    {
        profileImg: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRBwgu1A5zgPSvfE83nurkuzNEoXs9DMNr8Ww&s",
        username: "NannyA",
        action: "liked your post",
        time: "5m ago",
        preview: "Great post about child safety!",
        unread: true
    },
    {
        profileImg: "https://easy-peasy.ai/cdn-cgi/image/quality=80,format=auto,width=700/https://fdczvxmwwjwpwbeeqcth.supabase.co/storage/v1/object/public/images/50dab922-5d48-4c6b-8725-7fd0755d9334/3a3f2d35-8167-4708-9ef0-bdaa980989f9.png",
        username: "Parent4",
        action: "liked your post",
        time: "5m ago",
        preview: "Great post about child safety!",
        unread: true
    },
    {
        profileImg: "https://marketplace.canva.com/EAFqNrAJpQs/1/0/1600w/canva-neutral-pink-modern-circle-shape-linkedin-profile-picture-WAhofEY5L1U.jpg",
        username: "NannyB",
        action: "liked your post",
        time: "5m ago",
        preview: "Great post about child safety!",
        unread: true
    },
    {
        profileImg: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQYXz402I39yGoxw90IrFr9w0vuQnuVSkgPCg&s",
        username: "NannyC",
        action: "liked your post",
        time: "5m ago",
        preview: "Great post about child safety!",
        unread: true
    },
    {
        profileImg: "https://shotkit.com/wp-content/uploads/2021/06/cool-profile-pic-matheus-ferrero.jpeg",
        username: "Parent5",
        action: "liked your post",
        time: "5m ago",
        preview: "Great post about child safety!",
        unread: true
    },
    {
        profileImg: "https://www.perfocal.com/blog/content/images/2021/01/Perfocal_17-11-2019_TYWFAQ_100_standard-3.jpg",
        username: "NannyD",
        action: "liked your post",
        time: "5m ago",
        preview: "Great post about child safety!",
        unread: true
    },

];

const populateNotification = (notification, templateNotification) => {

    const notificationImage = templateNotification.querySelector(".notification-image");
    if (notificationImage && notification.profileImg) {
        notificationImage.src = notification.profileImg;
    }

    const notificationUsername = templateNotification.querySelector(".notification-username");
    if (notificationUsername) {
        notificationUsername.textContent = notification.username;
    }

    const notificationAction = templateNotification.querySelector(".notification-action");
    if (notificationAction) {
        notificationAction.textContent = notification.action;
    }

    const notificationTime = templateNotification.querySelector(".notification-time");
    if (notificationTime) {
        notificationTime.textContent = notification.time;
    }

    if (notification.unread) {
        templateNotification.classList.add("unread");
    }
};
const createNotifications = (notificationsData) => {
    const notificationContainer = document.getElementById("notification-container");
    const templateNotification = document.querySelector(".notification-card");
    if (!notificationContainer || !templateNotification) {
        console.error("Notification container or template not found!");
        return;
    }
    notificationsData.forEach((notification) => {
        const newNotification = templateNotification.cloneNode(true);
        newNotification.style.display = "block";
        populateNotification(notification, newNotification);
        notificationContainer.appendChild(newNotification);
    });
};

async function loadNotificationCard() {
    const notificationsCardPlaceholder = document.getElementById("center");
    if (!notificationsCardPlaceholder) {
        console.error("Placeholder for notifications card not found!");
        return;
    }
    const response = await fetch("notifications_card/notifications_card.html");
    const notificationsCardHtml = await response.text();
    notificationsCardPlaceholder.innerHTML = notificationsCardHtml;
    createNotifications(notifications);
}

document.addEventListener("DOMContentLoaded", loadNotificationCard);