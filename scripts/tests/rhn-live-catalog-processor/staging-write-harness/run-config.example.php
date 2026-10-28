<?php
/** Copy to run-config.php only after the Kosmos probe is terminal and exact staging targets are selected. */
return array(
	'run_id'                   => 'replace-with-unique-run-id',
	'token_hash'               => 'replace-with-lowercase-sha256-of-one-use-token',
	'release_dir'              => __DIR__ . '/release/rhn-live-catalog-processor',
	'probe_event_id'           => '6b9495a4-0807-4d00-9928-fdf629da61a3',
	'probe_terminal_status'    => 'PENDING',
	'probe_checked_at_utc'     => '',
	'hook_fingerprint'         => 'replace-after-a-separate-read-only-callback-inventory-and-review',
	'targets'                  => array(
		'approved' => array(
			'product_id' => 0,
			'sku'        => '',
			'action'     => array(
				'action' => 'approved', 'sku' => '', 'source_row' => 0,
				'record' => array( 'sku' => '', 'approved' => true, 'source' => 'Rollback-only staging adapter rehearsal', 'sheet_row' => 0, 'short_description' => '' ),
			),
		),
		'withdrawal' => array(
			'product_id' => 0,
			'sku'        => '',
			'action'     => array(
				'action' => 'withdrawal', 'sku' => '', 'source_row' => 0,
				'record' => array( 'sku' => '', 'source' => 'Explicit rollback-only staging withdrawal rehearsal', 'row' => 0 ),
			),
		),
	),
);
