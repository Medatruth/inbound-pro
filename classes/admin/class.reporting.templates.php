<?php

/**
 *
 */

if ( !class_exists('Inbound_Reporting_Templates') ) {

    class Inbound_Reporting_Templates {

        /**
         * Inbound_Reporting_Templates constructor.
         */
        public function __construct() {

            /* Load and Dislay Correct Template */
            add_action('admin_init' , array( __CLASS__ , 'display_template' ) );

        }

        /**
         *
         */
        public static function display_template() {

            if ( !isset( $_REQUEST['action'] )  || $_REQUEST['action'] != 'inbound_generate_report' ) {
                return;
            }

            $_REQUEST['class']::load_template();
        }

        /**
         *    Prepares dates to process
         */
        public static function prepare_range( $range = 90 ) {

            global $post;

            $today = new DateTime(date_i18n('Y-m-d G:i:s T'));
            $dates['end_date'] = $today->format('Y-m-d G:i:s T');
            $today->modify('-'.$range.' days');
            $dates['start_date'] =  $today->format('Y-m-d G:i:s T');

            /* generate dates for previous date-range */
            $today->modify('-'.$range.' days');
            $dates['past_start_date'] = $today->format('Y-m-d G:i:s T');
            $dates['past_end_date'] = $dates['start_date'];

            return $dates;
        }

        /**
         * takes two dates formatted as YYYY-MM-DD and creates an
         * inclusive array of the dates between the from and to dates.
         * could test validity of dates here but I'm already doing
         * that in the main script
         * @param $start_time
         * @param $end_time
         * @return array
         */
        public static function get_days_from_range( $start_time , $end_time )      {

            $aryRange=array();

            $iDateFrom=mktime(1,0,0,substr($start_time,5,2),     substr($start_time,8,2),substr($start_time,0,4));
            $iDateTo=mktime(1,0,0,substr($end_time,5,2),     substr($end_time,8,2),substr($end_time,0,4));

            if ($iDateTo>=$iDateFrom)
            {
                array_push($aryRange,date('Y-m-d',$iDateFrom));
                while ($iDateFrom<$iDateTo)
                {
                    $iDateFrom+=86400; // add 24 hours
                    array_push($aryRange,date('Y-m-d',$iDateFrom));
                }
            }

            return $aryRange;
        }


        /**
         *
         */
        public static function display_filters() {
            ?>
            <div class="report-filters">
                <?php

                if (isset($_REQUEST['range'])) {
                    ?>
                    <div class="tag tag-range">&nbsp;
                        <?php echo intval($_REQUEST['range']) .' '. __( 'days' , 'inboud-pro'); ?> &nbsp; <i class="fa fa-calendar" aria-hidden="true"></i>
                    </div>
                    <?php
                }
                if (isset($_REQUEST['page_id'])) {
                    ?>
                    <div class="tag"><span><?php _e('page id' , 'inbound-pro'); ?></span>
                        <?php echo intval($_REQUEST['page_id']); ?> <i class="fa fa-tag" aria-hidden="true"></i>
                    </div>
                    <?php
                }
                if (isset($_REQUEST['lead_id']) && $_REQUEST['lead_id'] ) {
                    ?>
                    <div class="tag"><span><?php _e('lead id' , 'inbound-pro'); ?></span>
                        <?php echo intval($_REQUEST['lead_id']); ?> <i class="fa fa-tag" aria-hidden="true"></i>
                    </div>
                    <?php
                }
                if (isset($_REQUEST['lead_uid'])) {
                    ?>
                    <div class="tag"><span><?php _e('lead uid' , 'inbound-pro'); ?></span>
                        <?php echo sanitize_text_field($_REQUEST['lead_uid']); ?> <i class="fa fa-tag" aria-hidden="true"></i>
                    </div>
                    <?php
                }
                if (isset($_REQUEST['source'])) {
                    ?>
                    <div class="tag"><span><?php _e('source' , 'inbound-pro'); ?></span>
                        <?php echo sanitize_text_field($_REQUEST['source']); ?> <i class="fa fa-tag" aria-hidden="true"></i>
                    </div>
                    <?php
                }
                ?>
            </div>
            <style type="text/css">
                .tag {
                    height:12px;
                    background: none repeat scroll 0 0 skyblue;
                    border-radius: 2px;
                    color: white;
                    cursor: default;
                    display: inline-block;
                    position: relative;
                    white-space: nowrap;
                    padding: 6px 7px 4px 0;
                    margin: 5px 10px 0 0;
                    font-size:10px
                }

                .tag-range {
                    background-color:darkgray !important;
                }

                .tag span {
                    background: none repeat scroll 0 0 gainsboro;
                    border-radius: 2px 0 0 2px;
                    margin-right: 5px;
                    padding: 6px 10px 5px;
                }

                .fa-tag {
                    margin-left:4px;
                }
                .report-filters {
                    margin-left:10px;
                    margin-right:10px;
                }
            </style>
            <?php
        }
    }

    new Inbound_Reporting_Templates;
}