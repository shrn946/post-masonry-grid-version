// JavaScript Document

jQuery(document).ready(function ($) {
    // Initialize Isotope
    var $grid = $('.grid').isotope({
        itemSelector: '.grid-item',
		

      
		
    });
	
    // Filter items on button click
    $('.filter').on('click', 'a', function () {
        var filterValue = $(this).attr('data-filter');
        $grid.isotope({ filter: filterValue });
    });
});
