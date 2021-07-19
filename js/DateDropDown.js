var YearFounded = document.getElementById('YearFounded');
var date = new Date();
var year = date.getFullYear();
for (var i = year - 25; i <= year + 30; i++) {
  var option = document.createElement('option');
  option.value = option.innerHTML = i;
  if (i === year) option.selected = true;
  YearFounded.appendChild(option);
};
// document.body.YearFounded.appendChild(select);