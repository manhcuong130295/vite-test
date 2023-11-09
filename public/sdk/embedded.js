(function () {
  const scriptTag = document.currentScript;
  const projectId = scriptTag.getAttribute('projectId');
  const domain    = scriptTag.getAttribute('domain');

  const style = document.createElement('style');
  style.innerHTML = `
    .hidden-mgpt-embedded {
      display: none;
    }
    @media (max-width: 768px) {
      #chatIframeMgpt {
        width: 100%!important;
        height: 88%!important;
        max-height: 100%!important;
        right: 0!important;
        top: 0!important;
        border-radius: unset!important;
      }
    }
  `;
  document.head.appendChild(style);

  const iframe = document.createElement('iframe');
  iframe.id = 'chatIframeMgpt';
  iframe.style = "border: none; position: fixed; flex-direction: column; justify-content: space-between; box-shadow: rgba(150, 150, 150, 0.2) 0px 10px 30px 0px, rgba(150, 150, 150, 0.2) 0px 0px 0px 1px; bottom: 5rem; right: 1rem; width: 448px; height: 85vh; max-height: 824px; border-radius: 0.75rem; z-index: 2147483646; overflow: hidden; left: unset;";
  iframe.scrolling= "yes";
  iframe.className = 'hidden-mgpt-embedded';
  iframe.src = `${domain}/sdk/embedded.html?projectId=${projectId}`;
  document.body.appendChild(iframe);

  // Create chat widget container
  const chatWidgetContainer = document.createElement("div");
  chatWidgetContainer.id = "chatmgpt-bubble-button";
  document.body.appendChild(chatWidgetContainer);

  // Inject the HTML
  chatWidgetContainer.innerHTML = `
    <div style="position: fixed; bottom: 1rem; right: 1rem; width: 50px; height: 50px; border-radius: 25px; background-color: rgb(0, 0, 0); box-shadow: rgba(0, 0, 0, 0.2) 0px 4px 8px 0px; cursor: pointer; z-index: 2147483645; transition: all 0.2s ease-in-out 0s; left: unset; transform: scale(1);">
      <div style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; z-index: 2147483646;">
        <svg id="chatIconMgpt" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.3" stroke="white" width="24" height="24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 011.037-.443 48.282 48.282 0 005.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"></path>
        </svg>
        <svg id="closeIconMgpt" class="hidden-mgpt-embedded" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.3" stroke="white" width="24" height="24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
        </svg>
      </div>
    </div>
  `;

  const chatBubble = document.getElementById('chatmgpt-bubble-button');
  const chatIconMgpt = document.getElementById('chatIconMgpt');
  const closeIconMgpt = document.getElementById('closeIconMgpt');
  var chatIframeMgpt = document.getElementById('chatIframeMgpt');
  chatBubble.addEventListener('click', function() {
    togglePopup();
  });

  function togglePopup() {
    chatIconMgpt.classList.toggle('hidden-mgpt-embedded');
    closeIconMgpt.classList.toggle('hidden-mgpt-embedded');
    chatIframeMgpt.classList.toggle('hidden-mgpt-embedded');
  }
})();
