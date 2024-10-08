<?php
/**
 * Shell - class to work with $wpdb global object
 */
#[\AllowDynamicProperties]
class dbCfs {
    /**
     * Execute query and return results
     * @param string $query query to be executed
     * @param string $get what must be returned - one value (one), one row (row), one col (col) or all results (all - by default)
     * @param const $outputType type of returned data
     * @return mixed data from DB
     */
    static public $query = '';
    static public function get($query, $get = 'all', $outputType = ARRAY_A) {
        return false;
        //global $wpdb;
        // $get = strtolower($get);
        // $res = NULL;
        // $query = self::prepareQuery($query);
        //error_log('QUERY: '. $query);
    }
    /**
     * Execute one query
     * @return query results
     */
    static public function query($query) {
        return false;
    }
    /**
     * Get last insert ID
     * @return int last ID
     */
    static public function insertID() {
        global $wpdb;
        return $wpdb->insert_id;
    }
    /**
     * Get number of rows returned by last query
     * @return int number of rows
     */
    static public function numRows() {
        global $wpdb;
        return $wpdb->num_rows;
    }
    /**
     * Replace prefixes in custom query. Suported next prefixes:
     * #__  Worcfsess prefix
     * ^__  Store plugin tables prefix (@see CFS_DB_PREF if config.php)
     * @__  Compared of WP table prefix + Store plugin prefix (@example wp_s_)
     * @param string $query query to be executed
     */
    static public function prepareQuery($query) {
        global $wpdb;
        return str_replace(
                array('#__', '^__', '@__'),
                array($wpdb->prefix, CFS_DB_PREF, $wpdb->prefix. CFS_DB_PREF),
                $query);
    }
    static public function getError() {
        global $wpdb;
        return $wpdb->last_error;
    }
    static public function lastID() {
        global $wpdb;
        return $wpdb->insert_id;
    }
    static public function timeToDate($timestamp = 0) {
        if($timestamp) {
            if(!is_numeric($timestamp))
                $timestamp = dateToTimestampCfs($timestamp);
            return date('Y-m-d', $timestamp);
        } else {
            return date('Y-m-d');
        }
    }
    static public function dateToTime($date) {
        if(empty($date)) return '';
        if(strpos($date, CFS_DATE_DL)) return dateToTimestampCfs($date);
        $arr = explode('-', $date);
        return dateToTimestampCfs($arr[2]. CFS_DATE_DL. $arr[1]. CFS_DATE_DL. $arr[0]);
    }
    static public function exist($table) {
        global $wpdb;
        switch ($table) {
          case 'cfs_contacts':
            $res = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}cfs_contacts'");
          break;
          case 'cfs_countries':
            $res = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}cfs_countries'");
          break;
          case 'cfs_files':
            $res = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}cfs_files'");
          break;
          case 'cfs_forms':
            $res = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}cfs_forms'");
          break;
          case 'cfs_forms_rating':
            $res = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}cfs_forms_rating'");
          break;
          case 'cfs_membership_presets':
            $res = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}cfs_membership_presets'");
          break;
          case 'cfs_modules':
            $res = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}cfs_modules'");
          break;
          case 'cfs_statistics':
            $res = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}cfs_statistics'");
          break;
          case 'cfs_modules_type':
            $res = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}cfs_modules_type'");
          break;
          case 'cfs_subscribers':
            $res = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}cfs_subscribers'");
          break;
          case 'cfs_usage_stat':
            $res = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}cfs_usage_stat'");
          break;
        }
        return !empty($res);
    }
    static public function prepareHtml($d) {
        if(is_array($d)) {
            foreach($d as $i => $el) {
                $d[ $i ] = self::prepareHtml( $el );
            }
        } else {
            $d = esc_html($d);
        }
        return $d;
    }
	static public function prepareHtmlIn($d) {
		if(is_array($d)) {
            foreach($d as $i => $el) {
                $d[ $i ] = self::prepareHtml( $el );
            }
        } else {
            $d = wp_filter_nohtml_kses($d);
        }
        return $d;
    }
	static public function escape($data) {
		global $wpdb;
		return $wpdb->_escape($data);
	}
	static public function getAutoIncrement($table) {
		return (int) self::get('SELECT AUTO_INCREMENT
			FROM information_schema.tables
			WHERE table_name = "'. $table. '"
			AND table_schema = DATABASE( );', 'one');
	}
	static public function setAutoIncrement($table, $autoIncrement) {
		return self::query("ALTER TABLE `". $table. "` AUTO_INCREMENT = ". $autoIncrement. ";");
	}
}
