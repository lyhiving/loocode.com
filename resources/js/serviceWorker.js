'use strict';

console.log(self);
console.log(fetch);

self.addEventListener('push', function (event) {
    console.log('Received a push message', event);
    var data = {};
    if (event.data !== null) {
        data = event.data.json();
        notification = self.registration.showNotification(data.title, {
            body: data.body,
            icon: data.icon,
            tag: data.tag
        });
    } else {
        notification = fetch("https://loocode.com/notification").then(function (response) {
            if (response.status !== 200) {
                onsole.log('Looks like there was a problem. Status Code: ' + response.status);
                throw new Error();
            }
            return response.json().then(function (data) {
                return self.registration.showNotification(data.title, {
                    body: data.body,
                    icon: data.icon,
                    tag: data.tag
                })
            });
        });
    }
    event.waitUntil(notification);
});

self.addEventListener('notificationclick', function (event) {
    console.log('On notification click: ', event.notification.tag);
    event.notification.close();

    event.waitUntil(clients.matchAll({
        type: 'window'
    }).then(function (clientList) {
        for (var i = 0; i < clientList.length; i++) {
            var client = clientList[i];
            if (client.url === '/' && 'focus' in client) {
                return client.focus();
            }
        }
        if (clients.openWindow) {
            return clients.openWindow('/');
        }
    }));
});

