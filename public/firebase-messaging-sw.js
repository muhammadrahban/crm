importScripts('https://www.gstatic.com/firebasejs/8.2.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.2.1/firebase-messaging.js');
// importScripts('js/firebase.js');


const firebaseConfig = {
  apiKey: "AIzaSyAYu1IUU4ApL7i4zURnGuELuhk7HL0j4Dw",
  authDomain: "hpplus-7f28b.firebaseapp.com",
  projectId: "hpplus-7f28b",
  storageBucket: "hpplus-7f28b.appspot.com",
  messagingSenderId: "400696578342",
  appId: "1:400696578342:web:ecdd05cadb4f709c2a71e7",
  measurementId: "G-C8JXSYWRZM"
};

firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();


// [START on_background_message]
messaging.onBackgroundMessage(function(payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  // Customize notification here
  const notificationTitle = 'Background Message Title';
  const notificationOptions = {
    body: 'Background Message body.',
    icon: '/firebase-logo.png'
  };

  return self.registration.showNotification(notificationTitle,
    notificationOptions);
});
