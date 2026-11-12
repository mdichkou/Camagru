const video = document.getElementById('player');
const canvasElement = document.getElementById('canvas');
const save = document.getElementById("save-btn");
const upload = document.getElementById("upload-btn");
const effects = document.getElementById("effect");
const input = document.getElementById('inp');
const mask = document.getElementById('mask');
const myForm = document.getElementById('myForm');
const checkbox = document.getElementById('myCheckbox');
var check = true;
const constraints = {
  audio: false,
  video: {width: 490,
    height: 390
  }
};
async function init() {
  try {
    const stream = await navigator.mediaDevices.getUserMedia(constraints);
    check = true;
    input.disabled = true;
    upload.style.display = 'none';
    handleSuccess(stream);
  } catch (e) {
    console.log('camera not allowed');
  }
}
function actualise() {
  // location.reload();
}
var context = canvasElement.getContext('2d');
input.onchange = function(e) {
  var img = new Image();
  if(this.files[0].size > 3072000){
    alert("Image is too big!");
    this.value = "";
 } else
 {
  img.onload = draw;
  img.onerror = failed;
  effects.disabled = false;
  img.src = URL.createObjectURL(this.files[0]);
 }
};
function draw() {
  context.drawImage(this, 0,0,490,390);
}
function failed() {
  effects.disabled = true;
  alert("The provided file couldn't be loaded as an Image media");
}
function handleSuccess(stream) {
  window.stream = stream;
  canvasElement.style.display = 'none';
  effects.disabled = false;
  video.srcObject = stream;
}
function postpicture () {
  let picture = canvasElement.toDataURL();
  var http = new XMLHttpRequest();
  var params = 'data='+encodeURI(picture)+'&option='+encodeURI(effects.value);
  http.open('POST', "api/save_image.php", true);
  http.withCredentials = true;
  http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  http.onreadystatechange = function() {
    if(http.readyState == 4 && http.status == 200) {
    }
}
  http.send(params);
}
effects.onchange = function(){
  if (effects.value == "batman")
  {
    mask.style.visibility = 'visible';
    mask.setAttribute("src","img/batman.png");
    if (check == true)
      save.disabled = false;
    else
      upload.disabled = false;
  }
if (effects.value == "iron")
{
  mask.style.visibility = 'visible';
  mask.setAttribute("src","img/ironman.png");
  if (check == true)
      save.disabled = false;
    else
      upload.disabled = false;
}
if (effects.value == "joker")
{
  mask.style.visibility = 'visible';
  mask.setAttribute("src","img/joker.png");
  if (check == true)
      save.disabled = false;
    else
      upload.disabled = false;
}
if (effects.value == "off")
{
  mask.style.visibility = 'hidden';
  mask.setAttribute("src","");
      save.disabled = true;
      upload.disabled = true;
}
};
function OnChangeCheckbox (checkbox) {
  if (checkbox.checked) {
    video.style.display = 'none';
    mask.style.visibility = 'hidden';
    effects.selectedIndex = "0";
    mask.setAttribute("src","");
    save.disabled = true;
    upload.disabled = true;
    canvasElement.style.display = 'block';
    check = false;
    input.disabled = false;
    effects.disabled = true;
    upload.style.display = 'block';
    save.style.display = 'none';
  }
  else {
    video.style.display = 'block';
    save.style.display = 'block';
    effects.selectedIndex = "0";
    mask.style.visibility = 'hidden';
    save.disabled = true;
    input.value = "";
    context.clearRect(0, 0, canvas.width, canvas.height);
    upload.disabled = true;
    mask.setAttribute("src","");
    canvasElement.style.display = 'none';
    upload.style.display = 'none';
    check = true;
    input.disabled = true;
    effects.disabled = false;
  }
}
init();
save.addEventListener("click", function() {
context.drawImage(video, 0, 0);
postpicture();},false);
upload.addEventListener("click", postpicture,false);
