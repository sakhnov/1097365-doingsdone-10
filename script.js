'use strict';

var expandControls = document.querySelectorAll('.expand-control');

var hidePopups = function() {
  [].forEach.call(document.querySelectorAll('.expand-list'), function(item) {
    item.classList.add('hidden');
  });
};

document.body.addEventListener('click', hidePopups, true);

[].forEach.call(expandControls, function(item) {
  item.addEventListener('click', function() {
    item.nextElementSibling.classList.toggle('hidden');
  });
});

var $checkbox = document.getElementById('show-completed-tasks');
var completionTogglers = document.querySelectorAll('.js-completion-toggler');

if($checkbox) {
  $checkbox.addEventListener('change', function(event) {
    var is_checked = +event.target.checked;

    window.location = '/index.php?show_completed=' + is_checked;
  });
}

[].forEach.call(completionTogglers, function(toggler) {
  toggler.addEventListener('change', function(evt) {
    var is_checked = +evt.target.checked;

    window.location = '/index.php?complete_task=' + is_checked + '&task_id=' + evt.target.dataset.taskId;
  });
});





var $checkbox = document.getElementsByClassName('show_completed');

if ($checkbox.length) {
  $checkbox[0].addEventListener('change', function (event) {
    var is_checked = +event.target.checked;

    var searchParams = new URLSearchParams(window.location.search);
    searchParams.set('show_completed', is_checked);

    window.location = '/index.php?' + searchParams.toString();
  });
}

flatpickr('#date', {
  enableTime: false,
  dateFormat: "Y-m-d",
  locale: "ru"
});
