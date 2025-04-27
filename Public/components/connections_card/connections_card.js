const connections = [
    {
        profileImg: "https://media.istockphoto.com/id/1437816897/photo/business-woman-manager-or-human-resources-portrait-for-career-success-company-we-are-hiring.jpg?s=612x612&w=0&k=20&c=tyLvtzutRh22j9GqSGI33Z4HpIwv9vL_MZw_xOE19NQ=",
        username: "NannyA",
        action: "sent you a connect",
        unread: true,
    },
    {
        profileImg: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRBwgu1A5zgPSvfE83nurkuzNEoXs9DMNr8Ww&s",
        username: "NannyB",
        action: "sent you a connect",
        unread: false,
    },
    {
        profileImg: "https://media.istockphoto.com/id/1386479313/photo/happy-millennial-afro-american-business-woman-posing-isolated-on-white.jpg?s=612x612&w=0&k=20&c=8ssXDNTp1XAPan8Bg6mJRwG7EXHshFO5o0v9SIj96nY=",
        username: "Parent 1",
        action: "sent you a connect",
        unread: true,
    },
    {
        profileImg: "https://a.storyblok.com/f/191576/1200x800/a3640fdc4c/profile_picture_maker_before.webp",
        username: "NannyC",
        action: "sent you a connect",
        unread: false,
    },
    {
        profileImg: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRBwgu1A5zgPSvfE83nurkuzNEoXs9DMNr8Ww&s",
        username: "Parent 2",
        action: "sent you a connect",
        unread: true,
    },
    {
        profileImg: "https://easy-peasy.ai/cdn-cgi/image/quality=80,format=auto,width=700/https://fdczvxmwwjwpwbeeqcth.supabase.co/storage/v1/object/public/images/50dab922-5d48-4c6b-8725-7fd0755d9334/3a3f2d35-8167-4708-9ef0-bdaa980989f9.png",
        username: "NannyD",
        action: "sent you a connect",
        unread: false,
    },
    {
        profileImg: "https://marketplace.canva.com/EAFqNrAJpQs/1/0/1600w/canva-neutral-pink-modern-circle-shape-linkedin-profile-picture-WAhofEY5L1U.jpg",
        username: "Parent 3",
        action: "sent you a connect",
        unread: true,
    },
    {
        profileImg: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQYXz402I39yGoxw90IrFr9w0vuQnuVSkgPCg&s",
        username: "Parent 4",
        action: "sent you a connect",
        unread: true,
    },
    {
        profileImg: "https://shotkit.com/wp-content/uploads/2021/06/cool-profile-pic-matheus-ferrero.jpeg",
        username: "Parent 5",
        action: "sent you a connect",
        unread: true,
    },
    {
        profileImg: "https://www.perfocal.com/blog/content/images/2021/01/Perfocal_17-11-2019_TYWFAQ_100_standard-3.jpg",
        username: "Parent 6",
        action: "sent you a connect",
        unread: true,
    }
];

async function loadConnectionCards() {
    const connectionsContainer = document.getElementById("center");
    const response = await fetch("/components/connections_card/connections_card.html");
    const connectionsCardHtml = await response.text();
    connectionsContainer.innerHTML = connectionsCardHtml;

    createConnections(connections);
}
const populateConnectionCard = (connection, templateCard) => {
    const connectionImage = templateCard.querySelector(".connection-image");
    connectionImage.src = connection.profileImg;
    const connectionUsername = templateCard.querySelector(".connection-username");
    connectionUsername.textContent = connection.username;
    const connectionAction = templateCard.querySelector(".connection-action");
    connectionAction.textContent = connection.action;

    if (connection.unread) {
        templateCard.classList.add("unread");
    }

    const acceptIcon = templateCard.querySelector(".accept-icon");
    const declineIcon = templateCard.querySelector(".decline-icon");
    acceptIcon.addEventListener("click", () => {
        console.log(`Connection request from ${connection.username} accepted`);
        templateCard.style.display = "none";
    });
    declineIcon.addEventListener("click", () => {
        console.log(`Connection request from ${connection.username} declined`);
        templateCard.style.display = "none";
    });
};

const createConnections = (connectionsData) => {
    const connectionsContainer = document.getElementById("center");
    const templateCard = document.querySelector(".connection-card");
    if (!connectionsContainer || !templateCard) {
        console.error("Connection container or template card not found!");
        return;
    }
    connectionsData.forEach((connection) => {
        const newConnection = templateCard.cloneNode(true);
        newConnection.style.display = "block";
        populateConnectionCard(connection, newConnection);
        connectionsContainer.appendChild(newConnection);
    });
};

document.addEventListener('DOMContentLoaded', loadConnectionCards);
