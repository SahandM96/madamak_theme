    <?php
/* Get the tickets object */
global $wpas_tickets;

if ( $wpas_tickets->have_posts() ):

	/* Get list of columns to display */
	$columns 		  = wpas_get_tickets_list_columns();
	
	/* Get number of tickets per page */
	$tickets_per_page = wpas_get_option( 'tickets_per_page_front_end' );
	If ( empty($tickets_per_page) ) {
		$tickets_per_page = 5 ; // default number of tickets per page to 5 if no value specified.
	}
	
	?>
	<div class="ticket-list-container">

		<?php //wpas_get_template( 'partials/ticket-navigation' ); ?>

		<!-- List of tickets -->
		<div id="ticketlist" class="wpas-table wpas-table-hover" data-filter="#wpas_filter" data-filter-text-only="true" data-page-navigation=".wpas_table_pagination" data-page-size=" <?php echo $tickets_per_page ?> ">
			<div class="table-head">
				<?php foreach ( $columns as $column_id => $column ) {

					$data_attributes = '';

					// Add the data attributes if any
					if ( isset( $column['column_attributes']['head'] ) && is_array( $column['column_attributes']['head'] ) ) {
						$data_attributes = wpas_array_to_data_attributes( $column['column_attributes']['head'] );
					}

					printf( '<p id="wpas-ticket-%1$s" %3$s>%2$s</p>', $column_id, $column['title'], $data_attributes );

				} ?>
			</div>
			<div class="table-body">
				<?php
				while( $wpas_tickets->have_posts() ):

					$wpas_tickets->the_post();

					echo '<div class="ticket wpas-status-' . wpas_get_ticket_status( $wpas_tickets->post->ID ) . '" id="wpas_ticket_' . $wpas_tickets->post->ID . '">';

					foreach ( $columns as $column_id => $column ) {

						$data_attributes = '';

						// Add the data attributes if any
						if ( isset( $column['column_attributes']['body'] ) && is_array( $column['column_attributes']['body'] ) ) {
							$data_attributes = wpas_array_to_data_attributes( $column['column_attributes']['body'], true );
						}

						printf( '<div %s>', $data_attributes );

						/* Display the content for this column */
						wpas_get_tickets_list_column_content( $column_id, $column );

						echo '</div>';

					}

					echo '</div>';
				
				endwhile;

				wp_reset_query(); ?>
			</div>
			<div class="navigation">
				<tr>
					<td colspan="<?php echo count($columns); ?>">
						<ul class="wpas_table_pagination"></ul>
					</td>
				</tr>
			</div>
		</div>
	</div>
<?php else:
	echo wpas_get_notification_markup( 'info', sprintf( __( 'You haven\'t submitted a ticket yet. <a href="%s">Click here to submit your first ticket</a>.', 'awesome-support' ), wpas_get_submission_page_url() ) );
	echo '<div class="notfound">
	<p>تا کنون تیکتی ثبت نشده است !</p>
</div>';
endif; ?>