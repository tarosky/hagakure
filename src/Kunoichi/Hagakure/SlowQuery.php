<?php

namespace Kunoichi\Hagakure;


use Kunoichi\Hagakure\Pattern\Singleton;
use Kunoichi\Hagakure\Utility\EnvInfo;

/**
 * Record slow query for log from PHP.
 */
class SlowQuery extends Singleton {

	use EnvInfo;

	/**
	 * @var array Query list.
	 */
	protected $queries = [];

	/**
	 * {@inheritdoc}
	 */
	protected function init() {
		if ( $this->should_save_queries() ) {
			add_filter( 'log_query_custom_data', [ $this, 'log_query' ], 9999, 5 );
		}
	}

	/**
	 * Filter query log and save as slow log.
	 *
	 * @see wpdb::log_query()
	 * @param array  $query_data      Custom query data. Default empty.
	 * @param string $query           Query's SQL.
	 * @param float  $query_time      Total time spent on the query in seconds.
	 * @param string $query_callstack CSV list of calling functions.
	 * @param float  $query_start     Unix timestamp of the time at the start of the query.
	 *
	 * @return array
	 */
	public function log_query( $query_data, $query, $query_time, $query_callstack, $query_start ) {
		if ( $query_time > $this->get_slow_query_time() ) {
			error_log( sprintf(
				"[Hagakure Slow Log]\t%f seconds\t@%s\n%s\n%s",
				$query_time,
				$this->uri_info(),
				$query,
				$query_callstack
			) );
		}
		return $query_data;
	}

	/**
	 * Get time limit recognized as slow query in second.
	 *
	 * @return float
	 */
	protected function get_slow_query_time() {
		return (float) apply_filters( 'hagakure_slow_query_time', 1.5 );
	}

	/**
	 * Should save queries?
	 *
	 * @return bool
	 */
	protected function should_save_queries() {
		return defined( 'SAVEQUERIES' ) && SAVEQUERIES;
	}
}
