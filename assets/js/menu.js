/**
 * JS for the right side menu
 * It responsible for sending the ajax request to the server
 *
 * @package Testing Elevated
 */

(function(){
    class Menu {
        /**
         * Singleton instance
         */
        static instance = null;

        /**
         * Constructor
         */
        constructor() {
           if( ! Menu.instance ) {
              this.init();
           }

           Menu.instance = this;
        }

        /**
         * Initialize the menu
         */
        init() {
            this.menuButtonsID = [ 'start', 'commit', 'rollback' ];
            this.menuButtons = this.menuButtonsID.map( buttonId => new MenuButton(buttonId) );

            const { enabled } = testing_elevated_menu_object;

            if ( enabled ) {
                this.menuButtons.forEach( button => button.ID === 'start' ? button.setActive(true) : null );
            }
        }

        /**
         * Clear the active class from all the buttons
         */
        clearActiveClass() {
            this.menuButtons.forEach( button => button.setActive( false ) );
        }

        /**
         * Disable all the buttons
         *
         * @param {boolean} disabled
         */
        disableMenuButtons( disabled = true ) {
            this.menuButtons.forEach( button => button.setDisabled( disabled ) );
        }
    }

    class MenuButton {
        /**
         * Constructor
         *
         * @param {string} buttonId
         */
        constructor(buttonId) {
            this.ID = buttonId;
            this.button = document.getElementById('testing-elevated-' + this.ID);

            if( ! this.button ) {
                return;
            }

            this.disabled = false;
            this.action = this.button.dataset.action ?? '';
            this.button.addEventListener( 'click', this.buttonClick.bind(this) );
        }

        /**
         *
         */
        buttonClick(e) {
            e.preventDefault();
            if ( ! this.disabled && this.action ) {
                Menu.instance.disableMenuButtons( true );
                this.sendWPAjaxRequest();
            }
        }

        /**
         * Send WP ajax request to the server
         */
        sendWPAjaxRequest() {
            const { ajax_url, nonce } = testing_elevated_menu_object;

            // WordPress ajax request
            const xhr = new XMLHttpRequest();
            xhr.open( 'POST', ajax_url );
            xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
            xhr.onload = () => {
                if ( xhr.status >= 200 && xhr.status < 300 ) {
                   try{
                       const response = JSON.parse( xhr.responseText );
                       if ( response.success ) {
                           Menu.instance.clearActiveClass();
                           this.setActive( true );

                           // reload the page
                           location.reload();
                       }
                   } catch (e) {
                   }
                }

                Menu.instance.disableMenuButtons( false );
            }

            xhr.onerror = () => {
                // Menu.instance.disableMenuButtons( true );
            }

            xhr.send( `action=${this.action}&nonce=${nonce}` );
        }

        /**
         * Set the active class to the button
         */
        setActive( active ) {
            if ( active ) {
                this.button.classList.add( 'testing-elevated-menu__item--active' );
            } else {
                this.button.classList.remove( 'testing-elevated-menu__item--active' );
            }
        }

        /**
         * Disable the button
         */
        setDisabled( disabled ) {
            this.disabled = !!disabled;
            this.button.classList.toggle( 'testing-elevated-menu__item--disabled', this.disabled );
        }
    }

    new Menu();
})();
