const CACHE_NAME = 'panel-cache-v1';
const ASSETS_TO_CACHE = [
    '/build/manifest.json',
    // Add other critical assets here if needed, but Vite's hashed assets change names
];

self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});

self.addEventListener('fetch', (event) => {
    // Basic fetch strategy or just pass through for now
    // logic can be expanded for offline capabilities
});
