<?php
class Log_model extends MY_Model
{
	function earliest()
	{
		$return = 0;
		$this->db->select( 'created_at' );
		$this->db->order_by( 'created_at', 'ASC');
		$this->db->limit(1);
		$result	= $this->db->get( 'log' );
		foreach( $result->result_array() as $e )
		{
			$return = $e['created_at'];
		}
		return $return;
	}

	function get( $from, $to )
	{
		$this->db->where( 'created_at >= ', $from );
		$this->db->where( 'created_at <= ', $to );

		$what = array(
			'address',
			'COUNT(address) AS count',
			'search',
			'COUNT(DISTINCT(search)) AS count_search',
			);

		$this->db->select( $what );
		$this->db->group_by( 'address');
		$this->db->order_by( 'count', 'DESC');
		$this->db->limit(50);

		$return	= array();
		$result	= $this->db->get( 'log' );
		foreach( $result->result_array() as $e )
		{
			if( $e['count_search'] > 1 )
			{
				$e['search'] = array();
				$what = array(
					'search',
					'COUNT(search) AS count',
					);

				$this->db->select( $what );

				$this->db->where( 'address', $e['address'] );
				$this->db->where( 'created_at >= ', $from );
				$this->db->where( 'created_at <= ', $to );

				$this->db->group_by( 'search');
				$this->db->order_by( 'count', 'DESC');
				$result2 = $this->db->get( 'log' );
				foreach( $result2->result_array() as $e2 )
				{
					$e['search'][$e2['search']] = $e2['count'];
				}
			}
			else
			{
				if( $e['search'] )
				{
					$e['search'] = array(
						$e['search'] => $e['count']
						);
				}
			}
			$return[] = $e;
		}
		return $return;
	}

	function add( $entry = array() )
	{
		if( ! $entry )
			return;
		$entry['created_at'] = time();
		$entry['address'] = strtolower($entry['address']);
		$entry['search'] = strtolower($entry['search']);
		$entry['remote'] = $_SERVER['REMOTE_ADDR'];

		$this->db->insert('log', $entry);
	}
}