/* eslint-disable semi, no-restricted-globals */
/* global importScripts, firebase */

importScripts('https://www.gstatic.com/firebasejs/10.7.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.7.0/firebase-messaging-compat.js');

firebase.initializeApp({
  apiKey: 'AIzaSyABEkHN2T00Lf7yQ0jFy_wU_Pzw5HeG3M4',
  authDomain: 'semsar-3df83.firebaseapp.com',
  databaseURL: 'https://semsar-3df83-default-rtdb.firebaseio.com',
  projectId: 'semsar-3df83',
  storageBucket: 'semsar-3df83.firebasestorage.app',
  messagingSenderId: '928729873625',
  appId: '1:928729873625:web:2f03f4c34ae7d3b56652c8',
  measurementId: 'G-CRMSQRHSSS'
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage(payload => {
  const notification = payload.notification || {};
  const title = notification.title || 'New notification';

  const options = {
    body: notification.body || '',
    icon: notification.icon || '/logo.png'
  };

  self.registration.showNotification(title, options);
});
