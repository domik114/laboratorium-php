document.addEventListener('keydown', function (event) {
  var key = event.key;
  var date = new Date;
  var timestamp = date.toLocaleString('pl-PL', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', second: '2-digit' });
  var ms = date.getMilliseconds();
  console.log(`Timestamp: ${timestamp}.${ms}. Key pressed: ${key}`);
});


