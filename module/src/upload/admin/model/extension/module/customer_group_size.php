<?php

/*
<insertfile>_inc/summary.txt</insertfile>
*/

class ModelExtensionModuleCustomerGroupSize extends Model {
	public function getCustomerGroupSize($customer_group_id) {
		$sql = '';

		$sql .= 'SELECT COUNT(*) as `size` ';
		$sql .= 'FROM `' . DB_PREFIX . 'customer` ';
		$sql .= 'WHERE ';
		$sql .= '`customer_group_id` = "' . (int)$customer_group_id . '"';

		return $this->db->query($sql)->row['size'];
	}
}
