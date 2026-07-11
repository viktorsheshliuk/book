<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
        <h1><?php echo $heading_title; ?></h1>
		<?php if ($decoded_logs) { ?>
		<table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Event Name</th>
                    <th>Event Data</th>
                </tr>
            </thead>
            <tbody>
			<?php foreach ($decoded_logs as $log) { ?>
                <tr>
                    <td><?php echo $log['date']; ?></td>
                    <td><?php echo $log['event_name']; ?></td>
                    <td><pre><?php print_r($log['event_data']); ?></pre></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
       <?php } else { ?>
        <p>No log entries found.</p>
        <?php } ?>
    </div>
  </div>
  <div class="container-fluid">
 
  </div>
</div>
<?php echo $footer; ?>