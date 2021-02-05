'use strict';

const request = require("superagent");

const appKey = "BCWfstHHTPcYu1LNGvBRAAwC2QNwud5hbx0lF9Bk4DR_td5c18yvwGcFa7R3cMjyq8LxF9rIvw2kdZcM0EJwFx4";

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}


function sendSubscriptionToServer(subscription) {
    var uri = location.protocol + '//' + location.host.replace("zhidao.", "") + '/subscribe';
    request('POST', uri).send({
        subscription: JSON.stringify(subscription)
    }).then(function(response) {

    });
}

function subscribe() {
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.ready.then(function (serviceWorkerRegistration) {
            serviceWorkerRegistration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(appKey)
            })
                .then(function (subscription) {
                    // TODO: Send the subscription subscription.endpoint
                    // to your server and save it to send a push message
                    // at a later date
                    return sendSubscriptionToServer(subscription);
                })
                .catch(function (e) {
                    if (Notification.permission === 'denied') {
                        // The user denied the notification permission which
                        // means we failed to subscribe and the user will need
                        // to manually change the notification permission to
                        // subscribe to push messages
                        console.log('Permission for Notifications was denied');
                    } else {
                        // A problem occurred with the subscription, this can
                        // often be down to an issue or lack of the gcm_sender_id
                        // and / or gcm_user_visible_only
                        console.log('Unable to subscribe to push.', e);
                    }
                });
        });
    } else {
        console.log("serviceWorker not support");
    }
}

// Once the service worker is registered set the initial state
function initialiseState() {
    // Are Notifications supported in the service worker?
    if (!('showNotification' in ServiceWorkerRegistration.prototype)) {
        console.log('Notifications aren\'t supported.');
        return;
    }

    // Check the current Notification permission.
    // If its denied, it's a permanent block until the
    // user changes the permission
    if (Notification.permission === 'denied') {
        console.log('The user has blocked notifications.');
        return;
    }

    // Check if push messaging is supported
    if (!('PushManager' in window)) {
        console.log('Push messaging isn\'t supported.');
        return;
    }

    // We need the service worker registration to check for a subscription
    navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {
        // Do we already have a push message subscription?
        serviceWorkerRegistration.pushManager.getSubscription()
            .then(function(subscription) {
                if (!subscription) {
                    // We aren’t subscribed to push, so set UI
                    // to allow the user to enable push
                    return;
                }
                // Keep your server in sync with the latest subscription
                sendSubscriptionToServer(subscription);
            })
            .catch(function(err) {
                console.log('Error during getSubscription()', err);
            });
    });
}


window.addEventListener('load', function () {
    subscribe();
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('//static.loocode.com/assets/js/serviceWorker.js').then(initialiseState);
    }
});


