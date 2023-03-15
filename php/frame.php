<?php
/**
 * this class represents the outer frame of pages of the website
 *
 * @author hendrik
 */
abstract class PageFrame {
    /**
     * gets the default page in case none is specified.
     *
     * @return string name of default page
     */
    abstract function getDefaultPage();
    
    /**
     * this method can write additional http headers, for example for cache control.
     *
     * @param $page_url
     * @return true, to continue the rendering, false to not render the normal content
     */
    abstract function writeHttpHeader($page_url);
    
    /**
     * this method can write additional html headers.
     */
    abstract function writeHtmlHeader();
    
    /**
     * renders the frame
     */
    abstract function renderFrame();
    
    /**
     * includes java script libraries
     */
    public function includeJs() {
        echo '
            <!-- START: Scripts -->
            <!-- Object Fit Polyfill -->
            <script src="'.WEB_FOLDER.'/assets/vendor/object-fit-images/dist/ofi.min.js"></script>
            
            <!-- GSAP -->
            <script src="'.WEB_FOLDER.'/assets/vendor/gsap/src/minified/TweenMax.min.js"></script>
            <script src="'.WEB_FOLDER.'/assets/vendor/gsap/src/minified/plugins/ScrollToPlugin.min.js"></script>
            
            <!-- Popper -->
            <script src="'.WEB_FOLDER.'/assets/vendor/popper.js/dist/umd/popper.min.js"></script>
            
            <!-- Bootstrap -->
            <script src="'.WEB_FOLDER.'/assets/vendor/bootstrap/dist/js/bootstrap.min.js"></script>

            <!-- Blast -->
            <script src="'.WEB_FOLDER.'/assets/vendor/blast/blast.min.js"></script>
            
            <!-- Sticky Kit -->
            <script src="'.WEB_FOLDER.'/assets/vendor/sticky-kit/dist/sticky-kit.min.js"></script>
            
            <!-- Jarallax -->
            <script src="'.WEB_FOLDER.'/assets/vendor/jarallax/dist/jarallax.min.js"></script>
            <script src="'.WEB_FOLDER.'/assets/vendor/jarallax/dist/jarallax-video.min.js"></script>
            
            <!-- imagesLoaded -->
            <script src="'.WEB_FOLDER.'/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
            
            <!-- Flickity -->
            <script src="'.WEB_FOLDER.'/assets/vendor/flickity/dist/flickity.pkgd.min.js"></script>
            
            <!-- Photoswipe -->
            <script src="'.WEB_FOLDER.'/assets/vendor/photoswipe/dist/photoswipe.min.js"></script>
            <script src="'.WEB_FOLDER.'/assets/vendor/photoswipe/dist/photoswipe-ui-default.min.js"></script>

            <!-- Jquery Validation -->
            <script src="'.WEB_FOLDER.'/assets/vendor/jquery-validation/dist/jquery.validate.min.js"></script>

            <!-- Jquery Countdown + Moment -->
            <script src="'.WEB_FOLDER.'/assets/vendor/jquery-countdown/dist/jquery.countdown.min.js"></script>
            <script src="'.WEB_FOLDER.'/assets/vendor/moment/min/moment.min.js"></script>
            <script src="'.WEB_FOLDER.'/assets/vendor/moment-timezone/builds/moment-timezone-with-data.min.js"></script>
            
            <!-- Hammer.js -->
            <script src="'.WEB_FOLDER.'/assets/vendor/hammerjs/hammer.min.js"></script>
            
            <!-- NanoSroller -->
            <script src="'.WEB_FOLDER.'/assets/vendor/nanoscroller/bin/javascripts/jquery.nanoscroller.js"></script>
            
            <!-- SoundManager2 -->
            <script src="'.WEB_FOLDER.'/assets/vendor/soundmanager2/script/soundmanager2-nodebug-jsmin.js"></script>
            
            <!-- Seiyria Bootstrap Slider -->
            <script src="'.WEB_FOLDER.'/assets/vendor/bootstrap-slider/dist/bootstrap-slider.min.js"></script>
            
            <!-- Summernote -->
            <script src="'.WEB_FOLDER.'/assets/vendor/summernote/dist/summernote-bs4.min.js"></script>
            <script src="'.WEB_FOLDER.'/assets/vendor/summernote/dist/lang/summernote-pl-PL.min.js"></script>

            <!-- DataTables -->
            <script src="'.WEB_FOLDER.'/assets/vendor/datatables/js/datatables.min.js"></script>
            
            <!-- nK Share -->
            <script src="'.WEB_FOLDER.'/assets/plugins/nk-share/nk-share.js"></script>
            
            <!-- GoodGames -->
            <script src="'.WEB_FOLDER.'/assets/js/goodgames.min.js"></script>
            <script src="'.WEB_FOLDER.'/assets/js/goodgames-init.js"></script>

            <!-- CORE Stendhal scripts
            <script src="'.WEB_FOLDER.'/assets/js/stendhal-scripts.js"></script> -->
            <!-- CORE POL scripts -->
            <script src="'.WEB_FOLDER.'/assets/js/pol-scripts.js"></script>
            <!-- END: Scripts -->';
    }
}