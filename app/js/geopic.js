var results;
var geoData;

document.addEventListener("deviceready", onDeviceReady, false);

// device APIs are available
function onDeviceReady() {

  getGeo();

  $(document).keypress(function (e) {
    if(e.which == 13) {
      window.location.href='#results';
      getGeoResults(document.getElementById('searchGeo').value);
    }
  });

  //Responsive Fix
  if($(window).width() <= 375) {
    $('#describePicFieldSet').attr("data-mini", "true");
    $('.toolbar-capture').attr("data-iconpos", "notext");
    $('.toolbar-back').attr("data-iconpos", "notext");
  }

  function prevent_default(e) {
    e.preventDefault();
  }

  function disable_scroll() {
    $(document).on('touchmove', prevent_default);
  }

  function enable_scroll() {
    $(document).unbind('touchmove', prevent_default);
  }

  var xStart;
  var deltaX;
  //Don't add touch events on anchor elements, as it will trigger a click event instead
  $('#searchPanel ul li > div').on('touchstart', function(e){
   xStart = e.originalEvent.touches[0].pageX;
   deltaX = 0;
  })
  .on('touchmove', function (e) {
    deltaX = xStart - e.originalEvent.touches[0].pageX;
    //e.style.marginLeft = (xMove - xStart) + 'px';
    if (deltaX < 0) {
      e.currentTarget.style.right = deltaX + 'px';
      disable_scroll();
    }
  })
  .on('touchend', function(e) {
    var swipeDistance = $(window).width() * 0.3;
    if (Math.abs(deltaX) > swipeDistance) {
      //Show Results
      $(e.currentTarget).animate({ right: 0}, 600, 'easeOutBounce');
      window.location.href='#results';
      categoryNameToID(e.currentTarget.id);
    } else {
      //go back to 0
      $(e.currentTarget).animate({ right: 0}, 600, 'easeOutBounce');
    }
    enable_scroll();
  });
}

function showGeoResults(locality) {
  $('.masonry-grid').empty();
  //var $masonryGrid = $('.masonry-grid');

  for(var i=0; i < geoData.length; i++) {
    var obj = geoData[i];
    for(var key in obj) {
      if(obj.locality == locality.trim()) {
        if(key == 'filename') {
          $('.masonry-grid').append("<div class=\"grid-item w2\" onclick=\"bigImage('" + obj.filename + "', '" + obj.locality + "', '" + obj.categories + "');\"><img src=\"" + webService + obj.thumbname + "\" /></div>");
        }
      }
    }
  }
}

function getGeoResults(locality) {
  var resultsURL = webService + "/return.php?geolocation=true&callback=returnJSONP";
  $.jsonp({
    url: resultsURL,
    cache: false,
    callback: 'returnJSONP',
    dataType: 'jsonp',
    timeout: 5000,
    success: function(data, status) {
      //document.getElementById('location').value = data.resourceSets[0].resources[0].address.locality;
     geoData = data;
      showGeoResults(locality);
    },
    error: function(){
       //document.getElementById('location').value = 'Error: JSON Fail.';
    }
  });
}

function categoryNameToID(cat) {
  var catID = 1;
  switch(cat) {
    case 'search-food':
      catID = 1;
      break;
    case 'search-sports':
      catID = 2;
      break;
    case 'search-nature':
      catID = 3;
      break;
    case 'search-travel':
      catID = 4;
      break;
    case 'search-funny':
      catID = 5;
      break;
    case 'search-pets':
      catID = 6;
      break;
    default:
      catID = 1;
  }
  getResults(catID);
}


function getResults(catID) {
  var resultsURL = webService + "/return.php?category=" + catID +"&callback=returnJSONP";
  $.jsonp({
    url: resultsURL,
    cache: false,
    callback: 'returnJSONP',
    dataType: 'jsonp',
    timeout: 5000,
    success: function(data, status) {
        results = data;
        showResults();
      },
    error: function(){
    }
  });
}

