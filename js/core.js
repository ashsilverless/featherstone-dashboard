/* CLASS AND FOCUS ON CLICK */

$('.toggle').on('click', function() {
  $( '.data-table__account-wrapper.active' ).removeClass('active');
  $(this).closest( '.data-table__account-wrapper' ).addClass('active');
})
var dataTable = $('.data-table-toggle');
dataTable.on('click', function() {
$('.data-section.active').removeClass('active');
$('.data-section').addClass('active');

})
