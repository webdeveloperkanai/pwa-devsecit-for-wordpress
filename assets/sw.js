let deferredPrompt;

self.addEventListener('beforeinstallprompt', (event) => {
  // Prevent Chrome 76 and later from showing the mini-infobar
  event.preventDefault();

  // Stash the event so it can be triggered later.
  deferredPrompt = event;

  // Check if the app is already installed using getInstalledRelatedApps
  navigator.getInstalledRelatedApps().then((relatedApps) => {
    if (relatedApps.length > 0) {
      // The app is already installed, do not show the install prompt
      deferredPrompt = null;
    } else {
      // Optionally, show a button or other UI element to notify the user they can install the PWA.
      // For example, you can create a button with an id "installButton" in your HTML.

      // document.getElementById('installButton').style.display = 'block';
    }
  });
});

// Optional: You can also listen for the "appinstalled" event to detect when the app has been successfully installed.
self.addEventListener('appinstalled', (event) => {
  // Log to analytics or perform other tasks if needed.
});

self.addEventListener("install", function(event) {
    event.waitUntil(preLoad());
  });
  
  var preLoad = function(){
     console.log("Installing web app");
    return caches.open("offline").then(function(cache) {
       console.log("caching index and important routes");
      return cache.addAll(["/"]);
    });
  };
  
  self.addEventListener("fetch", function(event) {
    event.respondWith(checkResponse(event.request).catch(function() {
      return returnFromCache(event.request);
    }));
    event.waitUntil(addToCache(event.request));
  });
  
  var checkResponse = function(request){
    return new Promise(function(fulfill, reject) {
      fetch(request).then(function(response){
        if(response.status !== 404) {
          fulfill(response);
        } else {
          reject();
        }
      }, reject);
    });
  };
  
  var addToCache = function(request){
    return caches.open("offline").then(function (cache) {
      return fetch(request).then(function (response) {
         console.log(response.url + " was cached");
        return cache.put(request, response);
      });
    });
  };
  
  var returnFromCache = function(request){
      console.warn("Tryining to access offline.html")
    return caches.open("offline").then(function (cache) {
      return cache.match(request).then(function (matching) {
       if(!matching || matching.status == 404) {
         return cache.match("offline.html");
       } else {
         return matching;
       }
      });
    });
  };