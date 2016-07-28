var czrapp = czrapp || {};
/************************************************
* MASONRY GRID SUB CLASS
*************************************************/
/* 
* In this script we fire the grid masonry on the grid only when all the images
* therein are fully loaded in case we're not using the images on scroll loading
* Imho would be better use a reliable plugin like imagesLoaded (from the same masonry's author)
* which addresses various cases, failing etc, as it is not very big. Or at least dive into it
* to see if it really suits our needs.
*
* We can use different approaches while the images are loaded:
* 1) loading animation
* 2) display the grid in a standard way (organized in rows) and modify che html once the masonry is fired.
* 3) use namespaced events
* This way we "ensure" a compatibility with browsers not running js
*
* Or we can also fire the masonry at the start and re-fire it once the images are loaded
*/
(function($, czrapp) {
  var _methods =  {

    init : function() {
      /*
      * TODO:
      * - use delegation for images (think about infinite scroll)
      * - use jQuery deferred (think about infinite scroll)
      */
      this.grid = $('.grid.masonry-grid');
      this._images = this.grid.find('img');

      this._loaded_counter = 0;
      this._n_images = this._images.length;

    },
    masonryGridEventListener : function() {
      //LOADING ACTIONS
      var self = this;

      if ( ! this._n_images )
        return;      
      
      this.grid.on( 'images_loaded', function(){ self._czrFireMasonry(); });
      this._images.on( 'simple_load', function(){ self._czrMaybeTriggerImagesLoaded(); });
      
      //Dummy, for testing purpose only
      this.triggerSimpleLoad( this._images );
    },

    _czrFireMasonry : function() {
      this.grid.masonry({
          itemSelector: '.grid-item',
          percentPosition: true
      });
    },

    _czrMaybeTriggerImagesLoaded : function() {

      this._loaded_counter++;;
      if ( this._loaded_counter == this._n_images )
        this.grid.trigger('images_loaded');
    }
  };//_methods{}

  czrapp.methods.Czr_MasonryGrid = {};
  $.extend( czrapp.methods.Czr_MasonryGrid , _methods );  
})(jQuery, czrapp);