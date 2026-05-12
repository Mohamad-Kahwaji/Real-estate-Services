<script type="module">
  import { initializeApp } from 'https://www.gstatic.com/firebasejs/10.7.0/firebase-app.js';
  import { getMessaging, getToken, onMessage } from 'https://www.gstatic.com/firebasejs/10.7.0/firebase-messaging.js';

  const firebaseConfig = {
    apiKey: "AIzaSyDhAYz3pjCi6IQA79Q75R-ZlE_VPkU0mi0",
    authDomain: "project-final-215df.firebaseapp.com",
    projectId: "project-final-215df",
    storageBucket: "project-final-215df.firebasestorage.app",
    messagingSenderId: "344799214618",
    appId: "1:344799214618:web:2de42f7fb05467a69365a3"
  };

  @if(auth('admins')->check() || auth('superadmins')->check())
  const FCM_ENDPOINT = '/fcm/admin-token';
  @elseif(auth('users')->check())
  const FCM_ENDPOINT = '/fcm/register-token';
  @else
  const FCM_ENDPOINT = null;
  @endif

  if (!FCM_ENDPOINT) {
    // not logged in, skip FCM
  } else {
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

        if (!token) return;

        const response = await fetch(FCM_ENDPOINT, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({ token })
        });

        console.log('SAVE TOKEN STATUS:', response.status);

      } catch (error) {
        console.error('FCM ERROR:', error.message);
      }
    }

    initFCM();

    onMessage(messaging, payload => {
      if (notificationApi && notificationApi.permission === 'granted') {
        new notificationApi(payload.notification?.title || 'New notification', {
          body: payload.notification?.body || '',
          icon: '/logo.png'
        });
      }

      window.dispatchEvent(new CustomEvent('fcm-message', { detail: payload }));
    });
  }
</script>
