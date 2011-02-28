<?php
/**
 * WicketPixie
 * (c) 2006-2011 Eddie Ringle <eddie@eringle.net>
 * Provided by Chris Pirillo <chris@pirillo.com>
 *
 * Licensed under the New BSD License.
 */

class SourceManager
{
    static function install()
    {
        global $wpdb;

        $table_revision = '1.0';

        $table_name = $wpdb->prefix . 'wipi_sources';

        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name
                || get_option('wipi_source_manager_revision') != $table_revision) {
            $sql = "CREATE TABLE " . $table_name . " (
                    id int NOT NULL AUTO_INCREMENT,
                    title varchar(255) NOT NULL,
                    profile_url varchar(255) NOT NULL,
                    feed_url varchar(255) NOT NULL,
                    lifestream boolean NOT NULL,
                    updates boolean NOT NULL,
                    favicon varchar(255) NOT NULL,
                    UNIQUE KEY id (id)
                    );";
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta($sql);
            update_option('wipi_source_manager_revision', $table_revision);
        }
    }

    static function collect_sources()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wipi_sources';
        $sources = $wpdb->get_results("SELECT * FROM $table_name");
        if (is_array($sources)) {
            return $sources;
        } else {
            return array();
        }
    }

    static function collect_stream_sources()
    {
        global $wpdb;

        $table = $wpdb->prefix . 'wipi_sources';
        $streams = $wpdb->get_results("SELECT * FROM $table WHERE lifestream = 1");

        if (is_array($streams)) {
            return $streams;
        } else {
            return array();
        }
    }

    static function add($req)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wipi_sources';
        if ($req['title'] && $req['profile_url']) {
            if (!$wpdb->get_var("SELECT id FROM $table WHERE feed_url = '" . $req['feed_url'] . "'")) {
                preg_match('@^(?:http://)?([^/]+)@1', $req['profile_url'], $matches);
                $favicon_domain = $matches[1];
                $sql = $wpdb->prepare("INSERT INTO " . $table . " (id, title, profile_url, feed_url, lifestream, updates, favicon) VALUES('', '%s', '%s', '%s', '%d', '%d', '%s')",
                        $req['title'], $req['profile_url'], $req['feed_url'],
                        ($req['lifestream'] == 1) ? 1 : 0, ($req['updates'] == 1) ? 1 : 0,
                        'http://www.google.com/s2/favicons?domain=' . $favicon_domain);
            }
        }
    }
}

function wipi_admin_render_source_manager()
{
?>
    <h2>Source Manager</h2>
    <p>Manage all of your social stream sources here.</p>
<?php
}
?>