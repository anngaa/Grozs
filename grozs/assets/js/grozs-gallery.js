jQuery(document).ready(function ($) {

  // =========================
  // === GALERIJA + PREV/NEXT + LIGHTBOX FIX ===
  // =========================

  $('.grozs-gallery-widget').each(function () {
    var $widget        = $(this);
    var $mainLink      = $widget.find('.grozs-gallery-main');
    var $mainImg       = $mainLink.find('img');
    var $thumbs        = $widget.find('.grozs-gallery-thumb');
    var $thumbWrappers = $widget.find('.grozs-gallery-thumb-wrapper');
    var images         = $thumbs.map(function () {
      return $(this).attr('src');
    }).get();
    var currentIndex   = 0;
    var group          = 'product-gallery-' + Math.random().toString(36).substr(2, 5);

    // ◼️ Funkcija, kas pārbūvē lightbox <a> tagus
    function rebuildAnchors() {
	  // Saglabā esošo .active thumb index
	  var $activeThumb = $thumbs.filter('.active');
	  var activeIndex = $thumbs.index($activeThumb);

	  // Notīra visus a tagus un saglabā active statusu
	  $thumbWrappers.each(function (index) {
		$(this).find('a').remove();
		$thumbs.eq(index).removeClass('active');
	  });

	  // Atjauno .active klasei tikai aktuālajam thumb
	  $thumbs.eq(currentIndex).addClass('active');

	  // Lielās bildes <a> atribūti
	  $mainLink
		.attr('href', images[currentIndex])
		.attr('data-elementor-open-lightbox', 'yes')
		.attr('data-elementor-lightbox-slideshow', group);

	  // Pievieno <a> tikai pārējiem thumbnailiem
	  for (var i = 0; i < images.length; i++) {
		if (i !== currentIndex) {
		  $('<a>')
			.attr('href', images[i])
			.attr('data-elementor-open-lightbox', 'yes')
			.attr('data-elementor-lightbox-slideshow', group)
			.css('display', 'none')
			.appendTo($thumbWrappers.eq(i));
		}
	  }
	}

    // ◼️ Funkcija, kas pārslēdz galveno attēlu
    function updateMain(index) {
      if (index < 0) index = images.length - 1;
      if (index >= images.length) index = 0;
      currentIndex = index;

      var src = images[index];
      $mainImg.attr('src', src);
      $mainLink.attr('href', src);
      $mainLink.css('background-image', 'url("' + src + '")');
      $thumbs.removeClass('active').eq(index).addClass('active');

      rebuildAnchors();
    }

    // Inicializācija
    updateMain(0);

    // === SWIPE uz galvenā attēla ===
    var touchStartX = 0;
    var touchEndX = 0;
    var swipeThreshold = 50;

    $mainLink.on('touchstart', function (e) {
      touchStartX = e.originalEvent.touches[0].clientX;
    });
    $mainLink.on('touchend', function (e) {
      touchEndX = e.originalEvent.changedTouches[0].clientX;
      handleSwipe();
    });

    function handleSwipe() {
      if (touchEndX < touchStartX - swipeThreshold) {
        $next.click();
      } else if (touchEndX > touchStartX + swipeThreshold) {
        $prev.click();
      }
    }

    // Zoom efekts
    /*
    $mainLink.on('mousemove', function (e) {
      $mainLink.css('background-size', '200%');
      var offset = $mainLink.offset();
      var x = e.pageX - offset.left;
      var y = e.pageY - offset.top;
      var xP = (x / $mainLink.width()) * 100;
      var yP = (y / $mainLink.height()) * 100;
      $mainLink.css('background-position', xP + '% ' + yP + '%');
    }).on('mouseleave', function () {
      $mainLink.css({
        'background-position': 'center center',
        'background-size': 'cover'
      });
    });
    */

    // Thumbnail klikšķis
    $thumbs.on('click', function (e) {
      e.preventDefault();
      var idx = $thumbs.index(this);
      updateMain(idx);
    });

    // Prev / next pogas
    var $prev = $('<button class="grozs-gallery-prev">←</button>');
    var $next = $('<button class="grozs-gallery-next">→</button>');
    $mainLink.css('position','relative').append($prev, $next);

    $prev.on('click', function (e) {
      e.preventDefault(); e.stopPropagation();
      updateMain(currentIndex - 1);
    });

    $next.on('click', function (e) {
      e.preventDefault(); e.stopPropagation();
      updateMain(currentIndex + 1);
    });

  });

});
