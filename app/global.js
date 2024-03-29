//Location of API
let apiPath = "http://localhost/";

//Responsive top nav
function expandNav() {
  var x = document.getElementById("topnav");
  if (x.className === "topnav") {
    x.className += " responsive";
  } else {
    x.className = "topnav";
  }
}

//top nav active
function makeActive(page){
  $('#topnav').children().each(function() {
    $(this).removeClass("active");
  });

  $(page).addClass("active");
}

// change page title
function changePageTitle(page_title){
  // change page title
  $('#page-title').text(page_title);

  // change title tag
  document.title=page_title;
}

// remove any prompt messages
function clearResponse(){
    $('#response').html('');
}

//Function to populate alert message box
function responseAlert(alertType, message) {
  clearResponse();
  var html = `<div class='alert ` + alertType + `'><span class='closebtn' onclick='clearResponse();'>&times;</span>` + message + `</div>`;
  $('#response').html(html);
}

// function to make form values to json format
$.fn.serializeObject = function()
{
  var o = {};
  var a = this.serializeArray();
  $.each(a, function() {
    if (o[this.name] !== undefined) {
      if (!o[this.name].push) {
        o[this.name] = [o[this.name]];
      }
      o[this.name].push(this.value || '');
    } else {
      o[this.name] = this.value || '';
    }
  });
  return o;
};

// get or read cookie
function getCookie(cname){
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' '){
            c = c.substring(1);
        }

        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
