$(document).ready(function() {
  // disable the developer tool on right click
  document.oncontextmenu = function() { return false; };

  // disable F12 key OR Ctrl+Shift+I OR Ctrl+Shift+J OR Ctrl+U combo
  document.addEventListener('keydown', event => {
    if (event.keyCode === 123 
      || (event.ctrlKey && event.shiftKey && event.keyCode === 73) 
      || (event.ctrlKey && event.shiftKey && event.keyCode === 74)
      || (event.ctrlKey && event.keyCode === 85)) {
      event.preventDefault();
    }
  });
});