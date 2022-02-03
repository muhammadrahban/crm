
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
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

//   messaging ( app ? :  App ) : Messaging;
const messaging = firebase.messaging();
    messaging

    .requestPermission()
    .then(function(){

        console.log("Notification Permission Granted!");
        return messaging.getToken();

    }).then(function(token){
        $('#device_token').val(token);
        console.log(token);

    }).catch(function(err){
        console.log("Unable to get permission to notify.",err);

    });

        // Message on Payload
messaging.onMessage((payload) => {
    console.log(payload);
}, e => {
    console.log(e)
  })
