<?php
/**
 * Template file for displaying the fulfillment timeline on the product page.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="shipping-info">
    <h3><?php _e( "Estimated arrival", "ecom" ); ?></h3>
    <div class="fulfillment_timeline_date">
        <div class="time">
            <div class="icon-holder">
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none"
                        aria-hidden="true" focusable="false">
                        <path
                            d="M10.3126 10.2524L12.1726 6.80245L10.8451 5.48245L7.53761 2.17495C7.43156 2.07363 7.29053 2.01709 7.14386 2.01709C6.99719 2.01709 6.85616 2.07363 6.75011 2.17495C6.65938 2.27881 6.60938 2.41204 6.60938 2.54995C6.60938 2.68786 6.65938 2.82109 6.75011 2.92495L9.84761 6.49495L9.31511 7.02745L4.89011 2.70745C4.83647 2.6537 4.77276 2.61106 4.70262 2.58197C4.63248 2.55288 4.55729 2.53791 4.48136 2.53791C4.40543 2.53791 4.33024 2.55288 4.2601 2.58197C4.18996 2.61106 4.12625 2.6537 4.07261 2.70745V2.70745C3.97079 2.81475 3.91404 2.95703 3.91404 3.10495C3.91404 3.25287 3.97079 3.39515 4.07261 3.50245L8.21261 8.12995L7.68761 8.66245L3.82511 4.82995C3.77306 4.77213 3.70945 4.7259 3.63838 4.69425C3.56732 4.66261 3.4904 4.64625 3.41261 4.64625C3.33482 4.64625 3.2579 4.66261 3.18683 4.69425C3.11577 4.7259 3.05215 4.77213 3.00011 4.82995V4.82995C2.90938 4.93381 2.85938 5.06704 2.85938 5.20495C2.85938 5.34286 2.90938 5.47609 3.00011 5.57995L6.60011 9.74995L6.07511 10.2824L3.00011 7.67995C2.89556 7.59068 2.76259 7.54163 2.62511 7.54163C2.48763 7.54163 2.35466 7.59068 2.25011 7.67995V7.67995C2.19636 7.73359 2.15373 7.7973 2.12463 7.86744C2.09554 7.93758 2.08057 8.01277 2.08057 8.0887C2.08057 8.16463 2.09554 8.23982 2.12463 8.30996C2.15373 8.38009 2.19636 8.44381 2.25011 8.49745L5.02511 11.2649L9.27011 15.5099C9.40942 15.6494 9.57485 15.7601 9.75695 15.8355C9.93904 15.911 10.1342 15.9499 10.3314 15.9499C10.5285 15.9499 10.7237 15.911 10.9058 15.8355C11.0879 15.7601 11.2533 15.6494 11.3926 15.5099L15.1426 11.7974L15.5476 11.3924C15.7446 11.195 15.8827 10.9465 15.9463 10.6749C16.0099 10.4033 15.9965 10.1194 15.9076 9.85495L13.5001 3.88495L13.0501 4.03495C12.9466 4.07147 12.8557 4.13672 12.7879 4.22305C12.7202 4.30939 12.6785 4.41324 12.6676 4.52245L12.9676 7.59745L10.8451 10.7849L10.3126 10.2524Z"
                            fill="#222222"></path>
                    </svg>

                </span>
            </div>
            <p class="date"><?php echo $date_start; ?></p>
            <button class="edd-description" aria-describedby="tooltip"
                data-tippy-content="<?php echo esc_attr( $start_description ); ?>"><?php _e( "Order placed", "ecom" ); ?></button>
        </div>
        <div class="time">
            <div class="icon-holder">
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path
                            d="M21.868,11.5l-4-7A1,1,0,0,0,17,4H5A1,1,0,0,0,4,5V6H2A1,1,0,1,0,2,8H6a1,1,0,0,1,0,2H3a1,1,0,0,0,0,2H5a1,1,0,1,1,0,2H4v3a1,1,0,0,0,1,1H6.05a2.5,2.5,0,0,0,4.9,0h4.1a2.5,2.5,0,0,0,4.9,0H21a1,1,0,0,0,1-1V12A1,1,0,0,0,21.868,11.5ZM8.5,19A1.5,1.5,0,1,1,10,17.5,1.5,1.5,0,0,1,8.5,19Zm5.488-8V6h1.725l2.845,5h-4.57ZM17.5,19A1.5,1.5,0,1,1,19,17.5,1.5,1.5,0,0,1,17.5,19Z">
                        </path>
                    </svg>

                </span>
            </div>
            <p class="date"><?php echo $date_ship; ?></p>
            <button class="edd-description" aria-describedby="tooltip"
                data-tippy-content="<?php echo esc_attr( $shipping_description ); ?>"><?php _e( "Order ships", "ecom" ); ?></button>
        </div>
        <div class="time">
            <div class="icon-holder">
                <span class="icon icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path
                            d="M21,9.25A1.25,1.25,0,0,0,19.75,8H12.41l4.29-4.29a1,1,0,0,0-1.41-1.41L12,5.59,10.71,4.29A1,1,0,0,0,9.29,5.71L11.59,8H4.25A1.25,1.25,0,0,0,3,9.25V15H4v5.75A1.25,1.25,0,0,0,5.25,22h13.5A1.25,1.25,0,0,0,20,20.75V15h1ZM19,10v3H13V10ZM5,10h6v3H5ZM6,20V15h5v5Zm12,0H13V15h5Z">
                        </path>
                    </svg>

                </span>
            </div>
            <p class="date"><?php echo $date_delivered; ?></p>
            <button class="edd-description" aria-describedby="tooltip"
                data-tippy-content="<?php echo esc_attr( $delivery_description ); ?>"><?php _e( "Delivered!", "ecom" ); ?></button>
        </div>
    </div>
</div>