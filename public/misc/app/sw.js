
var CACHE_NAME = 'my-site-cache-v26';
var urlsToCache = [
  '/public/misc/app/',
  '/public/misc/app/index.html',
  '/public/misc/app/style.css',
  '/public/misc/app/fastclick.js',
  '/public/misc/app/handlebars.min.js',
  '/public/misc/app/icon_font.css',
  '/public/misc/app/icon_font.woff2',
  '/public/misc/app/material.indigo-pink.min.css',
  '/public/misc/app/material.min.js',
  '/public/misc/app/simple-logo.png',
  '/public/misc/app/launcher-icon-1x.png',
  '/public/misc/app/launcher-icon-2x.png',
  '/public/misc/app/launcher-icon-4x.png',
  '/public/misc/app/ios-installA.png',
  '/public/misc/app/ios-installB.png',
  '/public/misc/app/android-install.png'
];

self.addEventListener('install', function(event) {
  // Perform install steps
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(function(cache) {
        console.log('Opened cache');
        return cache.addAll(urlsToCache);
      })
  );
});

self.addEventListener('fetch', function(event) {
  event.respondWith(
    caches.match(event.request)
      .then(function(response) {
        // Cache hit - return response
        if (response) {
          return response;
        }
        console.log(event.request);
        return fetch(event.request);
      }
    )
  );
});

self.addEventListener('activate', function(event) {

  var cacheWhitelist = [CACHE_NAME];

  event.waitUntil(
    caches.keys().then(function(cacheNames) {
      return Promise.all(
        cacheNames.map(function(cacheName) {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});