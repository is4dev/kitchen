/*
 * Please see the included README.md file for license terms and conditions.
 */


// This file is a suggested starting place for your code.
// It is completely optional and not required.
// Note the reference that includes it in the index.html file.


/*jslint browser:true, devel:true, white:true, vars:true */
/*global $:false, intel:false app:false, dev:false, cordova:false */


// For improved debugging and maintenance of your app, it is highly
// recommended that you separate your JavaScript from your HTML files.
// Use the addEventListener() method to associate events with DOM elements.
// For example:

// var el ;
// el = document.getElementById("id_myButton") ;
// el.addEventListener("click", myEventHandler, false) ;



// The function below is an example of a way to "start" your app. if you convert
// your app to a Cordova app, this function will call the standard Cordova
// "hide splashscreen" function. If this is a web app that does not use Cordova
// this function is quietly ignored and does nothing.

// You can add other code to this function or add additional functions that are
// triggered by the same event. The app.Ready event used here is created by the
// init-dev.js file. It serves as a unifier for a variety of "ready" events.
// See the init-dev.js file for more details. If you prefer to use other events
// to start your app, you can use those in addition to, or instead of this event.

// NOTE: change "dev.LOG" in "init-dev.js" to "true" to enable some console.log
// messages that can help you debug app initialization issues.

function onAppReady() {
    if( navigator.splashscreen && navigator.splashscreen.hide ) {   // Cordova API detected
        navigator.splashscreen.hide() ;
    }
}
document.addEventListener("app.Ready", onAppReady, false) ;

/*
function callKitchen()
{
    var url = "http://kirdyk.radier.ca/api/messages/442576";
    var apiCall = $.get(url, function(data) {kitchenCallback(data);}); 
}

function kitchenCallback(payload) 
{
    var data = $.parseJSON(payload);    
    alert(data.subject);
}

*/

function callKitchen()
{
    var url = "http://kirdyk.radier.ca/api/threads";
    var apiCall = $.get(url, function(data) {kitchenCallback(data);}); 
}

function kitchenCallback(payload) 
{
    var data = $.parseJSON(payload);
    for(i=0; i<data.count; i++)
    {
        var subj = data.threads[i].message.subject;
        //check the number of replies. if >0 then render the badge
        var badgeHtml ="";
        if(data.threads[i].counter > 0)
        {
            badgeHtml= "<span class='af-badge tl'>"+data.threads[i].counter+"</span>";
        }
        //Append the title to the list
        var li = document.createElement('li');
        li.setAttribute('class','widget uib_w_7');
        li.setAttribute('data-uib','app_framework/listitem');
        li.innerHTML= badgeHtml +  "<a href='#'>"+subj+"<br /><b>"+data.threads[i].message.author.name+"</b></a>";        
        var listStuff = document.getElementById("stuffs_list");
        listStuff.appendChild(li);

        
    }
    
}




