// module.js

// Function to gather token data from the active scene
function gatherTokenData() {
  const scene = game.scenes.active;
  if (!scene) {
    console.error("No active scene found.");
    return [];
  }

  const tokens = scene.tokens.contents;
  return tokens.map(token => {
    const actorData = token.actor.system;
    return {
      name: token.name,
      currentHP: actorData.attributes.hp.value,
      maxHP: actorData.attributes.hp.max
    };
  });
}

// Function to send player data to the external server
function sendPlayerData(data) {
  const url = 'https://beta.byteoverflow.de/foundry/pulldata.php'; // Replace with your PHP script URL
  fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(data)
  })
  .then(response => response.text()) // Get response as text first
  .then(responseText => {
    console.log('Response Text:', responseText); // Log the raw response text
    try {
      const data = JSON.parse(responseText);
      console.log('Success:', data);
    } catch (error) {
      console.error('Error parsing JSON:', error, 'Response Text:', responseText);
    }
  })
  .catch((error) => {
    console.error('Fetch Error:', error);
  });
}

// Initial data sending when the module is ready
Hooks.on('ready', () => {
  console.log('APIlib is up and running!');
  const data = gatherTokenData();
  sendPlayerData(data);
});

// Update data whenever an actor's HP changes
Hooks.on('updateActor', (actor, data, options, userId) => {
  // Check if HP was updated
  if (data.system && data.system.attributes && data.system.attributes.hp) {
    const updatedData = gatherTokenData();
    sendPlayerData(updatedData);
  }
});