function showResults() {
  $('.masonry-grid').empty();
  for(var i=0; i < results.length; i++) {
    var obj = results[i];
    for(var key in obj) {
      if(key == 'filename') {
        $('.masonry-grid').append("<div class=\"grid-item w2\" onclick=\"bigImage('" + obj.filename + "', '" + obj.locality + "', '" + obj.categories + "');\"><img src=\"" + webService + obj.thumbname + "\" /></div>");
      }
    }
  }
}


function bigImage(image, locality, categories) {
  $('.single-photo-category-header').html(locality);
  $('.single-photo-category').remove();
  $('.photo-single').attr("src", webService + image);
  $('.photo-single').css("style", "block");
  window.location.href='#single-result';
  var categoryArray = categories.split(",");
  var catID = 1;
  for(i = 0; i < categoryArray.length; i++) {
    switch(categoryArray[i]) {
      case 'Food':
        catID = 1;
        break;
      case 'Sports':
        catID = 2;
        break;
      case 'Nature':
        catID = 3;
        break;
      case 'Travel':
        catID = 4;
        break;
      case 'Humour':
        catID = 5;
        break;
      case 'Pets':
        catID = 6;
        break;
      default:
        catID = 1;
    }
    $('.single-photo-category-wrap')
        .append("<a href=\"#\" onclick=\"location.href='#results';getResults('" + catID + "');\" data-role=\"button\" class=\"single-photo-category\">" + categoryArray[i] + "</a>").trigger('create');
  }
}


 //Get GeoLocation name using Bing REST Services
function getLocationData(lat, lng) {
  var query =  lat + "," +  lng;
  var geoCodeURL = 'http://dev.virtualearth.net/REST/v1/Locations/' + query + '?o=json&jsonp=geoCallBack&key=' + bingKey;
  $.jsonp({
    url: geoCodeURL,
    cache: false,
    callback: 'geoCallBack',
    dataType: 'jsonp',
    timeout: 5000,
    success: function(data, status) {
      $('#location').html(data.resourceSets[0].resources[0].address.locality);
    },
    error: function(){
      $('#location').html('World');
    }
  });
}

function onSuccess(position) {
  getLocationData(position.coords.latitude, position.coords.longitude);
}

function getGeo() {
  var watchID = navigator.geolocation.getCurrentPosition(onSuccess, onFailure, { maximumAge: 5000, timeout: 50000, enableHighAccuracy: true });
}

// Called when a photo is successfully retrieved
function onPhotoDataSuccess(fileURI) {
 	$('.photo').attr("src", fileURI);
 	$('.photo').css("style", "block");

}

// A button will call this function
function capturePhoto() {
  navigator.camera.getPicture(onPhotoDataSuccess, onFailurePhoto, { quality: 50 });
}

function onFailurePhoto () {
  window.location.href='#MainPage';
}

function checkAndSave() {
  uploadPhoto($('.photo').attr("src"));
}

function uploadPhoto(imageURI) {
  var categories = [];
  $("input:checkbox[name=choice]:checked").each(function(){
    categories.push($(this).val());
  });

	var options = new FileUploadOptions();
	options.fileKey = "file";
	options.fileName = imageURI.substr(imageURI.lastIndexOf('/')+1);
	options.mimeType = "image/jpeg";

	var params = {};
  params.categories = categories.toString();
  params.locality = $('#location').html();
	options.params = params;
	options.chunkedMode = false;

	var ft = new FileTransfer();
	ft.upload(imageURI,  webService + "upload.php", uploadSuccess, onFailure, options);
  $('#describePic').append('<div class=\"load-screen\"><img src=\"img/loading.svg\" /></div>');
}

function uploadSuccess(r) {
	//alert("uploadSuccess" + r.response);
  location.href='#search';
  $('.load-screen').remove();
}

function onFailure(error) {
  $('.load-screen').remove();
  $('#location').html('Error: ' + error.message);
}
