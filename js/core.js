/* CLASS AND FOCUS ON CLICK */

$('.toggle').on('click', function() {
  $( '.data-table__account-wrapper.active' ).removeClass('active');
  $(this).closest( '.data-table__account-wrapper' ).addClass('active');
})

$.fn.toggleText = function(t1, t2){
  if (this.text() == t1) this.text(t2);
  else                   this.text(t1);
  return this;
};

$('.data-toggle').on('click', function() {
    $('.main-content').toggleClass('show-chart');
    $(this).toggleText('View Charts', 'View Tables');
})
