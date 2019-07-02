<?php
function wordpress_image_gallery($atts = array(), $content = null, $tag) {
    
    /*
     * Some particular wordpress themes could move the output of the shortcode in a position that
     * could be different respect to that one where the shortcode is placed. In order to avoid this
     * situation we start to buffering the output.
     */
    ob_start();
    
    
    /*
     * Now we use the function getImagesArray() described below, that will retrieve from the remote endpoint
     * the pictures we want to display in the gallery.
     */
    $images = getImagesArray();
    
    /*
     * Here we create a container for our pictures that will be styled using CSS Flexible Box Layout which is
     * supported from the most actually used browsers and it has great flexibility in terms of responsive design 
     */
    echo('<div class="wpg-container">');
    for($i = 0; $i < sizeof($images);$i++)
    {
        
        /*
         * We are looping over the array we got from the remote endpoint, for each element we get the title
         * and the picture url 
         */
        $photoTitle = $images[$i]->title;
        $photoUrl = $images[$i]->url; 
        echo '<div class="wpg-item">';
        
        /*
         * the funcion do_we_want_to_show_title() is defined inside the wpg-option-page.php, this function
         * return the value of the checkbox inside the option page corresponding to the option "Show title for all images 
         * in the gallery" if the checkbox is checked it will return true, false otherwise. As default the function
         * return true, this is useful if the user has not configured the plugin using the option page, 
         * in this way when the function return true means that the title of the pictures should be displayed in the
         * gallery. If we want to hide the title we have simply uncheck the option inside the option page.
         */
        if(do_we_want_to_show_title()){
            echo('<span class="wpg-item-title">'.$photoTitle.'</span>');
        }
        echo '<img class="wpg-item-image" src="'.$photoUrl.'" alt="'.$photoTitle.'" title="'.$photoTitle.'" >';
        echo '</div>';
    }
    echo("</div>");

    /* at this point we stop to buffering the output and we will return it*/
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
}
add_shortcode( 'wordpress-image-gallery', 'wordpress_image_gallery' );

/*
 *  The function getImagesArray() will load from the option page how many pictures we have to retrieve from 
 *  the remote endpoint, as default I have set 10 picture, in this way if the plugin will be activated but
 *  not configured through the option page it will display 10 pictures.
 *  If the user configure the plugin using the option page it will display the quantity of pictures inserted by
 *  the user
 */
function getImagesArray(){
    
    /* Default quantity of pictures */
    $pictures_number = 10;
    
    /* 
     * We check if the user has inserted a different quantity, if yes we display the number of pictures 
     * choosed by the user
     */
    $pics_number = get_option('pictures-number') ;
    if(isset($pics_number)&&!empty($pics_number)){
        $pictures_number = $pics_number;
    }
    
    /*
     * Here we do the call to the remote endpoint, using the query string we pass as parameter _limit
     * in this way we get the exact quantity of pictures we want
     */
    $response = wp_remote_get( 'https://jsonplaceholder.typicode.com/photos?_limit='.$pictures_number );
    
    /*
     * If the call is successfull we will receive as response a json object with all the parameters
     * of the requested pictures
     */
    if ( is_array( $response ) ) {
        $body = $response['body'];
    }
    
    /*
    * Now we have to convert the json in an object we can manipulate, for this we use the json_decode
    * that will convert the json object received by the remote endpoint to an array of standard objects,
    * and we will return this array to the shortcode handler function that will use it to display the 
    * gallery 
    */
    $photoObj = json_decode($body);
    return $photoObj;
}