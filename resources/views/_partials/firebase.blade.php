<script type="module">
  import { initializeApp } from 'https://www.gstatic.com/firebasejs/10.7.0/firebase-app.js';
  import { getMessaging, getToken, onMessage } from 'https://www.gstatic.com/firebasejs/10.7.0/firebase-messaging.js';

  console.log('FCM NEW CODE LOADED');

  const firebaseConfig = {
    apiKey: "AIzaSyDhAYz3pjCi6IQA79Q75R-ZlE_VPkU0mi0",
    authDomain: "project-final-215df.firebaseapp.com",
    projectId: "project-final-215df",
    storageBucket: "project-final-215df.firebasestorage.app",
    messagingSenderId: "344799214618",
    appId: "1:344799214618:web:2de42f7fb05467a69365a3"
  };

  const app = initializeApp(firebaseConfig);
  const messaging = getMessaging(app);
  const notificationApi = window.Notification;

  async function initFCM() {
    try {
      const registration = await navigator.serviceWorker.register('/firebase-messaging-sw.js');
      console.log('SW registered:', registration);

      const permission = await notificationApi.requestPermission();
      console.log('Notification permission:', permission);

      if (permission !== 'granted') return;

      const token = await getToken(messaging, {
        vapidKey: 'BJT2LDZd9cC_x5LXYYof0T6KyHByepIFVPBhisk9hY_dMd5hWSJSj7Z7vqFiOrTrT1vJ_5E0sSUC7g5RBba-O6U',
        serviceWorkerRegistration: registration
      });

      console.log('FCM Token:', token);

      const response = await fetch('/fcm/admin-token', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          token: token
        })
      });

      const text = await response.text();
      console.log('SAVE TOKEN STATUS:', response.status);
      console.log('RAW RESPONSE:', text);

    } catch (error) {
      console.error('FCM ERROR:', error);
      console.error('FCM ERROR NAME:', error.name);
      console.error('FCM ERROR MESSAGE:', error.message);
      console.error('FCM ERROR CODE:', error.code);
    }
  }

  initFCM();

  onMessage(messaging, payload => {
    console.log('FCM Foreground:', payload);

    if (notificationApi && notificationApi.permission === 'granted') {
      new notificationApi(payload.notification?.title || 'New notification', {
        body: payload.notification?.body || '',
        icon: '/logo.png'
      });
    }

    window.dispatchEvent(
      new CustomEvent('fcm-message', {
        detail: payload
      })
    );
  });
</script>
